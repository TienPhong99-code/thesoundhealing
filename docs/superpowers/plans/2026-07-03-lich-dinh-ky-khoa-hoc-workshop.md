# Lịch định kỳ cho Khóa học & Workshop — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Cho phép admin cấu hình lịch khóa học/workshop theo 2 dạng (cố định 1 ngày / định kỳ theo thứ trong khoảng ngày) thay vì gõ tay từng ngày, và hiển thị chuỗi ngày rõ ràng ra frontend.

**Architecture:** Một helper PHP thuần (`ScheduleFunction.php`) sinh & chuẩn hoá danh sách ngày + câu tóm tắt từ field ACF, có fallback đọc chuỗi text cũ. ACF thay field Text bằng cụm field có conditional logic. Template chỉ tiêu thụ output helper; JS form đặt lịch không đổi (chỉ đổi nguồn `availDates`).

**Tech Stack:** WordPress, PHP 7.4+, `vinkla/extended-acf` v14, jQuery + Flatpickr (không sửa).

## Global Constraints

- Thư mục làm việc gốc: `wp-content/themes/thesoundhealing/`. Mọi path dưới đây tương đối từ đây.
- Post type: `khoa_hoc` (prefix field `kh_`, field ngày cũ `start_date`) và `workshop` (prefix `ws_`, field cũ `ws_date`).
- File PHP mới PHẢI đăng ký trong `configs/loadFile.php`.
- Định dạng ngày lưu nội bộ: `Y-m-d`. Hiển thị: `d/m/Y`.
- Weekday key khớp `date('N')`: Mon=`1` … Sun=`7`.
- ACF field builder dùng kiểu fluent qua `mona_regist_acf_field_group()` (xem `inc/acf/KhoaHocACF.php`).
- Không migration script; không sửa CF7; không sửa `khoa-hoc.js`/`ws.js`.
- `window.khSchedule` / `window.wsSchedule` giữ nguyên shape: `{ availDates: string[], isPast: bool }`.
- Không có test suite WordPress. Logic thuần được test bằng PHP CLI (`php tests/...`). Phần phụ thuộc WordPress kiểm thử thủ công.

---

### Task 1: Helper thuần sinh ngày & tóm tắt (CLI-testable core)

Tạo phần lõi thuần PHP (không gọi hàm WordPress) để test được bằng `php` CLI.

**Files:**
- Create: `inc/functions/ScheduleFunction.php`
- Test: `tests/schedule-test.php`

**Interfaces:**
- Produces:
  - `mona_generate_recurring_dates(string $start, string $end, array $weekdays, int $maxDays = 366): array` — trả mảng `Y-m-d` tăng dần, unique; rỗng nếu input không hợp lệ (`end < start`, `weekdays` rỗng, khoảng > `$maxDays`).
  - `mona_normalize_date_list(array $parts): array` — nhận mảng chuỗi ngày lẫn lộn (`Y-m-d`, `d-m-Y`, "12 Tháng 8, 2026", strtotime), trả mảng `Y-m-d` sort + unique.
  - `mona_recurring_summary(array $weekdays, string $start, string $end): string` — VD `"Thứ 3, 5, 7 · 12/08 – 12/09"`.

- [ ] **Step 1: Viết test thất bại**

Tạo `tests/schedule-test.php`:

```php
<?php
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
    mona_generate_recurring_dates('2026-08-11', '2026-08-20', ['2','4','6']),
    ['2026-08-11','2026-08-13','2026-08-15','2026-08-18','2026-08-20']
);
// weekdays rỗng -> []
check('empty weekdays', mona_generate_recurring_dates('2026-08-11','2026-08-20', []), []);
// end < start -> []
check('end before start', mona_generate_recurring_dates('2026-08-20','2026-08-11', ['2']), []);
// vượt maxDays -> []
check('over maxDays', mona_generate_recurring_dates('2026-01-01','2030-01-01', ['1']), []);

// Chuẩn hoá hỗn hợp + sort + unique
check('normalize mixed',
    mona_normalize_date_list(['13-08-2026', '2026-08-12', '12-08-2026']),
    ['2026-08-12','2026-08-13']
);

// Summary
check('summary recurring',
    mona_recurring_summary(['2','4','6'], '2026-08-11', '2026-08-20'),
    'Thứ 3, 5, 7 · 11/08 – 20/08'
);

echo $fail === 0 ? "\nALL PASS\n" : "\n$fail FAILED\n";
exit($fail === 0 ? 0 : 1);
```

