<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
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

            Text::make('Thời lượng', 'dv_duration')
                ->helperText('Ví dụ: 60 - 90 phút mỗi phiên'),

            Text::make('Trang phục', 'dv_clothing')
                ->helperText('Ví dụ: Đồ tập hoặc quần áo thoải mái, nhẹ nhàng'),

            Text::make('Địa điểm', 'dv_location')
                ->helperText('Ví dụ: Aetheria Sanctuary, Level 4, Thảo Điền'),

            Textarea::make('Chuẩn bị', 'dv_preparation')
                ->helperText('Ví dụ: Hạn chế ăn no 2 tiếng trước giờ trị liệu')
                ->rows(2),

            Textarea::make('Mô tả ngắn', 'dv_short_desc')
                ->helperText('Hiển thị trên card và hero trang chi tiết.')
                ->rows(3),

            Text::make('Giá dịch vụ', 'dv_price')
                ->helperText('Ví dụ: 800.000 VNĐ'),

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

            // ─── Feature card 1 ──
            Image::make('Feature 1 – Icon', 'dv_feature_1_icon')
                ->helperText('Icon SVG/PNG ~20px.')
                ->acceptedFileTypes(['svg', 'png', 'jpg'])
                ->format('url'),

            Text::make('Feature 1 – Tiêu đề', 'dv_feature_1_title')
                ->helperText('Ví dụ: Pha lê Alchemy'),

            Textarea::make('Feature 1 – Mô tả', 'dv_feature_1_desc')
                ->rows(2),

            // ─── Feature card 2 ──
            Image::make('Feature 2 – Icon', 'dv_feature_2_icon')
                ->helperText('Icon SVG/PNG ~20px.')
                ->acceptedFileTypes(['svg', 'png', 'jpg'])
                ->format('url'),

            Text::make('Feature 2 – Tiêu đề', 'dv_feature_2_title')
                ->helperText('Ví dụ: Tĩnh lặng tuyệt đối'),

            Textarea::make('Feature 2 – Mô tả', 'dv_feature_2_desc')
                ->rows(2),

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
