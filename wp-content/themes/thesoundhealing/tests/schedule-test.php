<?php
// Chạy: php tests/schedule-test.php  (test thuần, không cần WordPress)
require __DIR__ . '/../inc/functions/ScheduleFunction.php';

$fail = 0;
function check($label, $actual, $expected) {
    global $fail;
    $a = json_encode($actual); $e = json_encode($expected);
    if ($a === $e) { echo "PASS: $label\n"; }
    else { echo "FAIL: $label\n  expected: $e\n  actual:   $a\n"; $GLOBALS['fail']++; }
}

// Định kỳ T3 (2), T5 (4), T7 (6) từ 2026-08-11 (T3) đến 2026-08-20 (T5)
check('recurring T3/T5/T7',
    mona_generate_recurring_dates('2026-08-11', '2026-08-20', ['2', '4', '6']),
    ['2026-08-11', '2026-08-13', '2026-08-15', '2026-08-18', '2026-08-20']
);
// weekdays rỗng -> []
check('empty weekdays', mona_generate_recurring_dates('2026-08-11', '2026-08-20', []), []);
// end < start -> []
check('end before start', mona_generate_recurring_dates('2026-08-20', '2026-08-11', ['2']), []);
// vượt maxDays -> []
check('over maxDays', mona_generate_recurring_dates('2026-01-01', '2030-01-01', ['1']), []);

// Chuẩn hoá hỗn hợp + sort + unique
check('normalize mixed',
    mona_normalize_date_list(['13-08-2026', '2026-08-12', '12-08-2026']),
    ['2026-08-12', '2026-08-13']
);

// Summary (VN mặc định — CLI không có determine_locale())
check('summary recurring',
    mona_recurring_summary(['2', '4', '6'], '2026-08-11', '2026-08-20'),
    'Thứ 3, 5, 7 · 11/08 – 20/08'
);

// dates_only: cắt phần thứ, giữ khoảng ngày (VN & EN)
check('dates_only VN', mona_schedule_dates_only('Thứ 3, 5, 7 · 11/08 – 20/08'), '11/08 – 20/08');
check('dates_only EN', mona_schedule_dates_only('Tue, Thu, Sat · 11/08 – 20/08'), '11/08 – 20/08');
check('dates_only ngày đơn', mona_schedule_dates_only('04/07/2026'), '04/07/2026');

// Summary EN — stub determine_locale() trả 'en_US' (đặt SAU test VN ở trên)
if (!function_exists('determine_locale')) {
    function determine_locale() { return 'en_US'; }
}
check('summary recurring EN',
    mona_recurring_summary(['2', '4', '6'], '2026-08-11', '2026-08-20'),
    'Tue, Thu, Sat · 11/08 – 20/08'
);

echo $fail === 0 ? "\nALL PASS\n" : "\n$fail FAILED\n";
exit($fail === 0 ? 0 : 1);