- [ ] **Step 2: Chạy test để xác nhận FAIL**

Run: `php tests/schedule-test.php`
Expected: FAIL — `Failed opening required '.../ScheduleFunction.php'` (file chưa tồn tại).

- [ ] **Step 3: Viết implementation tối thiểu**

Tạo `inc/functions/ScheduleFunction.php`:

```php
<?php

defined('ABSPATH') || defined('MONA_SCHEDULE_TEST') || (PHP_SAPI === 'cli') || exit;

/**
 * Nhãn thứ rút gọn theo key date('N'). Mon=1..Sun=7.
 */
function mona_weekday_short_labels(): array {
    return ['1' => '2', '2' => '3', '3' => '4', '4' => '5', '5' => '6', '6' => '7', '7' => 'CN'];
}

/**
 * Sinh danh sách ngày Y-m-d cho lịch định kỳ theo thứ trong tuần.
 */
function mona_generate_recurring_dates(string $start, string $end, array $weekdays, int $maxDays = 366): array {
    $weekdays = array_map('strval', $weekdays);
    if (empty($weekdays)) return [];
    $ts = strtotime($start); $te = strtotime($end);
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
function mona_normalize_date_list(array $parts): array {
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
 * Câu tóm tắt cho lịch định kỳ. VD "Thứ 3, 5, 7 · 12/08 – 12/09".
 */
function mona_recurring_summary(array $weekdays, string $start, string $end): string {
    $labels = mona_weekday_short_labels();
    $weekdays = array_map('strval', $weekdays);
    sort($weekdays);
    $names = [];
    foreach ($weekdays as $w) {
        if (isset($labels[$w])) $names[] = $labels[$w];
    }
    $ts = strtotime($start); $te = strtotime($end);
    $range = ($ts && $te) ? date('d/m', $ts) . ' – ' . date('d/m', $te) : '';
    $wd = $names ? 'Thứ ' . implode(', ', $names) : '';
    return trim($wd . ($wd && $range ? ' · ' : '') . $range);
}
```

- [ ] **Step 4: Chạy test để xác nhận PASS**

Run: `php tests/schedule-test.php`
Expected: `ALL PASS`.

> Ghi chú: nhãn "CN" (Chủ Nhật) khi được chọn sẽ hiện ở cuối. Test trên chỉ dùng 2/4/6 nên summary = `"Thứ 3, 5, 7 · ..."`.

- [ ] **Step 5: Commit**

```bash
git add inc/functions/ScheduleFunction.php tests/schedule-test.php
git commit -m "feat: helper thuần sinh ngày & tóm tắt lịch định kỳ"
```

---

### Task 2: Wrapper phụ thuộc WordPress + đăng ký loadFile

Thêm `mona_expand_schedule()` và `mona_schedule_label()` (đọc ACF, tự nhận diện post_type) vào cùng file, và đăng ký file trong loader.

**Files:**
- Modify: `inc/functions/ScheduleFunction.php` (thêm cuối file)
- Modify: `configs/loadFile.php:20` (thêm dòng require sau `ACFFunction.php`)

**Interfaces:**
- Consumes: `mona_generate_recurring_dates()`, `mona_normalize_date_list()`, `mona_recurring_summary()` (Task 1).
- Produces:
  - `mona_expand_schedule(int $post_id): array` → `['type'=>'single'|'recurring'|'legacy', 'dates'=>string[], 'future'=>string[], 'summary'=>string, 'is_past'=>bool]`.
  - `mona_schedule_label(int $post_id): string` — chuỗi ngắn cho card ('' nếu không có lịch).

