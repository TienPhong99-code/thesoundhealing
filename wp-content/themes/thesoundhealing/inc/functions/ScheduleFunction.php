<?php

defined('ABSPATH') || defined('MONA_SCHEDULE_TEST') || (PHP_SAPI === 'cli') || exit;

/**
 * Nhãn thứ rút gọn theo key date('N'). Mon=1..Sun=7.
 */
function mona_weekday_short_labels(): array
{
    return ['1' => '2', '2' => '3', '3' => '4', '4' => '5', '5' => '6', '6' => '7', '7' => 'CN'];
}

/**
 * Sinh danh sách ngày Y-m-d cho lịch định kỳ theo thứ trong tuần.
 */
function mona_generate_recurring_dates(string $start, string $end, array $weekdays, int $maxDays = 366): array
{
    $weekdays = array_map('strval', $weekdays);
    if (empty($weekdays)) return [];
    $ts = strtotime($start);
    $te = strtotime($end);
    if (!$ts || !$te || $te < $ts) return [];
    if (($te - $ts) / 86400 > $maxDays) return [];

    $out = [];
    for ($t = $ts; $t <= $te; $t += 86400) {
        if (in_array(date('N', $t), $weekdays, true)) {
            $out[] = date('Y-m-d', $t);
        }
    }
    sort($out);
    return array_values(array_unique($out));
}

/**
 * Chuẩn hoá mảng chuỗi ngày hỗn hợp về Y-m-d, sort + unique.
 * Hỗ trợ: Y-m-d, d-m-Y, "12 Tháng 8, 2026", strtotime fallback.
 */
function mona_normalize_date_list(array $parts): array
{
    $out = [];
    foreach ($parts as $part) {
        $part = trim((string) $part);
        if ($part === '') continue;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $part)) {
            $out[] = $part;
        } elseif (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $part, $m)) {
            $out[] = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        } elseif (preg_match('/(\d{1,2})\s+[Tt]háng\s+(\d{1,2})[,\s]+(\d{4})/u', $part, $m)) {
            $out[] = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        } elseif (($t = strtotime($part)) && $t > 0) {
            $out[] = date('Y-m-d', $t);
        }
    }
    sort($out);
    return array_values(array_unique($out));
}

/**
 * Câu tóm tắt cho lịch định kỳ.
 * VN: "Thứ 3, 5, 7 · 12/08 – 12/09". EN: "Tue, Thu, Sat · 12/08 – 12/09".
 */
function mona_recurring_summary(array $weekdays, string $start, string $end): string
{
    $weekdays = array_map('strval', $weekdays);
    sort($weekdays);

    // EN: liệt kê thứ bằng viết tắt tiếng Anh (Mon..Sun), bỏ tiền tố "Thứ".
    // VN: giữ dạng gọn "Thứ 3, 4, 5, ..." như cũ.
    $is_en = function_exists('determine_locale') && strpos(determine_locale(), 'en') === 0;
    $names = [];
    if ($is_en) {
        $en = ['1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu', '5' => 'Fri', '6' => 'Sat', '7' => 'Sun'];
        foreach ($weekdays as $w) {
            if (isset($en[$w])) $names[] = $en[$w];
        }
        $wd = $names ? implode(', ', $names) : '';
    } else {
        $labels = mona_weekday_short_labels();
        foreach ($weekdays as $w) {
            if (isset($labels[$w])) $names[] = $labels[$w];
        }
        $wd = $names ? 'Thứ ' . implode(', ', $names) : '';
    }

    $ts = strtotime($start);
    $te = strtotime($end);
    $range = ($ts && $te) ? date('d/m', $ts) . ' – ' . date('d/m', $te) : '';
    return trim($wd . ($wd && $range ? ' · ' : '') . $range);
}

/**
 * Map post_type -> tên field ACF.
 */
