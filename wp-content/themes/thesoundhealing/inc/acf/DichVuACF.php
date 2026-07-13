<?php

use Extended\ACF\Fields\ButtonGroup;
use Extended\ACF\Fields\Checkbox;
use Extended\ACF\Fields\DatePicker;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'Dịch vụ — Nổi bật',
        'style'    => 'default',
        'position' => 'side',
        'location' => [
            Location::where('post_type', '==', 'dich_vu'),
        ],
        'fields' => [
            TrueFalse::make('Best Seller', 'dv_best_seller')
                ->helperText('Hiển thị badge ⭐ Best Seller trên card.')
                ->stylized(),
        ],
    ]);


    mona_regist_acf_field_group([
        'title'    => 'Chi tiết Dịch Vụ',
        'style'    => 'default',
        'position' => 'normal',
        'location' => [
            Location::where('post_type', '==', 'dich_vu'),
        ],
        'fields' => [

            // ─── TAB: TIÊU ĐỀ CÁC MỤC ────────────────────────────────────
            Tab::make('Tiêu đề các mục')->placement('left'),

            Text::make('Tiêu đề mục "Giới thiệu"', 'dv_sectitle_about')
                ->helperText('Để trống sẽ dùng: Về dịch vụ'),

            Text::make('Tiêu đề mục "Mục tiêu & Lợi ích"', 'dv_sectitle_benefits')
                ->helperText('Để trống sẽ dùng: Mục tiêu & Lợi ích'),

            Text::make('Tiêu đề mục "Lộ trình học"', 'dv_sectitle_roadmap')
                ->helperText('Để trống sẽ dùng: Lộ trình học'),

            Text::make('Tiêu đề mục "Lợi ích nhận được"', 'dv_sectitle_receive')
                ->helperText('Để trống sẽ dùng: Lợi ích dịch vụ'),

            Text::make('Tiêu đề mục "Người hướng dẫn"', 'dv_sectitle_instructor')
                ->helperText('Để trống sẽ dùng: Người hướng dẫn'),

            Text::make('Tiêu đề mục "Cảm nhận / Testimonials"', 'dv_feedbacks_heading')
                ->helperText('Để trống sẽ dùng: Khách hàng nói gì?'),

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

            ButtonGroup::make('Loại lịch', 'dv_schedule_type')
                ->helperText('Cố định: chỉ 1 ngày diễn ra. Định kỳ: lặp theo thứ trong một khoảng ngày.')
                ->choices([
                    'single'    => 'Cố định 1 ngày',
                    'recurring' => 'Định kỳ',
                ])
                ->default('single'),

            DatePicker::make('Ngày diễn ra', 'dv_date_single')
                ->helperText('Ngày diễn ra cố định.')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('dv_schedule_type', '==', 'single')]),

            DatePicker::make('Ngày bắt đầu', 'dv_date_start')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('dv_schedule_type', '==', 'recurring')]),

            DatePicker::make('Ngày kết thúc', 'dv_date_end')
                ->format('Y-m-d')
                ->displayFormat('d/m/Y')
                ->conditionalLogic([ConditionalLogic::where('dv_schedule_type', '==', 'recurring')]),

            Checkbox::make('Các thứ diễn ra', 'dv_weekdays')
                ->helperText('Tick các thứ dịch vụ diễn ra hàng tuần.')
                ->choices([
                    '1' => 'Thứ 2',
                    '2' => 'Thứ 3',
                    '3' => 'Thứ 4',
                    '4' => 'Thứ 5',
                    '5' => 'Thứ 6',
                    '6' => 'Thứ 7',
                    '7' => 'Chủ Nhật',
                ])
                ->conditionalLogic([ConditionalLogic::where('dv_schedule_type', '==', 'recurring')]),

            Text::make('Số khách / phiên', 'dv_guests')
                ->helperText('Ví dụ: 1-2 khách / phiên · Tối đa 1 người'),

            Textarea::make('Lựa chọn số lượng người (form đặt lịch)', 'dv_guest_options')
                ->helperText('Mỗi dòng một lựa chọn cho dropdown "Số lượng người" trong form. Mỗi dòng nên chứa 1 con số (vd: 1 người / 2 người / 5 người) vì giá = giá gốc × số người. Tránh dạng khoảng như "3-5". Để trống = giữ lựa chọn mặc định.')
                ->rows(4),

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

            Image::make('Ảnh gallery 6', 'dv_gallery_6')
                ->helperText('Ảnh thứ 6 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 7', 'dv_gallery_7')
                ->helperText('Ảnh thứ 7 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 8', 'dv_gallery_8')
                ->helperText('Ảnh thứ 8 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 9', 'dv_gallery_9')
                ->helperText('Ảnh thứ 9 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            // ─── TAB: LỢI ÍCH ────────────────────────────────────────────
            Tab::make('Lợi ích')->placement('left'),

            Repeater::make('Danh sách lợi ích', 'dv_benefits_items')
                ->layout('block')
                ->collapsed('dv_benefit_title')
                ->fields([
                    Text::make('Nhãn (uppercase)', 'dv_benefit_title')->required()
                        ->helperText('Ví dụ: CẢI THIỆN GIẤC NGỦ'),
                    Textarea::make('Mô tả', 'dv_benefit_desc')->rows(2),
                ]),

            // ─── TAB: LỘ TRÌNH ───────────────────────────────────────────
            Tab::make('Lộ trình')->placement('left'),

            Textarea::make('Mô tả lộ trình', 'dv_roadmap_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(2),

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

            // ─── TAB: LỢI ÍCH NHẬN ĐƯỢC ─────────────────────────────────
            Tab::make('Ưu đãi')->placement('left'),

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

            Text::make('YouTube', 'dv_instructor_youtube')
                ->helperText('Link kênh YouTube. Ví dụ: https://youtube.com/@linhtam'),

            Text::make('TikTok', 'dv_instructor_tiktok')
                ->helperText('Link TikTok. Ví dụ: https://tiktok.com/@linhtam'),

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

            Repeater::make('Khung giờ đặt lịch', 'dv_time_slots')
                ->helperText('Để trống sẽ dùng khung giờ mặc định: 09:00-10:30, 10:30-12:00, 14:00-15:30, 15:30-17:00, 17:00-18:30.')
                ->layout('table')
                ->fields([
                    Text::make('Khung giờ', 'dv_time_slot')->required()
                        ->helperText('Ví dụ: 09:00 - 10:30'),
                ]),

            // ─── TAB: CẢM NHẬN ───────────────────────────────────────────
            Tab::make('Cảm nhận')->placement('left'),

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
        ],
    ], false);
}, 10);