- [ ] **Step 1: Thêm wrapper vào `ScheduleFunction.php`**

Nối vào cuối file:

```php
/**
 * Map post_type -> tên field ACF.
 */
function mona_schedule_field_map(int $post_id): array {
    $pt = get_post_type($post_id);
    if ($pt === 'workshop') {
        return ['type'=>'ws_schedule_type','single'=>'ws_date_single','start'=>'ws_date_start','end'=>'ws_date_end','weekdays'=>'ws_weekdays','legacy'=>'ws_date'];
    }
    return ['type'=>'kh_schedule_type','single'=>'kh_date_single','start'=>'kh_date_start','end'=>'kh_date_end','weekdays'=>'kh_weekdays','legacy'=>'start_date'];
}

/**
 * Trả về lịch đã chuẩn hoá cho 1 post (khoa_hoc/workshop).
 */
function mona_expand_schedule(int $post_id): array {
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

    return [
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
function mona_schedule_label(int $post_id): string {
    $s = mona_expand_schedule($post_id);
    return $s['summary'];
}
```

- [ ] **Step 2: Đăng ký trong `configs/loadFile.php`**

Sau dòng `MONA_THEME_INC_PATH . '/functions/ACFFunction.php',` (dòng 20), thêm:

```php
    MONA_THEME_INC_PATH . '/functions/ScheduleFunction.php',
```

- [ ] **Step 3: Xác nhận core test vẫn PASS**

Run: `php tests/schedule-test.php`
Expected: `ALL PASS` (wrapper mới không gọi khi chạy CLI; các hàm cũ không đổi).

> Lưu ý: `mona_expand_schedule()` gọi `get_field()`/`get_post_type()` nên chỉ test thủ công trong WordPress (Task 4-6). Guard đầu file (`defined('ABSPATH') || ... || PHP_SAPI==='cli'`) cho phép require trong test CLI mà không fatal.

- [ ] **Step 4: Commit**

```bash
git add inc/functions/ScheduleFunction.php configs/loadFile.php
git commit -m "feat: mona_expand_schedule + mona_schedule_label, đăng ký loader"
```

---

### Task 3: ACF fields cho Khóa học

Thay field Text `start_date` bằng cụm field lịch trong `KhoaHocACF.php`.

**Files:**
- Modify: `inc/acf/KhoaHocACF.php:54-56` (thay field `Ngày khai giảng`)

**Interfaces:**
- Consumes: field names từ `mona_schedule_field_map()` — `kh_schedule_type`, `kh_date_single`, `kh_date_start`, `kh_date_end`, `kh_weekdays`.

- [ ] **Step 1: Thêm import cần thiết**

Đầu file, thêm vào khối `use` (sau các `use Extended\ACF\Fields\...` hiện có):

```php
use Extended\ACF\Fields\ButtonGroup;
use Extended\ACF\Fields\Checkbox;
use Extended\ACF\Fields\DatePicker;
use Extended\ACF\ConditionalLogic;
```

- [ ] **Step 2: Thay field `Ngày khai giảng`**

Thay nguyên block (dòng 54-56):

```php
            Text::make('Ngày khai giảng', 'start_date')
                ->helperText('Nhập 1 hoặc nhiều ngày, định dạng DD-MM-YYYY, cách nhau bằng dấu phẩy. Ví dụ: 12-08-2026, 13-08-2026. Form sẽ tự chọn ngày đầu tiên và chỉ cho phép chọn các ngày đã nhập. Bắt buộc nhập.')
                ->required(),
```

bằng:

