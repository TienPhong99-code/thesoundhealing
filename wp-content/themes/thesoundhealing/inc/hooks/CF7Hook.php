<?php
defined('ABSPATH') || exit;

/**
 * Sau khi CF7 gửi mail thành công: lưu booking data vào transient + set cookie.
 * JS sẽ đọc data-buy-url trên wrapper và redirect sang WC checkout.
 */
add_action('wpcf7_mail_sent', function ($cf7) {
    $submission = WPCF7_Submission::get_instance();
    if (!$submission) return;
    $d = $submission->get_posted_data();

    $booking = [
        'fullname'    => sanitize_text_field($d['fullname']        ?? ''),
        'email'       => sanitize_email($d['email']                ?? ''),
        'phone'       => sanitize_text_field($d['phone']           ?? ''),
        'date'        => sanitize_text_field($d['kh-date']         ?? ''),
        'time'        => sanitize_text_field($d['kh-time']         ?? ($d['dv-time'] ?? ($d['ws-time'] ?? ''))),
        'location'    => sanitize_text_field($d['kh-location']     ?? ($d['dv-branch'] ?? ($d['kh-branch'] ?? ($d['ws-branch'] ?? '')))),
        'guests'      => sanitize_text_field($d['ws-guests']       ?? ''),
        'instructor'  => sanitize_text_field($d['kh-instructor']   ?? ($d['dv-instructor'] ?? ($d['ws-instructor'] ?? ''))),
        'children'    => sanitize_text_field($d['kh-children']     ?? ''),
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
 * Xóa invalid state của dv-instructor và dv-time sau khi CF7 validate xong.
 * Dùng PHP Reflection để access private $invalid_fields của WPCF7_Validation.
 * Chạy priority 99 trên hook 'wpcf7_validate' (sau tất cả field validation).
 */
add_filter('wpcf7_validate', function ($result) {
    try {
        $ref  = new ReflectionClass($result);
        $prop = $ref->getProperty('invalid_fields');
        $prop->setAccessible(true);
        $fields = (array) $prop->getValue($result);
        unset($fields['dv-instructor'], $fields['dv-time'], $fields['dv-branch'], $fields['kh-instructor'], $fields['kh-time'], $fields['kh-branch'], $fields['ws-instructor'], $fields['ws-branch'], $fields['ws-time']);
        $prop->setValue($result, $fields);
    } catch (\Throwable $e) {
        // Fail silently nếu CF7 thay đổi internal API
    }
    return $result;
}, 99);

/**
 * wpcf7_form_elements — inject <option> vào rendered HTML.
 * REPLACE toàn bộ options trong select (không append để tránh duplicate).
 */
add_filter('wpcf7_form_elements', function ($html) {
    $post_type = get_post_type(get_queried_object_id());
    if (!in_array($post_type, ['dich_vu', 'khoa_hoc', 'workshop'], true)) return $html;

    $post_id = (int) get_queried_object_id();
    if (!$post_id) return $html;

    $build_options = function (string $placeholder, array $values): string {
        $out = '<option value="">' . esc_html($placeholder) . '</option>';
        foreach ($values as $v) {
            $out .= '<option value="' . esc_attr($v) . '">' . esc_html($v) . '</option>';
        }
        return $out;
    };

    if ($post_type === 'dich_vu') {

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
                '$1' . $build_options('Chọn người hướng dẫn', $ins_values) . '$3',
                $html
            );
        }

        // ── dv-branch ────────────────────────────────────────────────────
        $branch_values = [];
        $rows          = get_field('dv_branches', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $branch = trim($row['dv_branch_name'] ?? '');
                if ($branch) $branch_values[] = $branch;
            }
        }
        if ($branch_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="dv-branch"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options('Chọn chi nhánh', $branch_values) . '$3',
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
            '$1' . $build_options('Chọn khung giờ', $time_values) . '$3',
            $html
        );
    } elseif ($post_type === 'khoa_hoc') {

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
                '$1' . $build_options('Chọn người hướng dẫn', $ins_values) . '$3',
                $html
            );
        }

        // ── kh-branch ────────────────────────────────────────────────────
        $branch_values = [];
        $rows          = get_field('kh_branches', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $branch = trim($row['kh_branch_name'] ?? '');
                if ($branch) $branch_values[] = $branch;
            }
        }
        if ($branch_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="kh-branch"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options('Chọn chi nhánh', $branch_values) . '$3',
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
            '$1' . $build_options('Chọn khung giờ', $time_values) . '$3',
            $html
        );
    } elseif ($post_type === 'workshop') {

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
                '$1' . $build_options('Chọn người hướng dẫn', $ins_values) . '$3',
                $html
            );
        }

        // ── ws-branch ────────────────────────────────────────────────────
        $branch_values = [];
        $rows          = get_field('ws_branches', $post_id);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $branch = trim($row['ws_branch_name'] ?? '');
                if ($branch) $branch_values[] = $branch;
            }
        }
        if ($branch_values) {
            $html = preg_replace(
                '/(<select[^>]*\bname="ws-branch"[^>]*>)([\s\S]*?)(<\/select>)/i',
                '$1' . $build_options('Chọn chi nhánh', $branch_values) . '$3',
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
            '$1' . $build_options('Chọn khung giờ', $time_values) . '$3',
            $html
        );
    }

    return $html;
});
