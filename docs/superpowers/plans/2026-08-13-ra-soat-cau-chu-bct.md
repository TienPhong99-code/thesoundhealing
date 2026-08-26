# Rà soát câu chữ trước khi nộp BCT — việc còn lại phải làm tay

Ngày: 2026-08-13. Nguồn: `cau-chu-can-sua.md` (audit 12/12 trang, ~120 câu VN + 177 câu EN).

## Đã xong bằng code (deploy là có hiệu lực ngay)

| Hạng mục | Nơi sửa |
|---|---|
| Text mặc định của các section (hiện khi ACF để trống) | `partials/sections/**`, `partials/components/search-booking.php` |
| Placeholder / default / helper text của ACF | `inc/acf/SearchBookingACF.php`, `DichVuPageACF.php`, `AboutACF.php`, `KhoaHocACF.php` |
| Disclaimer checkout + email hoàn thành đơn | `woocommerce/checkout/form-checkout.php`, `woocommerce/emails/customer-completed-order.php` |
| Bản dịch EN của các chuỗi trên | `languages/monamedia-en_US.po` + `.mo` |
| Nội dung demo trong seeders | `inc/seeders/*.php` (120 dòng — code chết, chỉ sửa cho nhất quán) |

> **Đã gỡ tool (2026-08-14).** `inc/tools/` (WordingMigrationTool, WpmlLinkTool, wording-map)
> đã xoá khỏi repo lẫn server sau khi chạy xong; `configs/loadFile.php` không còn đăng ký.
> Phần "Bước 1" bên dưới giữ lại để ghi nhận cách đã làm — muốn chạy lại thì khôi phục 3 file đó
> từ lịch sử git.
>
> Bản sao lưu 6 lần chạy vẫn nằm trong option `mona_wording_migration_backup` (autoload = no).
> Không xoá vì đó là dữ liệu gốc trước khi thay chữ; muốn hoàn tác phải khôi phục tool trước.

## Bước 1 — Chạy tool thay thế nội dung trong database

**Phần lớn câu chữ vi phạm nằm trong DB, không nằm trong theme.** Sau khi deploy code:

1. Vào **wp-admin → Công cụ → Rà soát câu chữ BCT**
2. Trang mặc định là *quét thử* — đọc bảng đối chiếu "Hiện tại → Sẽ đổi thành"
3. Bấm **Áp dụng thay đổi**
4. Sai thì bấm **Hoàn tác** (tool tự lưu bản sao lưu của lần chạy gần nhất)

Tool quét: `post_title` / `post_content` / `post_excerpt`, ACF postmeta, Theme Settings
(`options_*`), và WPML String Translation. Bảng thay thế nằm ở `inc/tools/wording-map.php` —
sửa file này rồi quét lại nếu muốn thêm/bớt cụm từ.

Tool bỏ qua giá trị serialize và meta key bắt đầu bằng `_` để không làm hỏng dữ liệu ACF.

> Sau khi nộp hồ sơ xong, gỡ dòng `tools/WordingMigrationTool.php` khỏi `configs/loadFile.php`.

## Bước 1b — Tạo trang "Chính sách & Điều khoản" (VN + EN)

Code đã deploy sẵn, chỉ còn tạo Page trong wp-admin:

| Thành phần | File |
|---|---|
| Nội dung VN + EN | `inc/data/chinh-sach.php` |
| Giao diện (hero + mục lục neo + 5 mục) | `partials/sections/chinh-sach/section-content.php` |
| Page template | `page-template/page-chinh-sach.php` |

1. **Trang VN:** Trang → Thêm mới, tiêu đề "Chính sách & Điều khoản", slug `chinh-sach`,
   Page Attributes → Template = **Chính sách & Điều khoản**. Nội dung để TRỐNG (template tự render).
2. **Trang EN:** tạo bản dịch WPML của trang trên, tiêu đề "Policies & Terms",
   slug `policies`, gán cùng template.
3. Thêm link tới trang này ở **footer** (bắt buộc với hồ sơ BCT).

