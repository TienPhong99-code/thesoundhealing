<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
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

            Text::make('Ngày tổ chức', 'ws_date')
                ->helperText('Ví dụ: 15 THÁNG 1, 2025'),

            Text::make('Thời gian', 'ws_time')
                ->helperText('Ví dụ: 09:00 – 17:00'),

            Text::make('Địa điểm', 'ws_location')
                ->helperText('Ví dụ: Aetheria Studio — Quận 1, TP.HCM'),

            Textarea::make('Mô tả ngắn', 'ws_short_desc')
                ->helperText('Hiển thị trên card và hero trang chi tiết.')
                ->rows(3),

            Text::make('Học phí', 'ws_price')
                ->helperText('Ví dụ: 1.200.000 VNĐ'),

            Text::make('Số chỗ còn lại', 'ws_capacity')
                ->helperText('Ví dụ: Còn 8 chỗ · Chỉ còn 2 chỗ · Hết chỗ'),

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