function mona_schedule_field_map(int $post_id): array
{
    $pt = get_post_type($post_id);
    if ($pt === 'workshop') {
        return ['type' => 'ws_schedule_type', 'single' => 'ws_date_single', 'start' => 'ws_date_start', 'end' => 'ws_date_end', 'weekdays' => 'ws_weekdays', 'legacy' => 'ws_date'];
    }
    if ($pt === 'dich_vu') {
        return ['type' => 'dv_schedule_type', 'single' => 'dv_date_single', 'start' => 'dv_date_start', 'end' => 'dv_date_end', 'weekdays' => 'dv_weekdays', 'legacy' => 'dv_available_days'];
    }
    return ['type' => 'kh_schedule_type', 'single' => 'kh_date_single', 'start' => 'kh_date_start', 'end' => 'kh_date_end', 'weekdays' => 'kh_weekdays', 'legacy' => 'start_date'];
}

/**
 * Trả về lịch đã chuẩn hoá cho 1 post (khoa_hoc/workshop).
 *
 * @return array{type:string, dates:string[], future:string[], summary:string, is_past:bool}
 */
function mona_expand_schedule(int $post_id): array
{
    static $cache = [];
    if (isset($cache[$post_id])) return $cache[$post_id];

    $f = mona_schedule_field_map($post_id);
    $sched_type = get_field($f['type'], $post_id);

    $dates = [];
    $summary = '';
    $type = 'legacy';

    if ($sched_type === 'single') {
        $type = 'single';
        $d = get_field($f['single'], $post_id);
        $dates = $d ? mona_normalize_date_list([$d]) : [];
        $summary = $dates ? date('d/m/Y', strtotime($dates[0])) : '';
    } elseif ($sched_type === 'recurring') {
        $type = 'recurring';
        $start = get_field($f['start'], $post_id);
        $end   = get_field($f['end'], $post_id);
        $wd    = get_field($f['weekdays'], $post_id);
        $wd    = is_array($wd) ? $wd : [];
        $dates = ($start && $end) ? mona_generate_recurring_dates($start, $end, $wd) : [];
        $summary = ($start && $end) ? mona_recurring_summary($wd, $start, $end) : '';
    } else {
        // Legacy: chuỗi text cũ.
        $raw = get_field($f['legacy'], $post_id);
        if ($raw) {
            $dates = mona_normalize_date_list(preg_split('/\s*,\s*/', trim($raw)));
            if (count($dates) === 1) {
                $summary = date('d/m/Y', strtotime($dates[0]));
            } elseif (count($dates) > 1) {
                $summary = date('d/m/Y', strtotime($dates[0])) . ' – ' . date('d/m/Y', strtotime(end($dates)));
            } else {
                $summary = (string) $raw; // không parse được ngày -> giữ nguyên text
            }
        }
    }

    $today  = date('Y-m-d');
    $future = array_values(array_filter($dates, fn($d) => $d >= $today));

    return $cache[$post_id] = [
        'type'    => $type,
        'dates'   => $dates,
        'future'  => $future,
        'summary' => $summary,
        'is_past' => empty($future),
    ];
}

/**
 * Chuỗi ngắn cho card. '' nếu không có lịch.
 */
function mona_schedule_label(int $post_id): string
{
    $s = mona_expand_schedule($post_id);
    return $s['summary'];
}

/**
 * Lịch đã qua hết chưa -> dùng cho nút "Hết hạn" trên card.
 * CHỈ coi là hết hạn khi CÓ ngày cụ thể nhưng tất cả đã qua.
 * Post không cấu hình lịch (dates rỗng, VD dịch vụ định kỳ "Thứ 2 – CN") -> KHÔNG hết hạn.
 * Nguồn chân lý duy nhất; card KHÔNG tự parse lại chuỗi ngày hiển thị.
 */
function mona_schedule_is_past(int $post_id): bool
{
    if ($post_id <= 0) return false;
    $s = mona_expand_schedule($post_id);
    return !empty($s['dates']) && empty($s['future']);
}

/**
 * Bỏ đoạn thứ ở đầu chuỗi lịch cho card gọn hơn, chỉ giữ phần ngày sau "·".
 * VD "Thứ 3, 4, 5, 6, 7, CN · 07/07 – 31/07" (VN) hoặc "Tue, Wed, ... · 07/07 – 31/07" (EN)
 * -> "07/07 – 31/07". Ngày đơn (không có "·") -> giữ nguyên.
 */
function mona_schedule_dates_only(string $summary): string
{
    $summary = trim($summary);
    $pos = strpos($summary, '·');
    return $pos !== false ? trim(substr($summary, $pos + strlen('·'))) : $summary;
}