Nội dung nằm trong code nên không sửa được từ wp-admin — muốn đổi câu chữ thì sửa
`inc/data/chinh-sach.php` rồi deploy lại. Đổi lại thế cho chắc: 2 ngôn ngữ luôn đầy đủ,
không thể bị editor xoá nhầm, và deploy là có ngay.

Link neo dùng chung cho cả 2 ngôn ngữ: `#bao-mat`, `#dat-thanh-toan`,
`#cung-ung-dich-vu`, `#hoan-huy-doi-lich`, `#dieu-khoan-su-dung`.

## Trạng thái ngày 2026-08-14 (đã chạy trên live)

Đã đăng nhập wp-admin, tạo trang và chạy tool. Kết quả quét vi phạm trên live:

| Trang | Trước | Sau |
|---|---|---|
| `/` | 11 | **0** |
| `/dich-vu/` | 7 | **0** |
| `/khoa-hoc-workshop/` | 6 | **0** |
| `/sound-healing-ho-chi-minh-city/` | 26 | **0** |
| `/en/` | 6 | **1** (còn "Sound Healing Therapy") |
| `/en/sound-healing-ho-chi-minh-city/` | nhiều | **0** |

Tổng cộng 6 lần chạy tool, đều có bản sao lưu riêng (Hoàn tác gỡ dần từng lần).

**4 lỗ hổng của tool đã phát hiện & sửa trong quá trình chạy:**
1. Bỏ qua mọi meta key có tiền tố `_` → sót toàn bộ **meta SEO Yoast** (`_yoast_wpseo_title/metadesc`).
2. Chỉ quét `options_%` → sót **tagline site** (`blogdescription`) vốn xuất hiện ở JSON-LD mọi trang.
3. Không quét bảng **`wp_yoast_indexable`** — Yoast cache sẵn title/description và render trang từ đó,
   nên sửa postmeta bằng `$wpdb` (không qua `save_post`) không có tác dụng. Đây là lý do landing page
   vẫn hiện tiêu đề cũ dù postmeta đã đúng.
4. Không quét **`icl_strings`** (chuỗi nguồn WPML), chỉ quét bảng bản dịch.

Ngoài ra scan ban đầu chạy 60s+ và bị LiteSpeed cắt → đã thêm bộ lọc SQL sinh từ chính bảng thay thế
(153 cụm → 63 từ gốc, phủ 100%), giảm còn ~5s.

### 🔴 Còn 1 việc phải làm tay

