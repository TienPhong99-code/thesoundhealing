<?php
defined('ABSPATH') || exit;

/**
 * Sau khi CF7 gửi mail thành công: lưu booking data vào transient + set cookie.
 * JS sẽ đọc data-buy-url trên wrapper và redirect sang WC checkout.
 */
add_filter('wpcf7_skip_mail', '__return_true');

add_action('wpcf7_mail_sent', function ($cf7) {
    $submission = WPCF7_Submission::get_instance();
    if (!$submission) return;
    $d = $submission->get_posted_data();

    // CF7 returns arrays for select/radio fields. Dynamic options injected via
    // wpcf7_form_elements are not in CF7's shortcode definition, so CF7 may
    // sanitize those values to empty. Fall back to raw $_POST when needed.
    $raw = wp_unslash((array) $_POST);

    // Extract a scalar string from either a CF7 array value or a raw $_POST value.
    $scalar = function ($val): string {
        if (is_array($val)) {
            $val = array_values(array_filter(array_map('trim', array_map('strval', $val))));
            $val = $val[0] ?? '';
        }
        return sanitize_text_field(trim((string) $val));
    };

    $pick = function (array $keys) use ($d, $raw, $scalar): string {
        foreach ($keys as $k) {
            $v = $scalar($d[$k] ?? null);
            if ($v !== '') return $v;
            $v = $scalar($raw[$k] ?? null);
            if ($v !== '') return $v;
        }
        return '';
    };

    $booking = [
        'fullname'   => $scalar($d['fullname'] ?? ($raw['fullname'] ?? '')),
        'email'      => sanitize_email($scalar($d['email']  ?? ($raw['email']  ?? ''))),
        'phone'      => $scalar($d['phone']    ?? ($raw['phone']    ?? '')),
        'date'       => $pick(['kh-date',      'ws-date',      'dv-date']),
        'time'       => $pick(['kh-time',       'ws-time',      'dv-time']),
        'location'   => $pick(['kh-location',   'ws-location',  'dv-branch']),
        'guests'     => $pick(['ws-guests', 'kh-guests', 'dv-guests']),
        'instructor' => $pick(['kh-instructor', 'ws-instructor', 'dv-instructor']),
        'children'   => $pick(['kh-children']),
        'source_url' => esc_url_raw(wp_get_referer() ?: ''),
    ];

    $token = wp_generate_password(32, false);
    set_transient('tsh_booking_' . $token, $booking, HOUR_IN_SECONDS);
    setcookie('tsh_booking_token', $token, time() + HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
});

/**
 * Inject tên dịch vụ (post title) vào posted data để dùng làm mail tag [service_name].
 * Lấy post_id từ HTTP referer vì filter này chạy trong AJAX context.
 */
add_filter('wpcf7_posted_data', function ($data) {
    $post_id = 0;
    $referer = wp_get_referer();
    if ($referer) {
        $id = (int) url_to_postid($referer);
        if ($id && get_post_type($id) === 'dich_vu') {
            $post_id = $id;
        }
    }
    if ($post_id) {
        $data['service_name'] = get_the_title($post_id);
    }
    return $data;
});

/**
 * Bypass "Undefined value was submitted through this field." cho dynamic select fields.
 *
 * CF7 phiên bản mới dùng SWV (Simple Web Validator) schema.
 * Schema enum được build trong wpcf7_swv_add_select_enum_rules (priority 20)
 * bằng cách gọi scan_form_tags() → wpcf7_form_tag filter cho từng tag.
 * Inject submitted value vào $tag->values tại đây để schema accept nó.
 *
 * Giữ lại wpcf7_validate_select hooks để tương thích CF7 phiên bản cũ hơn.
 */
$_tsh_dynamic_selects = [
    'kh-instructor',
    'kh-time',
    'kh-location',
    'ws-instructor',
    'ws-time',
    'ws-location',
    'ws-guests',
    'dv-instructor',
    'dv-time',
    'dv-branch',
];

// CF7 mới: inject vào tag trước khi SWV enum schema được build
add_filter('wpcf7_form_tag', function ($tag) use ($_tsh_dynamic_selects) {
    if (!isset($tag->name) || !in_array($tag->name, $_tsh_dynamic_selects, true)) return $tag;
    $val = sanitize_text_field(trim((string) ($_POST[$tag->name] ?? '')));
    if ($val === '' || in_array($val, (array) $tag->values, true)) return $tag;
    $tag->values[] = $val;
    $tag->labels[] = $val;
    return $tag;
}, 10, 1);

// CF7 cũ: wpcf7_validate_select hooks (backward compat)
$_tsh_inject_fn = function ($result, $tag) use ($_tsh_dynamic_selects) {
    if (!in_array($tag->name, $_tsh_dynamic_selects, true)) return $result;
    $val = sanitize_text_field(trim((string) ($_POST[$tag->name] ?? '')));
    if ($val !== '') {
        $tag->values[] = $val;
        $tag->labels[] = $val;
    }
    return $result;
};
add_filter('wpcf7_validate_select',  $_tsh_inject_fn, 9, 2);
add_filter('wpcf7_validate_select*', $_tsh_inject_fn, 9, 2);

$_tsh_reflection_fn = function ($result, $tag) use ($_tsh_dynamic_selects) {
    if (!in_array($tag->name, $_tsh_dynamic_selects, true)) return $result;
    $val = sanitize_text_field(trim((string) ($_POST[$tag->name] ?? '')));
    if ($val === '') return $result;
    try {
        $ref  = new ReflectionClass($result);
        $prop = $ref->getProperty('invalid_fields');
        $prop->setAccessible(true);
        $fields = (array) $prop->getValue($result);
        unset($fields[$tag->name]);
        $prop->setValue($result, $fields);
    } catch (\Throwable $e) {
    }
    return $result;
};
add_filter('wpcf7_validate_select',  $_tsh_reflection_fn, 20, 2);
add_filter('wpcf7_validate_select*', $_tsh_reflection_fn, 20, 2);

/**
 * wpcf7_form_elements — inject <option> vào rendered HTML.
 * REPLACE toàn bộ options trong select (không append để tránh duplicate).
 */
add_filter('wpcf7_form_elements', function ($html) {
    $post_type = get_post_type(get_queried_object_id());
    if (!in_array($post_type, ['dich_vu', 'khoa_hoc', 'workshop'], true)) return $html;

    $post_id = (int) get_queried_object_id();
    if (!$post_id) return $html;

    // Đọc dữ liệu booking đã lưu để pre-select option đúng khi user quay lại form.
    $token   = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
    $booking = $token ? (array) get_transient('tsh_booking_' . $token) : [];

    $build_options = function (string $placeholder, array $values, string $selected_val = ''): string {
        $out = '<option value="">' . esc_html($placeholder) . '</option>';
        foreach ($values as $v) {
            $sel = ($selected_val !== '' && $v === $selected_val) ? ' selected' : '';
            $out .= '<option value="' . esc_attr($v) . '"' . $sel . '>' . esc_html($v) . '</option>';
        }
        return $out;
    };

    // Helper: thay value="" thành value="$val" trên input có name khớp.
    $set_input_value = function (string $html, string $name, string $val): string {
        if ($val === '') return $html;
        return preg_replace(
            '/(<input\b[^>]*\bname="' . preg_quote($name, '/') . '"[^>]*)\bvalue="[^"]*"([^>]*>)/i',
            '$1value="' . esc_attr($val) . '"$2',
            $html
        );
    };

    if ($post_type === 'dich_vu') {

        $pre_date     = $booking['date']       ?? '';
        $pre_time     = $booking['time']       ?? '';
        $pre_location = $booking['location']   ?? '';
        $pre_instr    = $booking['instructor'] ?? '';

        $html = $set_input_value($html, 'dv-date', $pre_date);

        // ── dv-instructor ─────────────────────────────────────────────────
        $ins_values = [];
        $rows       = get_field('dv_instructors', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $name = trim($row['dv_instructor_name'] ?? '');
                if ($name) $ins_values[] = $name;
            }
        } else {
            $single = trim((string) get_field('dv_instructor_name', $post_id));
            if ($single) $ins_values[] = $single;
        }
        if ($ins_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="dv-instructor"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options(__('Chọn người hướng dẫn', 'monamedia'), $ins_values, $pre_instr) . '$3',
                $html
            );
        }

        // ── dv-branch ────────────────────────────────────────────────────
        $branch_values = [];
        $location_raw  = get_field('dv_location', $post_id);
        if (!empty($location_raw)) {
            foreach (explode("\n", $location_raw) as $line) {
                $line = trim($line);
                if ($line) $branch_values[] = $line;
            }
        }
        if ($branch_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="dv-branch"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options(__('Chọn chi nhánh', 'monamedia'), $branch_values, $pre_location) . '$3',
                $html
            );
        }

        // ── dv-time ───────────────────────────────────────────────────────
        $time_values = [];
        $rows        = get_field('dv_time_slots', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $slot = trim($row['dv_time_slot'] ?? '');
                if ($slot) $time_values[] = $slot;
            }
        }
        if (empty($time_values)) {
            $time_values = ['09:00 - 10:30', '10:30 - 12:00', '14:00 - 15:30', '15:30 - 17:00', '17:00 - 18:30'];
        }
        $html = preg_replace(
            '/(<select[^>]*\bname="dv-time"[^>]*>)([\s\S]*?)(<\/select>)/i',
            '$1' . $build_options(__('Chọn khung giờ', 'monamedia'), $time_values, $pre_time) . '$3',
            $html
        );
    } elseif ($post_type === 'khoa_hoc') {

        $pre_date     = $booking['date']       ?? '';
        $pre_time     = $booking['time']       ?? '';
        $pre_location = $booking['location']   ?? '';
        $pre_instr    = $booking['instructor'] ?? '';

        $html = $set_input_value($html, 'kh-date', $pre_date);

        // ── kh-instructor ─────────────────────────────────────────────────
        $ins_values = [];
        $rows       = get_field('kh_instructors', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $name = trim($row['kh_instructor_name'] ?? '');
                if ($name) $ins_values[] = $name;
            }
        } else {
            $single = trim((string) get_field('instructor_name', $post_id));
            if ($single) $ins_values[] = $single;
        }
        if ($ins_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="kh-instructor"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options(__('Chọn người hướng dẫn', 'monamedia'), $ins_values, $pre_instr) . '$3',
                $html
            );
        }

        // ── kh-branch ────────────────────────────────────────────────────
        $branch_values = [];
        $location_raw  = get_field('location', $post_id);
        if (!empty($location_raw)) {
            foreach (explode("\n", $location_raw) as $line) {
                $line = trim($line);
                if ($line) $branch_values[] = $line;
            }
        }
        if ($branch_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="kh-location"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options(__('Chọn chi nhánh', 'monamedia'), $branch_values, $pre_location) . '$3',
                $html
            );
        }

        // ── kh-time ───────────────────────────────────────────────────────
        $time_values = [];
        $rows        = get_field('kh_time_slots', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $slot = trim($row['kh_time_slot'] ?? '');
                if ($slot) $time_values[] = $slot;
            }
        }
        if (empty($time_values)) {
            $time_values = ['07:00 - 09:00', '09:00 - 11:00', '14:00 - 16:00', '16:00 - 18:00'];
        }
        $html = preg_replace(
            '/(<select[^>]*\bname="kh-time"[^>]*>)([\s\S]*?)(<\/select>)/i',
            '$1' . $build_options(__('Chọn khung giờ', 'monamedia'), $time_values, $pre_time) . '$3',
            $html
        );
    } elseif ($post_type === 'workshop') {

        $pre_time     = $booking['time']       ?? '';
        $pre_location = $booking['location']   ?? '';
        $pre_date     = $booking['date']       ?? '';
        $pre_instr    = $booking['instructor'] ?? '';
        $pre_guests   = $booking['guests']     ?? '';

        $html = $set_input_value($html, 'ws-date', $pre_date);

        // ── ws-instructor ─────────────────────────────────────────────────
        $ins_values = [];
        $rows       = get_field('ws_instructors', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $name = trim($row['ws_instructor_name'] ?? '');
                if ($name) $ins_values[] = $name;
            }
        } else {
            $single = trim((string) get_field('ws_instructor_name', $post_id));
            if ($single) $ins_values[] = $single;
        }
        if ($ins_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="ws-instructor"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options(__('Chọn người hướng dẫn', 'monamedia'), $ins_values, $pre_instr) . '$3',
                $html
            );
        }

        // ── ws-branch ────────────────────────────────────────────────────
        $branch_values = [];
        $location_raw  = get_field('ws_location', $post_id);
        if (!empty($location_raw)) {
            foreach (explode("\n", $location_raw) as $line) {
                $line = trim($line);
                if ($line) $branch_values[] = $line;
            }
        }
        if ($branch_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="ws-location"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options(__('Chọn chi nhánh', 'monamedia'), $branch_values, $pre_location) . '$3',
                $html
            );
        }

        // ── ws-time ───────────────────────────────────────────────────────
        $time_values = [];
        $rows        = get_field('ws_time_slots', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $slot = trim($row['ws_time_slot'] ?? '');
                if ($slot) $time_values[] = $slot;
            }
        }
        if (empty($time_values)) {
            $time_values = ['09:00 - 10:30', '10:30 - 12:00', '14:00 - 15:30', '15:30 - 17:00'];
        }
        $html = preg_replace(
            '/(<select[^>]*\bname="ws-time"[^>]*>)([\s\S]*?)(<\/select>)/i',
            '$1' . $build_options(__('Chọn khung giờ', 'monamedia'), $time_values, $pre_time) . '$3',
            $html
        );
    }

    // Đẩy chuỗi đã dịch (gettext) xuống JS qua data-attribute trên input date.
    // JS (khoa-hoc/dich-vu/ws.js) đọc JSON này để hiển thị "Chọn ngày" + các pill.
    $cf7_i18n = wp_json_encode([
        'selectDate'  => __('Chọn ngày', 'monamedia'),
        'today'       => __('Hôm nay', 'monamedia'),
        'tomorrow'    => __('Ngày mai', 'monamedia'),
        'thisWeekend' => __('Cuối tuần này', 'monamedia'),
        'monthAbbr'   => __('thg', 'monamedia'),
    ]);
    $html = preg_replace(
        '/(<input\b(?![^>]*\bdata-mona-i18n=)[^>]*\btype="date"[^>]*)(>)/i',
        '$1 data-mona-i18n="' . esc_attr($cf7_i18n) . '"$2',
        $html
    );

    return $html;
});

/**
 * Ép dịch các chuỗi theme (domain monamedia) sang tiếng Anh khi xem bản EN.
 * Lý do: các chuỗi này bị WPML can thiệp nên __()/.mo không trả bản dịch,
 * dù .po/.mo đã có. Filter chạy priority cao (sau WPML) để ghi đè.
 */
add_filter('gettext_monamedia', function ($translation, $text) {
    if (function_exists('determine_locale') && strpos(determine_locale(), 'en') === 0) {
        static $map = [
            // Placeholder select booking
            'Chọn khung giờ'       => 'Select time slot',
            'Chọn chi nhánh'        => 'Select branch',
            'Chọn người hướng dẫn' => 'Select instructor',
            // Trang đặt lịch thành công
            'E-TICKET LÀM QUÀ TẶNG' => 'Gift This Session',
            'Tính năng sắp ra mắt' => 'Coming soon',
        ];
        if (isset($map[$text])) return $map[$text];
    }
    return $translation;
}, 99, 2);