```php
            ButtonGroup::make('Loại lịch', 'kh_schedule_type')
                ->helperText('Cố định: chỉ 1 ngày khai giảng. Định kỳ: lặp theo thứ trong một khoảng ngày.')
                ->choices([
                    'single'    => 'Cố định 1 ngày',
                    'recurring' => 'Định kỳ',
                ])
                ->default('single'),

            DatePicker::make('Ngày khai giảng', 'kh_date_single')
                ->helperText('Ngày khai giảng cố định.')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('kh_schedule_type', '==', 'single')]),

            DatePicker::make('Ngày bắt đầu', 'kh_date_start')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('kh_schedule_type', '==', 'recurring')]),

            DatePicker::make('Ngày kết thúc', 'kh_date_end')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('kh_schedule_type', '==', 'recurring')]),

            Checkbox::make('Các thứ diễn ra', 'kh_weekdays')
                ->helperText('Tick các thứ khóa học diễn ra hàng tuần.')
                ->choices([
                    '1' => 'Thứ 2', '2' => 'Thứ 3', '3' => 'Thứ 4', '4' => 'Thứ 5',
                    '5' => 'Thứ 6', '6' => 'Thứ 7', '7' => 'Chủ Nhật',
                ])
                ->conditionalLogic([ConditionalLogic::where('kh_schedule_type', '==', 'recurring')]),
```

- [ ] **Step 3: Kiểm tra lint PHP**

Run: `php -l inc/acf/KhoaHocACF.php`
Expected: `No syntax errors detected`.

- [ ] **Step 4: Kiểm thử thủ công (WordPress local)**

Vào admin sửa 1 khóa học: chọn "Định kỳ" → hiện Ngày bắt đầu/kết thúc + checkbox thứ; chọn "Cố định 1 ngày" → chỉ hiện 1 date picker. Lưu không lỗi.

- [ ] **Step 5: Commit**

```bash
git add inc/acf/KhoaHocACF.php
git commit -m "feat: ACF field lịch định kỳ cho khóa học"
```

---

### Task 4: ACF fields cho Workshop

Tương tự Task 3 nhưng prefix `ws_`, trong `WorkshopACF.php`.

**Files:**
- Modify: `inc/acf/WorkshopACF.php:51-53` (thay field `Ngày tổ chức`)

**Interfaces:**
- Consumes: `ws_schedule_type`, `ws_date_single`, `ws_date_start`, `ws_date_end`, `ws_weekdays`.

- [ ] **Step 1: Thêm import**

Đầu file, thêm vào khối `use`:

```php
use Extended\ACF\Fields\ButtonGroup;
use Extended\ACF\Fields\Checkbox;
use Extended\ACF\Fields\DatePicker;
use Extended\ACF\ConditionalLogic;
```

- [ ] **Step 2: Thay field `Ngày tổ chức`**

Thay block (dòng 51-53):

```php
            Text::make('Ngày tổ chức', 'ws_date')
                ->helperText('Nhập 1 hoặc nhiều ngày, định dạng DD-MM-YYYY, cách nhau bằng dấu phẩy. Ví dụ: 12-08-2026, 13-08-2026. Form sẽ tự chọn ngày đầu tiên và chỉ cho phép chọn các ngày đã nhập. Bắt buộc nhập.')
                ->required(),
```

bằng:

```php
            ButtonGroup::make('Loại lịch', 'ws_schedule_type')
                ->helperText('Cố định: chỉ 1 ngày tổ chức. Định kỳ: lặp theo thứ trong một khoảng ngày.')
                ->choices([
                    'single'    => 'Cố định 1 ngày',
                    'recurring' => 'Định kỳ',
                ])
                ->default('single'),

            DatePicker::make('Ngày tổ chức', 'ws_date_single')
                ->helperText('Ngày tổ chức cố định.')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('ws_schedule_type', '==', 'single')]),

            DatePicker::make('Ngày bắt đầu', 'ws_date_start')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('ws_schedule_type', '==', 'recurring')]),

            DatePicker::make('Ngày kết thúc', 'ws_date_end')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('ws_schedule_type', '==', 'recurring')]),

            Checkbox::make('Các thứ diễn ra', 'ws_weekdays')
                ->helperText('Tick các thứ workshop diễn ra hàng tuần.')
                ->choices([
                    '1' => 'Thứ 2', '2' => 'Thứ 3', '3' => 'Thứ 4', '4' => 'Thứ 5',
                    '5' => 'Thứ 6', '6' => 'Thứ 7', '7' => 'Chủ Nhật',
                ])
                ->conditionalLogic([ConditionalLogic::where('ws_schedule_type', '==', 'recurring')]),
```

