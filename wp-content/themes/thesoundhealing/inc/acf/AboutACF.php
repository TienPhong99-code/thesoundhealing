<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\URL;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Về Chúng Tôi',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_template', '==', 'page-template/page-about.php'),
        ],
        'fields' => [

            // ─── TAB: HERO ────────────────────────────────────────────────
            Tab::make('Hero')->placement('left'),

            Text::make('Tiêu đề Hero', 'ab_hero_heading')
                ->helperText('Ví dụ: Bản Chất Của Sự Tĩnh Lặng')
                ->default('Bản Chất Của Sự Tĩnh Lặng'),

            Textarea::make('Mô tả Hero', 'ab_hero_desc')
                ->helperText('1–3 câu hiển thị dưới tiêu đề.')
                ->rows(3),

            Image::make('Ảnh nền Hero', 'ab_hero_image')
                ->helperText('Ảnh toàn chiều rộng phía sau heading. Tỉ lệ: 16/9 hoặc panoramic.')
                ->format('array')
                ->previewSize('medium'),

            // ─── TAB: HÀNH TRÌNH ──────────────────────────────────────────
            Tab::make('Hành Trình')->placement('left'),

            Text::make('Tiêu đề', 'ab_journey_heading')
                ->helperText('Ví dụ: Hành Trình Của Chúng Tôi')
                ->default('Hành Trình Của Chúng Tôi'),

            Textarea::make('Đoạn 1', 'ab_journey_desc_1')
                ->helperText('Đoạn văn đầu tiên.')
                ->rows(4),

            Textarea::make('Đoạn 2', 'ab_journey_desc_2')
                ->helperText('Đoạn văn thứ hai.')
                ->rows(4),

            Text::make('Nhãn link', 'ab_journey_link_text')
                ->helperText('Ví dụ: Khám Phá Triết Lý')
                ->default('Khám Phá Triết Lý'),

            URL::make('URL link', 'ab_journey_link_url')
                ->helperText('Đường dẫn trang triết lý / philosophy.'),

            Image::make('Ảnh minh họa', 'ab_journey_image')
                ->helperText('Ảnh cột bên phải. Tỉ lệ: 3/4 (portrait).')
                ->format('array')
                ->previewSize('medium'),

            // ─── TAB: NỀN TẢNG CỐT LÕI ───────────────────────────────────
            Tab::make('Nền Tảng Cốt Lõi')->placement('left'),

            Text::make('Tiêu đề section', 'ab_pillars_heading')
                ->helperText('Ví dụ: Nền Tảng Cốt Lõi')
                ->default('Nền Tảng Cốt Lõi'),

            Textarea::make('Mô tả section', 'ab_pillars_desc')
                ->helperText('1 câu mô tả ngắn.')
                ->rows(2),

            Repeater::make('Danh sách cột lõi', 'ab_pillars_items')
                ->helperText('Chính xác 2 mục. Mỗi mục hiển thị cạnh một ảnh.')
                ->layout('block')
                ->minRows(2)
                ->maxRows(2)
                ->collapsed('title')
                ->button('+ Thêm cột lõi')
                ->fields([
                    Text::make('Số thứ tự', 'number')
                        ->helperText('Ví dụ: 01')
                        ->default('01'),
                    Text::make('Tiêu đề', 'title')->required(),
                    Textarea::make('Mô tả', 'desc')->rows(3)->required(),
                    Image::make('Ảnh minh họa', 'image')
                        ->format('array')
                        ->previewSize('medium'),
                ]),

            // ─── TAB: NGƯỜI SÁNG LẬP ─────────────────────────────────────
            Tab::make('Người Sáng Lập')->placement('left'),

            Text::make('Nhãn', 'ab_visionary_label')
                ->helperText('Ví dụ: Người Sáng Lập')
                ->default('Người Sáng Lập'),

            Text::make('Tên', 'ab_visionary_name')
                ->helperText('Ví dụ: Elias Thorne')
                ->default('Elias Thorne'),

            Textarea::make('Trích dẫn', 'ab_visionary_quote')
                ->helperText('Quote lớn hiển thị nổi bật. Nên có dấu ngoặc kép.')
                ->rows(4),

            Textarea::make('Tiểu sử', 'ab_visionary_bio')
                ->helperText('Đoạn mô tả ngắn về người sáng lập.')
                ->rows(4),

            Image::make('Ảnh chân dung', 'ab_visionary_image')
                ->helperText('Portrait, tỉ lệ 3/4.')
                ->format('array')
                ->previewSize('medium'),

            Image::make('Ảnh nền texture', 'ab_visionary_bg')
                ->helperText('Texture overlay mờ phía sau section.')
                ->format('array')
                ->previewSize('medium'),

            // ─── TAB: GALLERY ─────────────────────────────────────────────
            Tab::make('Gallery')->placement('left'),

            Text::make('Tiêu đề Gallery', 'ab_gallery_heading')
                ->helperText('Ví dụ: Sống Trọn Vẹn')
                ->default('Sống Trọn Vẹn'),

            Textarea::make('Mô tả Gallery', 'ab_gallery_desc')
                ->helperText('1 câu mô tả ngắn.')
                ->rows(2),

            Repeater::make('Ảnh Gallery', 'ab_gallery_images')
                ->helperText('Đúng 4 ảnh. Ảnh đầu tiên sẽ hiển thị lớn (chiếm 2 hàng).')
                ->layout('block')
                ->minRows(4)
                ->maxRows(4)
                ->button('+ Thêm ảnh')
                ->fields([
                    Image::make('Ảnh', 'image')
                        ->format('array')
                        ->previewSize('medium')
                        ->required(),
                ]),

            // ─── TAB: CTA ─────────────────────────────────────────────────
            Tab::make('CTA')->placement('left'),

            Text::make('Tiêu đề CTA', 'ab_cta_heading')
                ->helperText('Ví dụ: Bắt Đầu Hành Trình Của Bạn')
                ->default('Bắt Đầu Hành Trình Của Bạn'),

            Textarea::make('Mô tả CTA', 'ab_cta_desc')
                ->helperText('1–2 câu.')
                ->rows(2),

            Text::make('Nút chính — nhãn', 'ab_cta_btn_primary_text')
                ->default('Đặt Lịch Trải Nghiệm'),

            URL::make('Nút chính — URL', 'ab_cta_btn_primary_url'),

            Text::make('Nút phụ — nhãn', 'ab_cta_btn_secondary_text')
                ->default('Xem Các Khóa Học'),

            URL::make('Nút phụ — URL', 'ab_cta_btn_secondary_url'),
        ],
    ], false);
}, 10);
