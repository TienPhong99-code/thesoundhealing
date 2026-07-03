# Thiết kế: Lịch định kỳ cho Khóa học & Workshop

**Ngày:** 2026-07-03
**Phạm vi:** `khoa_hoc` + `workshop`

## Bối cảnh & vấn đề

Hiện tại lịch khai giảng/tổ chức được lưu trong 1 field Text nhập tay:

- `khoa_hoc` → `start_date`
- `workshop` → `ws_date`

Admin gõ chuỗi ngày cách nhau bằng dấu phẩy (`12-08-2026, 13-08-2026`). Frontend
(`single-khoa_hoc.php:543-559`) parse chuỗi này → chuẩn hoá `Y-m-d` → lọc ngày tương lai →
đổ vào Flatpickr (chỉ cho chọn đúng các ngày đã nhập) + sinh 3 pill chọn nhanh.

Hai loại lịch thực tế:

1. **Cố định 1 ngày** — chỉ diễn ra 1 ngày duy nhất.
2. **Định kỳ** — lặp lại theo thứ trong tuần trong một khoảng ngày (VD: T3/T5/T7 từ 12/08 đến 12/09).

Với loại 2, nhập tay từng ngày rất cực cho admin và hiển thị ra ngoài chưa rõ ràng.

## Quyết định thiết kế (đã chốt)

- Áp dụng cho **cả** `khoa_hoc` và `workshop`.
- Kiểu lặp cần hỗ trợ: **theo thứ trong tuần + khoảng ngày** (không cần chọn tay bổ sung, không exclude).
- Hiển thị ra trang chi tiết: **câu tóm tắt + danh sách ngày cụ thể**.
- Form đặt lịch: **khách vẫn chọn 1 ngày** trong các ngày khả dụng (giữ nguyên logic Flatpickr,
  chỉ đổi nguồn `availDates`).
- **Không migration script** — helper tự fallback đọc chuỗi text cũ cho post chưa cập nhật.

## Kiến trúc

### 1. Cấu trúc field ACF

Thay 1 ô Text (`start_date` / `ws_date`) bằng cụm field có conditional logic. Prefix `kh_` cho
khóa học, `ws_` cho workshop. Đặt tại vị trí field ngày hiện tại trong `KhoaHocACF.php` /
`WorkshopACF.php`.

| Field name (khóa học / workshop) | Loại ACF | Điều kiện hiện |
|---|---|---|
| `kh_schedule_type` / `ws_schedule_type` | ButtonGroup — `single` = "Cố định 1 ngày", `recurring` = "Định kỳ" | luôn hiện, default `single` |
| `kh_date_single` / `ws_date_single` | DatePicker, `format('Y-m-d')`, `displayFormat('d/m/Y')` | `schedule_type == single` |
| `kh_date_start` / `ws_date_start` | DatePicker (`Y-m-d`) | `schedule_type == recurring` |
| `kh_date_end` / `ws_date_end` | DatePicker (`Y-m-d`) | `schedule_type == recurring` |
| `kh_weekdays` / `ws_weekdays` | Checkbox, choices key `1`–`7` (T2…CN), khớp `date('N')` | `schedule_type == recurring` |

Conditional logic dùng `->conditionalLogic([ConditionalLogic::where('kh_schedule_type', '==', 'recurring')])`.

Weekday choices (key = ISO-8601 `date('N')`, Mon=1 … Sun=7):

```php
['1' => 'Thứ 2', '2' => 'Thứ 3', '3' => 'Thứ 4', '4' => 'Thứ 5',
 '5' => 'Thứ 6', '6' => 'Thứ 7', '7' => 'Chủ Nhật']
```

**Field text cũ** (`start_date` / `ws_date`): gỡ khỏi định nghĩa ACF (không còn hiện trong admin),
nhưng meta cũ vẫn nằm trong DB nên `get_field('start_date')` vẫn trả giá trị đã lưu → helper fallback đọc được.

### 2. Helper trung tâm — `inc/functions/schedule.php`

File mới, đăng ký trong `configs/loadFile.php`.

#### `mona_expand_schedule( int $post_id ): array`

Tự nhận diện post_type để chọn bộ field (`kh_` hay `ws_`). Trả về:

```php
[
    'type'    => 'single' | 'recurring' | 'legacy',
    'dates'   => ['2026-08-12', ...],   // tất cả ngày, Y-m-d, sort tăng, unique
    'future'  => ['2026-08-12', ...],   // các ngày >= hôm nay (cho Flatpickr)
    'summary' => 'Thứ 3, 5, 7 · 12/08 – 12/09',  // chuỗi hiển thị chính
    'is_past' => bool,                  // true khi future rỗng
]
```

**Luồng xử lý:**

1. Đọc `schedule_type`.
2. `single` → `dates = [date_single]` (nếu có).
3. `recurring` → sinh ngày: lặp từ `date_start` đến `date_end`, giữ ngày có `date('N', $ts)`
   nằm trong mảng `weekdays` đã tick.
   - **Guard:** nếu khoảng > 366 ngày hoặc `date_end < date_start` hoặc `weekdays` rỗng → trả rỗng an toàn.
