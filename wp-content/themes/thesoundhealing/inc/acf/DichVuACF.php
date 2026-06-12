<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'Chi tiết Dịch Vụ',
        'style'    => 'default',
        'position' => 'normal',
        'location' => [
            Location::where('post_type', '==', 'dich_vu'),
        ],
        'fields' => [

            // ─── TAB: THÔNG TIN ───────────────────────────────────────────
            Tab::make('Thông tin')->placement('left'),

            Image::make('Ảnh Banner (Hero)', 'dv_banner_image')
                ->helperText('Ảnh toàn màn hình cho trang chi tiết. Khác với ảnh đại diện dùng trên card. Kích thước đề xuất: 1920×1080px trở lên.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Select::make('Hình thức', 'dv_format')
                ->helperText('Phân loại trực tiếp hay trực tuyến.')
                ->choices([
                    'Onsite' => 'Trực tiếp (Onsite)',
                    'Online' => 'Trực tuyến (Online)',
                ])
                ->default('Onsite'),

            Text::make('Thời lượng', 'dv_duration')
                ->helperText('Ví dụ: 60 - 90 phút mỗi phiên'),

            Textarea::make('Địa điểm', 'dv_location')
                ->helperText('Nhập mỗi địa điểm trên một dòng. Ví dụ: 104/20 Mai Thị Lựu, Tân Định (Quận 1)')
                ->rows(3),

            Text::make('Ngày hoạt động', 'dv_available_days')
                ->helperText('Ngày trong tuần nhận đặt lịch. Ví dụ: Thứ 2 – Chủ nhật · Thứ 4, 6, 7'),

            Text::make('Số khách / phiên', 'dv_guests')
                ->helperText('Ví dụ: 1-2 khách / phiên · Tối đa 1 người'),

            Text::make('Chi nhánh', 'dv_branch')
                ->helperText('Hiển thị trên card. Ví dụ: Thảo Điền · Quận 1'),

            Number::make('Số chỗ còn lại', 'dv_spots')
                ->helperText('Nhập số chỗ còn trống. 0 = Hết chỗ (Fully Booked). Để trống = không hiển thị badge.')
                ->min(0),

            Textarea::make('Mô tả ngắn', 'dv_short_desc')
                ->helperText('Hiển thị trên card và hero trang chi tiết.')
                ->rows(3),

            Text::make('Giá dịch vụ', 'dv_price')
                ->helperText('Ví dụ: 800.000 VNĐ'),

            Select::make('Trạng thái', 'dv_status')
                ->choices([
                    'open'     => 'Hoạt động',
                    'limited'  => 'Sắp hết chỗ',
                    'closed'   => 'Tạm ngưng',
                    'upcoming' => 'Sắp mở',
                ])
                ->default('open'),

            // ─── TAB: TRẢI NGHIỆM ────────────────────────────────────────
            Tab::make('Trải nghiệm')->placement('left'),

            Text::make('Tiêu đề trải nghiệm', 'dv_exp_title')
                ->helperText('Ví dụ: Hành trình Trải nghiệm')
                ->default('Hành trình Trải nghiệm'),

            Textarea::make('Mô tả trải nghiệm', 'dv_exp_desc')
                ->helperText('Đoạn văn mô tả trải nghiệm dịch vụ.')
                ->rows(5),

            Image::make('Ảnh trải nghiệm chính', 'dv_exp_image_1')
                ->helperText('Ảnh dọc lớn bên trái. Kích thước đề xuất: 560×625px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh chi tiết (overlay)', 'dv_exp_image_2')
                ->helperText('Ảnh nhỏ đè góc dưới phải. Kích thước đề xuất: 240×240px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 5', 'dv_gallery_5')
                ->helperText('Ảnh thứ 5 trong bộ gallery trang chi tiết. Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            // ─── TAB: LỘ TRÌNH ───────────────────────────────────────────
            Tab::make('Lộ trình')->placement('left'),

            Text::make('Nhãn lộ trình', 'dv_roadmap_label')
                ->helperText('Ví dụ: LỘ TRÌNH TRỊ LIỆU')
                ->default('LỘ TRÌNH TRỊ LIỆU'),

            Text::make('Tiêu đề lộ trình', 'dv_roadmap_heading')
                ->helperText('Ví dụ: Hành trình chữa lành')
                ->default('Hành trình chữa lành'),

            Repeater::make('Các giai đoạn', 'dv_roadmap_items')
                ->helperText('Mỗi giai đoạn gồm tiêu đề, mô tả và tag.')
                ->layout('block')
                ->collapsed('dv_week_title')
                ->fields([
                    Text::make('Tiêu đề giai đoạn', 'dv_week_title')
                        ->helperText('Ví dụ: Bước 1 – Kết nối với hơi thở')
                        ->required(),

                    Textarea::make('Mô tả', 'dv_week_desc')
                        ->rows(3),

                    Text::make('Tags', 'dv_week_tags')
                        ->helperText('Các tag cách nhau bởi dấu phẩy. Ví dụ: Sound Bath, Thiền định'),
                ]),

            // ─── TAB: LỢI ÍCH ────────────────────────────────────────────
            Tab::make('Lợi ích')->placement('left'),

            Text::make('Tiêu đề', 'dv_benefits_heading')
                ->default('Lợi ích của liệu pháp'),

            Repeater::make('Danh sách lợi ích', 'dv_benefits_items')
                ->layout('block')
                ->collapsed('dv_benefit_title')
                ->fields([
                    Text::make('Nhãn (uppercase)', 'dv_benefit_title')->required()
                        ->helperText('Ví dụ: CẢI THIỆN GIẤC NGỦ'),
                    Textarea::make('Mô tả', 'dv_benefit_desc')->rows(2),
                ]),

            // ─── TAB: LỢI ÍCH NHẬN ĐƯỢC ─────────────────────────────────
            Tab::make('Lợi ích nhận được')->placement('left'),

            Repeater::make('Lợi ích sẽ nhận', 'dv_receive_items')
                ->helperText('Mỗi ô gồm tiêu đề và mô tả. Ví dụ: Thư giãn sâu, Ưu đãi khách hàng thân thiết...')
                ->layout('block')
                ->collapsed('dv_receive_title')
                ->fields([
                    Text::make('Tiêu đề', 'dv_receive_title')->required(),
                    Textarea::make('Mô tả', 'dv_receive_desc')->rows(2),
                ]),

            // ─── TAB: NGƯỜI HƯỚNG DẪN ────────────────────────────────────
            Tab::make('Người hướng dẫn')->placement('left'),

            Text::make('Nhãn', 'dv_instructor_label')
                ->default('NGƯỜI HƯỚNG DẪN'),

            Image::make('Ảnh', 'dv_instructor_image')
                ->helperText('Ảnh vuông 96×96px, bo tròn.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Text::make('Tên', 'dv_instructor_name')
                ->helperText('Ví dụ: Linh Tâm'),

            Textarea::make('Giới thiệu', 'dv_instructor_bio')
                ->rows(3),

            Text::make('Instagram', 'dv_instructor_instagram')
                ->helperText('URL Instagram đầy đủ. Ví dụ: https://instagram.com/linhtam'),

            Text::make('Facebook', 'dv_instructor_facebook')
                ->helperText('URL Facebook đầy đủ. Ví dụ: https://facebook.com/linhtam'),

            Text::make('WhatsApp', 'dv_instructor_whatsapp')
                ->helperText('Số điện thoại hoặc link WhatsApp. Ví dụ: https://wa.me/84901234567'),

            Text::make('Facebook Messenger', 'dv_instructor_messenger')
                ->helperText('Link Messenger. Ví dụ: https://m.me/linhtam'),

            Repeater::make('Danh sách người hướng dẫn (form đặt lịch)', 'dv_instructors')
                ->helperText('Các lựa chọn hiển thị trong dropdown "Người hướng dẫn" của form đặt lịch.')
                ->layout('table')
                ->collapsed('dv_instructor_name')
                ->fields([
                    Text::make('Tên', 'dv_instructor_name')->required()
                        ->helperText('Ví dụ: Linh Tâm'),
                ]),

            // ─── TAB: KHUNG GIỜ ──────────────────────────────────────────
            Tab::make('Khung giờ')->placement('left'),

            Repeater::make('Danh sách chi nhánh (form đặt lịch)', 'dv_branches')
                ->helperText('Các lựa chọn hiển thị trong dropdown "Chi nhánh" của form đặt lịch.')
                ->layout('table')
                ->fields([
                    Text::make('Chi nhánh', 'dv_branch_name')->required()
                        ->helperText('Ví dụ: Thảo Điền'),
                ]),

            Repeater::make('Khung giờ đặt lịch', 'dv_time_slots')
                ->helperText('Để trống sẽ dùng khung giờ mặc định: 09:00-10:30, 10:30-12:00, 14:00-15:30, 15:30-17:00, 17:00-18:30.')
                ->layout('table')
                ->fields([
                    Text::make('Khung giờ', 'dv_time_slot')->required()
                        ->helperText('Ví dụ: 09:00 - 10:30'),
                ]),

            // ─── TAB: CẢM NHẬN ───────────────────────────────────────────
            Tab::make('Cảm nhận')->placement('left'),

            Text::make('Tiêu đề', 'dv_feedbacks_heading')
                ->default('Khách hàng nói gì?'),

            Repeater::make('Hình ảnh khách hàng', 'dv_feedbacks')
                ->helperText('Thêm hình ảnh khách hàng trải nghiệm dịch vụ.')
                ->layout('table')
                ->fields([
                    Image::make('Hình ảnh', 'dv_fb_image')
                        ->required()
                        ->helperText('Ảnh vuông, ví dụ 400×400px.')
                        ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                        ->format('array'),
                ]),

            // ─── TAB: CTA ────────────────────────────────────────────────
            Tab::make('CTA')->placement('left'),

            Text::make('Tiêu đề CTA', 'dv_cta_title')
                ->default('Bắt đầu hành trình chữa lành'),

            Textarea::make('Mô tả CTA', 'dv_cta_desc')
                ->rows(2)
                ->default('Mỗi buổi trị liệu là một bước tiến trên hành trình khám phá và chữa lành bản thân. Đặt lịch ngay hôm nay.'),
        ],
    ], false);
}, 10);
