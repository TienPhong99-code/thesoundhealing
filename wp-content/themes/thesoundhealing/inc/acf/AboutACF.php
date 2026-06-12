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

            Image::make('Ảnh minh họa Hero', 'ab_hero_image')
                ->helperText('Ảnh cột bên phải. Tỉ lệ: 4/3 hoặc 1/1.')
                ->format('array')
                ->previewSize('medium'),

            Textarea::make('Ghi chú nhỏ', 'ab_hero_note')
                ->helperText('Dòng chú thích nhỏ hiển thị dưới mô tả.')
                ->rows(2),

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

            URL::make('URL Video', 'ab_journey_video_url')
                ->helperText('Link YouTube hoặc Vimeo hiển thị cột bên trái.'),

            Image::make('Ảnh thumbnail Video', 'ab_journey_image')
                ->helperText('Ảnh hiển thị khi chưa có video URL. Tỉ lệ: 16/9.')
                ->format('array')
                ->previewSize('medium'),

            // ─── TAB: HỆ SINH THÁI ────────────────────────────────────────
            Tab::make('Hệ Sinh Thái')->placement('left'),

            Text::make('Dòng tiêu đề trên', 'ab_eco_heading_top')
                ->helperText('Ví dụ: Hệ sinh thái')
                ->default('Hệ sinh thái'),

            Text::make('Dòng tiêu đề dưới', 'ab_eco_heading_bottom')
                ->helperText('Ví dụ: HEALIVERSE HOLISTIC CENTRE')
                ->default('HEALIVERSE HOLISTIC CENTRE'),

            Repeater::make('Danh sách thành viên hệ sinh thái', 'ab_eco_items')
                ->helperText('Chính xác 3 mục.')
                ->layout('block')
                ->minRows(3)
                ->maxRows(3)
                ->collapsed('name')
                ->button('+ Thêm')
                ->fields([
                    Image::make('Logo', 'logo')
                        ->format('array')
                        ->previewSize('medium'),
                    Text::make('Tên domain', 'name')
                        ->helperText('Ví dụ: HEALIVERSE.VN')
                        ->required(),
                    URL::make('URL', 'url')
                        ->helperText('Đường dẫn website tương ứng.'),
                    Textarea::make('Mô tả', 'desc')
                        ->helperText('Có thể dùng thẻ <strong> để in đậm.')
                        ->rows(3),
                ]),

            // ─── TAB: NỀN TẢNG CỐT LÕI ───────────────────────────────────
            Tab::make('Nền Tảng Cốt Lõi')->placement('left'),

            // Featured block (top)
            Text::make('Badge nhãn nổi bật', 'ab_pillars_feat_badge')
                ->helperText('Ví dụ: Ưu điểm nổi bật'),

            Text::make('Tiêu đề nổi bật', 'ab_pillars_feat_heading')
                ->helperText('Heading lớn cột phải.'),

            Textarea::make('Mô tả nổi bật', 'ab_pillars_feat_desc')
                ->helperText('1–2 câu.')
                ->rows(3),

            Textarea::make('Danh sách checklist', 'ab_pillars_feat_list')
                ->helperText('Mỗi dòng là một mục. Hiển thị trong khung màu.')
                ->rows(5),

            Image::make('Ảnh nổi bật', 'ab_pillars_feat_image')
                ->helperText('Ảnh cột trái. Tỉ lệ: 4/5.')
                ->format('array')
                ->previewSize('medium'),

            Text::make('Badge trên ảnh', 'ab_pillars_feat_img_badge')
                ->helperText('Nhãn hiển thị góc dưới trái ảnh.'),

            Text::make('Nhãn link', 'ab_pillars_feat_link_text')
                ->helperText('Ví dụ: Xem tất cả ưu đãi'),

            URL::make('URL link', 'ab_pillars_feat_link_url'),

            // 3-column cards (bottom)
            Repeater::make('Danh sách thẻ (3 cột)', 'ab_pillars_items')
                ->helperText('Chính xác 3 thẻ hiển thị hàng dưới.')
                ->layout('block')
                ->minRows(3)
                ->maxRows(3)
                ->collapsed('title')
                ->button('+ Thêm thẻ')
                ->fields([
                    Text::make('Tiêu đề', 'title')->required(),
                    Textarea::make('Mô tả', 'desc')->rows(2),
                    Textarea::make('Checklist (mỗi dòng 1 mục)', 'list_items')
                        ->helperText('Mỗi dòng là một mục trong danh sách.')
                        ->rows(4),
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

            // ─── TAB: ĐẶC ĐIỂM NỔI BẬT ───────────────────────────────────
            Tab::make('Đặc Điểm Nổi Bật')->placement('left'),

            Textarea::make('Tiêu đề (mỗi dòng 1 dòng)', 'ab_features_heading')
                ->helperText('Dòng đầu sẽ in đậm. Ví dụ: Trải Nghiệm Chữa Lành\nToàn Diện')
                ->rows(2),

            Image::make('Ảnh minh họa', 'ab_features_image')
                ->helperText('Ảnh cột trái bên dưới tiêu đề. Tỉ lệ: 4/3.')
                ->format('array')
                ->previewSize('medium'),

            Repeater::make('Danh sách đặc điểm', 'ab_features_items')
                ->helperText('Mỗi hàng là một đặc điểm hiển thị bên cột phải.')
                ->layout('block')
                ->collapsed('title')
                ->button('+ Thêm đặc điểm')
                ->fields([
                    Text::make('Tiêu đề', 'title')->required(),
                    Textarea::make('Mô tả', 'desc')->rows(2),
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