- [ ] **Step 3: Lint**

Run: `php -l inc/acf/WorkshopACF.php`
Expected: `No syntax errors detected`.

- [ ] **Step 4: Kiểm thử thủ công**

Admin sửa 1 workshop: toggle "Định kỳ"/"Cố định" hiện đúng field, lưu không lỗi.

- [ ] **Step 5: Commit**

```bash
git add inc/acf/WorkshopACF.php
git commit -m "feat: ACF field lịch định kỳ cho workshop"
```

---

### Task 5: Trang chi tiết Khóa học — dùng helper + block "Lịch diễn ra"

**Files:**
- Modify: `partials/templates/single/single-khoa_hoc.php:8` (biến `$start_date`), `:542-566` (block availDates), và block hiển thị meta.

**Interfaces:**
- Consumes: `mona_expand_schedule()`, `mona_schedule_label()`.

- [ ] **Step 1: Nạp lịch ở đầu template**

Thay dòng 8:

```php
$start_date  = get_field('start_date', $post_id) ?: '20 THÁNG 7, 2025';
```

bằng:

```php
$kh_sched    = mona_expand_schedule($post_id);
$start_date  = $kh_sched['summary'] ?: '20 THÁNG 7, 2025';
```

- [ ] **Step 2: Thay block sinh availDates (dòng 542-566)**

Thay nguyên khối PHP tính `$_kh_dates` / `$_kh_future` + `<script>window.khSchedule...` (dòng 541-567) bằng:

```php
<?php $_kh_future = $kh_sched['future']; ?>
<script>
    window.khSchedule = <?php echo json_encode([
        'availDates' => $_kh_future,
        'isPast'     => $kh_sched['is_past'],
    ]); ?>;
</script>
```

- [ ] **Step 3: Thêm block "Lịch diễn ra" cho định kỳ**

Ngay TRƯỚC dòng `<script>` ở Step 2 (sau `</div>` đóng section, dòng ~529), chèn:

```php
<?php if ($kh_sched['type'] === 'recurring' && count($kh_sched['dates']) > 1) : ?>
    <div class="kh-schedule-list mt-4">
        <p class="font-medium text-[#1b1c19]"><?php echo esc_html($kh_sched['summary']); ?></p>
        <ul class="mt-2 flex flex-wrap gap-2">
            <?php foreach ($kh_sched['dates'] as $_d) : ?>
                <li class="px-2 py-1 rounded bg-[#f4efe3] text-[13px] text-[#1b1c19]">
                    <?php echo esc_html(date('d/m/Y', strtotime($_d))); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
```

- [ ] **Step 4: Lint**

Run: `php -l partials/templates/single/single-khoa_hoc.php`
Expected: `No syntax errors detected`.

- [ ] **Step 5: Kiểm thử thủ công**

- Khóa học `single`: dòng "KHAI GIẢNG" hiện `d/m/Y`; Flatpickr chỉ cho chọn ngày đó; không có block "Lịch diễn ra".
- Khóa học `recurring` T3/T5/T7: "KHAI GIẢNG" = summary; block "Lịch diễn ra" liệt kê đúng các ngày; Flatpickr enable đúng tập ngày.
- Post cũ (còn `start_date` text): hiển thị & đặt lịch như trước (nhánh legacy).

- [ ] **Step 6: Commit**

```bash
git add partials/templates/single/single-khoa_hoc.php
git commit -m "feat: single khóa học dùng helper lịch + block Lịch diễn ra"
```

---

### Task 6: Trang chi tiết Workshop — dùng helper + block "Lịch diễn ra"

**Files:**
- Modify: `partials/templates/single/single-workshop.php:7` (biến `$ws_date`), `:552-577` (block availDates), block meta.

**Interfaces:**
- Consumes: `mona_expand_schedule()`.

