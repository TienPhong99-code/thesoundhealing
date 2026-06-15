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
        'title'    => 'Mô tả Workshop',
        'style'    => 'seamless',
        'position' => 'acf_after_title',
        'location' => [
            Location::where('post_type', '==', 'workshop'),
        ],
        'fields' => [
            Textarea::make('Mô tả', 'ws_description')
                ->helperText('Mô tả hiển thị ngay dưới tiêu đề trên trang chi tiết workshop.')
                ->rows(3),
        ],
    ]);

    mona_regist_acf_field_group([
        'title'    => 'Chi tiết Workshop',
        'style'    => 'default',
        'position' => 'normal',
        'location' => [
            Location::where('post_type', '==', 'workshop'),
        ],
        'fields' => [

            // ─── TAB: THÔNG TIN ───────────────────────────────────────────
            Tab::make('Thông tin')->placement('left'),

            Select::make('Hình thức', 'ws_format')
                ->helperText('Phân loại trực tiếp hay trực tuyến.')
                ->choices([
                    'Onsite' => 'Trực tiếp (Onsite)',
                    'Online' => 'Trực tuyến (Online)',
                ])
                ->default('Onsite'),

            Text::make('Ngày tổ chức', 'ws_date')
                ->helperText('Ví dụ: 15 THÁNG 1, 2025'),

            Text::make('Thời gian', 'ws_time')
                ->helperText('Ví dụ: 09:00 – 17:00'),

            Text::make('Thời lượng', 'ws_duration')
                ->helperText('Ví dụ: 1 ngày · 2 tiếng · Buổi sáng'),

            Textarea::make('Địa điểm', 'ws_location')
                ->helperText('Nhập mỗi địa điểm trên một dòng. Ví dụ: Aetheria Studio — Quận 1, TP.HCM')
                ->rows(3),

            Text::make('Số lượng khách', 'ws_guests')
                ->helperText('Ví dụ: 20 người · Tối đa 15 khách'),

            Textarea::make('Mô tả ngắn', 'ws_short_desc')
                ->helperText('Hiển thị trên card và hero trang chi tiết.')
                ->rows(3),

            Text::make('Học phí', 'ws_price')
                ->helperText('Ví dụ: 1.200.000 VNĐ'),

            Text::make('Mô tả chỗ còn lại', 'ws_capacity')
                ->helperText('Ví dụ: Còn 8 chỗ · Chỉ còn 2 chỗ · Hết chỗ'),

            Text::make('Chi nhánh', 'ws_branch')
                ->helperText('Hiển thị trên card. Ví dụ: Thảo Điền · Quận 1'),

            Number::make('Số chỗ còn lại (số)', 'ws_spots')
                ->helperText('Nhập số chỗ còn trống. 0 = Hết chỗ. Để trống = không hiển thị badge. Dùng cho bộ lọc tìm kiếm.')
                ->min(0),

            Select::make('Trạng thái', 'ws_status')
                ->choices([
                    'open'     => 'Còn chỗ',
                    'limited'  => 'Sắp hết chỗ',
                    'closed'   => 'Hết chỗ',
                    'upcoming' => 'Sắp diễn ra',
                ])
                ->default('open'),

            // ─── TAB: TRẢI NGHIỆM ────────────────────────────────────────
            Tab::make('Trải nghiệm')->placement('left'),

            Text::make('Tiêu đề', 'ws_exp_title')
                ->helperText('Ví dụ: Không gian chữa lành qua âm thanh')
                ->default('Không gian chữa lành qua âm thanh'),

            Textarea::make('Mô tả', 'ws_exp_desc')
                ->helperText('Đoạn văn mô tả trải nghiệm workshop.')
                ->rows(5),

            Image::make('Ảnh trải nghiệm 1', 'ws_exp_image_1')
                ->helperText('Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh trải nghiệm 2', 'ws_exp_image_2')
                ->helperText('Kích thước đề xuất: 300×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 4', 'ws_gallery_4')
                ->helperText('Ảnh thứ 4 trong bộ gallery trang chi tiết. Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 5', 'ws_gallery_5')
                ->helperText('Ảnh thứ 5 trong bộ gallery trang chi tiết. Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 6', 'ws_gallery_6')
                ->helperText('Ảnh thứ 6 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 7', 'ws_gallery_7')
                ->helperText('Ảnh thứ 7 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 8', 'ws_gallery_8')
                ->helperText('Ảnh thứ 8 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 9', 'ws_gallery_9')
                ->helperText('Ảnh thứ 9 trong bộ gallery trang chi tiết.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            // ─── TAB: LỘ TRÌNH ───────────────────────────────────────────
            Tab::make('Lộ trình')->placement('left'),

            Text::make('Nhãn lộ trình', 'ws_roadmap_label')
                ->helperText('Ví dụ: LỘ TRÌNH TRẢI NGHIỆM')
                ->default('LỘ TRÌNH TRẢI NGHIỆM'),

            Text::make('Tiêu đề lộ trình', 'ws_roadmap_heading')
                ->helperText('Ví dụ: Hành trình chữa lành')
                ->default('Hành trình chữa lành'),

            Repeater::make('Các giai đoạn', 'ws_roadmap_items')
                ->helperText('Mỗi giai đoạn gồm tiêu đề, mô tả và tag.')
                ->layout('block')
                ->collapsed('ws_week_title')
                ->fields([
                    Text::make('Tiêu đề giai đoạn', 'ws_week_title')
                        ->helperText('Ví dụ: Phần 1 – Kết nối với hơi thở')
                        ->required(),

                    Textarea::make('Mô tả', 'ws_week_desc')
                        ->rows(3),

                    Text::make('Tags', 'ws_week_tags')
                        ->helperText('Các tag cách nhau bởi dấu phẩy. Ví dụ: Sound Bath, Thiền định'),
                ]),

            // ─── TAB: NGƯỜI HƯỚNG DẪN ────────────────────────────────────
            Tab::make('Người hướng dẫn')->placement('left'),

            Text::make('Nhãn', 'ws_instructor_label')
                ->default('NGƯỜI HƯỚNG DẪN'),

            Image::make('Ảnh', 'ws_instructor_image')
                ->helperText('Ảnh vuông 96×96px, bo tròn.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Text::make('Tên', 'ws_instructor_name')
                ->helperText('Ví dụ: Elena Vu'),

            Textarea::make('Giới thiệu', 'ws_instructor_bio')
                ->rows(3),

            Text::make('Instagram', 'ws_instructor_instagram')
                ->helperText('URL Instagram đầy đủ. Ví dụ: https://instagram.com/linhtam'),

            Text::make('Facebook', 'ws_instructor_facebook')
                ->helperText('URL Facebook đầy đủ. Ví dụ: https://facebook.com/linhtam'),

            Text::make('WhatsApp', 'ws_instructor_whatsapp')
                ->helperText('Số điện thoại hoặc link WhatsApp. Ví dụ: https://wa.me/84901234567'),

            Text::make('Facebook Messenger', 'ws_instructor_messenger')
                ->helperText('Link Messenger. Ví dụ: https://m.me/linhtam'),

            // ─── TAB: LỢI ÍCH ────────────────────────────────────────────
            Tab::make('Lợi ích')->placement('left'),

            Text::make('Tiêu đề', 'ws_benefits_heading')
                ->default('Bạn sẽ nhận được gì?'),

            Repeater::make('Danh sách lợi ích', 'ws_benefits_items')
                ->layout('block')
                ->collapsed('ws_benefit_title')
                ->fields([
                    Text::make('Tiêu đề', 'ws_benefit_title')->required(),
                    Textarea::make('Mô tả', 'ws_benefit_desc')->rows(2),
                ]),

            // ─── TAB: LỢI ÍCH NHẬN ĐƯỢC ─────────────────────────────────
            Tab::make('Lợi ích nhận được')->placement('left'),

            Repeater::make('Lợi ích sẽ nhận', 'ws_receive_items')
                ->helperText('Mỗi ô gồm tiêu đề và mô tả. Ví dụ: 70% Thực hành, Trải nghiệm trọn vẹn...')
                ->layout('block')
                ->collapsed('ws_receive_title')
                ->fields([
                    Text::make('Tiêu đề', 'ws_receive_title')->required(),
                    Textarea::make('Mô tả', 'ws_receive_desc')->rows(2),
                ]),

            // ─── TAB: CẢM NHẬN ───────────────────────────────────────────
            Tab::make('Cảm nhận')->placement('left'),

            Text::make('Tiêu đề', 'ws_feedbacks_heading')
                ->default('Cảm nhận của học viên'),

            Repeater::make('Hình ảnh khách hàng', 'ws_feedbacks')
                ->helperText('Thêm hình ảnh khách hàng/học viên tham dự.')
                ->layout('table')
                ->fields([
                    Image::make('Hình ảnh', 'ws_fb_image')
                        ->required()
                        ->helperText('Ảnh vuông, ví dụ 400×400px.')
                        ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                        ->format('array'),
                ]),

            // ─── TAB: CTA ────────────────────────────────────────────────
            Tab::make('CTA')->placement('left'),

            Text::make('Tiêu đề CTA', 'ws_cta_title')
                ->default('Đăng ký trước khi hết chỗ'),

            Textarea::make('Mô tả CTA', 'ws_cta_desc')
                ->rows(2)
                ->default('Số lượng chỗ giới hạn để đảm bảo chất lượng trải nghiệm tốt nhất cho mỗi người tham dự.'),
        ],
    ], false);
}, 10);