Trên `/en/`, ô Search Booking vẫn hiện **"Sound Healing Therapy"**. Đã kiểm tra: giá trị trong DB
**đã đúng** ở cả 3 nơi (`options_en_sb_desc_sound_healing` #21939, `icl_strings` #50668,
`icl_string_translations` #70253 — tất cả đều là "Sound relaxation experience"). Đây là **cache của
WPML**, không phải dữ liệu sai.

→ Vào **WPML → Support → Troubleshooting**, bấm nút **"Xóa bộ nhớ đệm trong WPML"**, rồi tải lại `/en/`.

## Bước 2 — Việc phải làm tay (tool KHÔNG tự làm được)

### 2a. Lỗi kỹ thuật (mục C của audit) — ưu tiên cao nhất

1. 🐞 Trang **Nighttime Sound Bath** (`/dich-vu/nighttime-sound-bath-group/`) đang hiển thị
   nội dung của **Midday Sound Bath**. Sửa lại đúng nội dung buổi tối.
2. 🐞 URL **`/dich-vu/tam-am-ngu-ngon-rieng-tu/`** đang hiển thị nội dung
   **Sound & Past Lives Journey**. Sửa lại đúng routing.
3. Đổi slug `chua-lanh-usui-reiki-rieng-tu` → `trai-nghiem-usui-reiki-rieng-tu` **+ redirect 301**
   (URL chứa "chua-lanh" đang được index công khai). Slug EN
   `sound-past-lives-journey`, `soul-mirror-tarot-reading` cũng nên đổi đồng bộ.
4. Rà **meta title / meta description** từng trang (Yoast/RankMath) — tool có quét postmeta
   nên phần lớn sẽ tự đổi, nhưng phải kiểm tra lại bằng mắt vì đây là thứ thanh tra soi trước.
5. **BCT yêu cầu nội dung tiếng Việt đầy đủ** — đảm bảo không trang chủ lực nào chỉ có bản EN.

### 2b. Câu cần viết lại hẳn (tool chỉ thay từ, không viết lại được ý)

Sửa tay trong wp-admin sau khi chạy tool:

| Trang | Câu hiện tại | Nên thành |
|---|---|---|
| Sound & Past Lives Journey | "Người Reader dùng phương pháp *truy cập* vào tiềm thức, thâm nhập vào Akashic Records – Tàng thư vũ trụ… *đọc* ra câu chuyện của hành trình tiền kiếp." | "Người hướng dẫn dẫn dắt bạn vào trạng thái thư giãn sâu và gợi mở để bạn tự quan sát những hình ảnh, cảm xúc và câu chuyện tưởng tượng của riêng mình." |
| Sound & Past Lives Journey | "Kenzo có hơn 7 năm… Tarot Reading… Thôi miên lượng tử và Akashic Records." | "Kenzo có hơn 7 năm thực hành và hướng dẫn các hoạt động thiền, âm thanh thư giãn và trải nghiệm khám phá phát triển bản thân." |
| Usui Reiki riêng tư | "Phiên trị liệu năng lượng chuyên sâu 1-1 giúp **giải quyết** các tắc nghẽn cảm xúc và **thể chất** cụ thể." | "Phiên trải nghiệm âm thanh và năng lượng thư giãn 1-1, giúp bạn thư giãn sâu và giải tỏa căng thẳng cảm xúc." |
| Usui Reiki riêng tư | Tiêu đề "Kích hoạt trạng thái phục hồi" + "cơ thể bạn chuyển sang chế độ nghỉ ngơi–tái tạo" | Tiêu đề "Thư giãn và nghỉ ngơi sâu" + "bạn cảm thấy thư thái và dễ chịu hơn" |
| Khóa Hòa âm 7 chuông pha lê | "xây dựng trực giác trong việc dẫn dắt một buổi trị liệu" | "…dẫn dắt một buổi trải nghiệm âm thanh thư giãn" |
| EN – Sound Bath (cả 4 trang) | "Sound Bath helps improve sleep quality… difficulty falling asleep, frequent waking during the night" | Bỏ hẳn claim mất ngủ → "a calming experience many guests enjoy as a relaxing wind-down" |
| EN – Course | "Become a Sound Healer" + "Certificate of Completion" | "Become a Sound Relaxation Facilitator" + "Certificate of Participation (non-accredited)" — **không hứa đào tạo thành nghề, không cấp thứ nghe như chứng chỉ hành nghề** |

### 2c. Claim số liệu chưa kiểm chứng (Điều 8 Luật Quảng cáo)

Có bằng chứng thì giữ, không thì bỏ số tuyệt đối:
"chứng chỉ quốc tế", "500 khách hàng", "100 chủ doanh nghiệp", "hơn 600 khách hàng".

### 2d. Giữ lại disclaimer miễn trừ y tế

Bản EN đã có câu tốt: *"While not a substitute for medical or psychological treatment…"*.
Giữ tinh thần đó; bản VN đã thêm câu tương đương ở checkout
(`woocommerce/checkout/form-checkout.php`). Nên đưa disclaimer này lên cả trang dịch vụ.

## Bối cảnh pháp lý

Công ty chỉ có mã ngành **giáo dục** (8552 / 8559 / 8569), **không có mã y tế 86xx**.
Mọi câu ngụ ý khám/chữa/điều trị bệnh → rủi ro **NĐ 38/2021**. Nguyên tắc xuyên suốt:
đóng khung lại thành **GIÁO DỤC / TRẢI NGHIỆM / THƯ GIÃN**.