- [ ] **Step 1: Nạp lịch đầu template**

Thay dòng 7:

```php
$ws_date       = get_field('ws_date',       $post_id) ?: '26 THÁNG 7, 2025';
```

bằng:

```php
$ws_sched      = mona_expand_schedule($post_id);
$ws_date       = $ws_sched['summary'] ?: '26 THÁNG 7, 2025';
```

- [ ] **Step 2: Thay block availDates**

Thay khối PHP tính `$_ws_dates`/`$_ws_future` + `<script>window.wsSchedule...` (dòng ~551-577) bằng:

```php
<?php $_ws_future = $ws_sched['future']; ?>
<script>
    window.wsSchedule = <?php echo json_encode([
        'availDates' => $_ws_future,
        'isPast'     => $ws_sched['is_past'],
    ]); ?>;
</script>
```

> Nếu shape script gốc có thêm key khác ngoài `availDates`/`isPast`, giữ nguyên các key đó. Mở file xác nhận trước khi thay (grep `window.wsSchedule` cho vùng dòng chính xác).

- [ ] **Step 3: Thêm block "Lịch diễn ra"**

Ngay trước `<script>` ở Step 2, chèn:

```php
<?php if ($ws_sched['type'] === 'recurring' && count($ws_sched['dates']) > 1) : ?>
    <div class="ws-schedule-list mt-4">
        <p class="font-medium text-[#1b1c19]"><?php echo esc_html($ws_sched['summary']); ?></p>
        <ul class="mt-2 flex flex-wrap gap-2">
            <?php foreach ($ws_sched['dates'] as $_d) : ?>
                <li class="px-2 py-1 rounded bg-[#f4efe3] text-[13px] text-[#1b1c19]">
                    <?php echo esc_html(date('d/m/Y', strtotime($_d))); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
```

- [ ] **Step 4: Lint**

Run: `php -l partials/templates/single/single-workshop.php`
Expected: `No syntax errors detected`.

- [ ] **Step 5: Kiểm thử thủ công**

Lặp 3 kịch bản (single / recurring / legacy) cho workshop như Task 5.

- [ ] **Step 6: Commit**

```bash
git add partials/templates/single/single-workshop.php
git commit -m "feat: single workshop dùng helper lịch + block Lịch diễn ra"
```

---

### Task 7: Card & search — dùng `mona_schedule_label()`

Thay 14 điểm đọc `get_field('start_date'/'ws_date')` cho card bằng `mona_schedule_label()`. `$post->ID` hoặc `$post_id` tùy site (mở file để lấy đúng biến id).

**Files:**
- Modify:
  - `partials/sections/home/section-courses-workshop.php:31,40,66,76`
  - `partials/sections/home/section-workshop.php:88`
  - `partials/sections/home/section-featured.php:50,69`
  - `partials/sections/home/section-courses.php:99`
  - `partials/sections/workshop/section-list.php:129`
  - `partials/sections/khoa-hoc-workshop/section-khoa-hoc.php:90`
  - `partials/sections/khoa-hoc-workshop/section-workshop.php:81`
  - `partials/sections/khoa-hoc/section-list.php:137`
  - `page-template/page-search-results.php:141,163`

- [ ] **Step 1: Thay các dòng hiển thị ngày (dùng `$post->ID`)**

Trong các file dùng `$post->ID`:

`section-courses-workshop.php` dòng 31:
```php
        'start_date' => get_field('start_date',      $post->ID) ?: 'Sắp khai giảng',
```
→
```php
        'start_date' => mona_schedule_label($post->ID) ?: 'Sắp khai giảng',
```

dòng 66:
```php
        'date'       => get_field('ws_date',          $post->ID) ?: 'Sắp diễn ra',
```
→
```php
        'date'       => mona_schedule_label($post->ID) ?: 'Sắp diễn ra',
```

`section-workshop.php:88`, `section-featured.php:50,69`, `section-courses.php:99`, `page-search-results.php:141,163`: cùng quy tắc — thay `get_field('start_date'|'ws_date', $post->ID)` bằng `mona_schedule_label($post->ID)`, giữ nguyên phần `?: '...'` và key mảng.