4. `schedule_type` rỗng (post cũ) → `type = 'legacy'`, parse chuỗi `start_date`/`ws_date` bằng
   đúng logic đang có ở `single-khoa_hoc.php:543-559` (hỗ trợ `Y-m-d`, `d-m-Y`, "12 Tháng 8, 2026", `strtotime`).
5. Chuẩn hoá: `sort`, `array_unique`. Tính `future` = lọc `>= date('Y-m-d')`.
6. Sinh `summary`:
   - `single`: `d/m/Y` của ngày đó.
   - `recurring`: `"Thứ 3, 5, 7 · 12/08 – 12/09"` — ghép nhãn thứ đã chọn (rút gọn) + khoảng ngày.
   - `legacy`: nếu là 1 ngày → `d/m/Y`; nhiều ngày → liệt kê / khoảng.

#### `mona_schedule_label( int $post_id ): string`

Trả chuỗi ngắn cho card: với `recurring` trả `summary`; với `single`/`legacy` trả ngày (hoặc summary).
Rỗng nếu không có lịch (để caller dùng `?: 'Sắp khai giảng'`).

### 3. Điểm hiển thị cần đổi

**Card (14 site)** — thay `get_field('start_date'/'ws_date') ?: '...'` bằng
`mona_schedule_label($post_id) ?: '...'`:

- `partials/sections/home/section-courses-workshop.php` (dòng 31, 40, 66, 76)
- `partials/sections/home/section-workshop.php` (88)
- `partials/sections/home/section-featured.php` (50, 69)
- `partials/sections/home/section-courses.php` (99)
- `partials/sections/workshop/section-list.php` (129)
- `partials/sections/khoa-hoc-workshop/section-khoa-hoc.php` (90)
- `partials/sections/khoa-hoc-workshop/section-workshop.php` (81)
- `partials/sections/khoa-hoc/section-list.php` (137)
- `page-template/page-search-results.php` (141, 163)

> Lưu ý `_date_sort` (section-courses-workshop.php:40,76) hiện dùng chuỗi text để sort — chuyển
> sang dùng ngày đầu tiên từ helper (`dates[0]`) để sort đúng theo ngày thật.

**Trang chi tiết** — `single-khoa_hoc.php`, `single-workshop.php`:

- Dòng "KHAI GIẢNG" / "Ngày tổ chức" (single-khoa_hoc.php:148, 437) → dùng `summary`.
- Thêm block **"Lịch diễn ra"**: câu tóm tắt (`summary`) + danh sách ngày cụ thể; chỉ render khi
  `type === 'recurring'` và có nhiều ngày.
- Block tính `$_kh_dates` / `availDates` (single-khoa_hoc.php:542-566) → thay bằng `future` từ helper.
  `window.khSchedule` / `window.wsSchedule` giữ nguyên shape (`availDates`, `isPast`).

**Booking JS** — `khoa-hoc.js`, `ws.js`: **không sửa**. Vẫn đọc `window.khSchedule.availDates`.

### 4. Không đụng chạm

- CF7 (`CF7Hook.php`, constant form ID): không đổi.
- Cấu trúc form đặt lịch, Flatpickr init: không đổi.
- Không migration script.

## Đơn vị & ranh giới

- **`schedule.php`** — thuần logic, không phụ thuộc template. Input: `post_id`. Output: mảng chuẩn.
  Test độc lập bằng cách gọi với post_id mẫu.
- **ACF field group** — chỉ khai báo field + conditional logic. Không chứa logic sinh ngày.
- **Template** — chỉ tiêu thụ output helper, không tự parse ngày.

Ranh giới rõ: sửa cách sinh ngày (helper) không phá template; sửa hiển thị (template) không đụng logic.

## Xử lý lỗi / biên

- `date_end < date_start`, `weekdays` rỗng, khoảng > 366 ngày → helper trả `dates`/`future` rỗng, không fatal.
- Post cũ chỉ có chuỗi text → nhánh `legacy`.
- Không có lịch nào → `summary` rỗng, card fallback `'Sắp khai giảng'`/`'Sắp diễn ra'`, single dùng placeholder hiện có.
- Tất cả ngày đã qua → `is_past = true`, Flatpickr fallback về hành vi "không có availDates" (đã có sẵn trong JS).

## Kiểm thử

Không có test suite PHP. Kiểm thử thủ công trên WordPress local:

1. Tạo khóa học `single` → card + single hiển thị đúng 1 ngày; Flatpickr chỉ cho chọn ngày đó.
2. Tạo khóa học `recurring` T3/T5/T7 trong 1 tháng → summary đúng, danh sách ngày đúng, Flatpickr enable đúng tập ngày.
3. Post cũ còn chuỗi text → vẫn hiển thị & đặt lịch như trước (nhánh legacy).
4. Lặp lại 3 kịch bản cho `workshop`.
5. Biên: end < start, không tick thứ nào, toàn ngày quá khứ.