> `section-featured.php:50,69` không có `?: '...'`. Giữ nguyên: `'start_date' => mona_schedule_label($post->ID),` và `'date' => mona_schedule_label($post->ID),`.

- [ ] **Step 2: Thay các dòng dùng `$post_id`**

`section-list.php` (workshop):129, `section-khoa-hoc.php`:90, `section-workshop.php` (khoa-hoc-workshop):81, `section-list.php` (khoa-hoc):137 — dùng `$post_id`:

VD `section-list.php:129`:
```php
            'date'       => get_field('ws_date',          $post_id) ?: 'Sắp diễn ra',
```
→
```php
            'date'       => mona_schedule_label($post_id) ?: 'Sắp diễn ra',
```

Áp cùng quy tắc cho 3 dòng còn lại (khoa_hoc dùng `?: 'Sắp khai giảng'`).

- [ ] **Step 3: Sửa `_date_sort` dùng ngày thật**

`section-courses-workshop.php:40`:
```php
        '_date_sort' => get_field('start_date', $post->ID) ?: $post->post_date,
```
→
```php
        '_date_sort' => (mona_expand_schedule($post->ID)['dates'][0] ?? null) ?: $post->post_date,
```

`section-courses-workshop.php:76`:
```php
        '_date_sort' => get_field('ws_date', $post->ID) ?: $post->post_date,
```
→
```php
        '_date_sort' => (mona_expand_schedule($post->ID)['dates'][0] ?? null) ?: $post->post_date,
```

- [ ] **Step 4: Lint tất cả file đã sửa**

Run:
```bash
for f in partials/sections/home/section-courses-workshop.php partials/sections/home/section-workshop.php partials/sections/home/section-featured.php partials/sections/home/section-courses.php partials/sections/workshop/section-list.php partials/sections/khoa-hoc-workshop/section-khoa-hoc.php partials/sections/khoa-hoc-workshop/section-workshop.php partials/sections/khoa-hoc/section-list.php page-template/page-search-results.php; do php -l "$f"; done
```
Expected: mỗi file `No syntax errors detected`.

- [ ] **Step 5: Kiểm thử thủ công**

Trang chủ, listing khóa học/workshop, trang search: card hiển thị summary (định kỳ) hoặc ngày (cố định); post cũ vẫn hiển thị chuỗi legacy; thứ tự sort theo ngày đúng.

- [ ] **Step 6: Commit**

```bash
git add partials/sections page-template/page-search-results.php
git commit -m "feat: card & search dùng mona_schedule_label cho lịch"
```

---

## Self-Review

**Spec coverage:**
- ACF field 2 dạng (single/recurring) → Task 3, 4. ✓
- Helper trung tâm + fallback legacy → Task 1, 2. ✓
- Hiển thị summary + danh sách ngày ở single → Task 5, 6. ✓
- Card 14 site + `_date_sort` → Task 7. ✓
- availDates từ helper, JS không đổi → Task 5, 6 (không đụng `khoa-hoc.js`/`ws.js`). ✓
- Đăng ký loadFile → Task 2. ✓
- Biên (end<start, weekdays rỗng, >366 ngày, quá khứ) → Task 1 test + helper guard. ✓

**Placeholder scan:** không còn TBD/TODO; mọi step có code cụ thể. ✓

**Type consistency:** tên hàm `mona_generate_recurring_dates`, `mona_normalize_date_list`, `mona_recurring_summary`, `mona_expand_schedule`, `mona_schedule_label`, `mona_schedule_field_map` dùng nhất quán giữa các task. Field names khớp `mona_schedule_field_map()`. Return shape `['type','dates','future','summary','is_past']` dùng nhất quán ở Task 5-7. ✓

**Lưu ý khi thực thi:** số dòng ở single-workshop (Task 6) là ước lượng — mở file xác nhận vùng `window.wsSchedule` trước khi thay.
