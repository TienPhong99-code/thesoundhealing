<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\URL;
use Extended\ACF\Fields\WYSIWYGEditor;
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

            WYSIWYGEditor::make('Mô tả Hero', 'ab_hero_desc')
                ->helperText('1–3 câu hiển thị dưới tiêu đề.')
                ->tabs('visual')
                ->toolbar('basic')
                ->disableMediaUpload(),

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

            WYSIWYGEditor::make('Đoạn 1', 'ab_journey_desc_1')
                ->helperText('Đoạn văn đầu tiên.')
                ->tabs('visual')
                ->toolbar('basic')
                ->disableMediaUpload(),

            WYSIWYGEditor::make('Đoạn 2', 'ab_journey_desc_2')
                ->helperText('Đoạn văn thứ hai.')
                ->tabs('visual')
                ->toolbar('basic')
                ->disableMediaUpload(),

            URL::make('URL Video', 'ab_journey_video_url')
                ->helperText('Link YouTube hoặc Vimeo hiển thị cột bên trái.'),

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
                    WYSIWYGEditor::make('Mô tả', 'desc')
                        ->helperText('Có thể dùng thẻ <strong> để in đậm.')
                        ->tabs('visual')
                        ->toolbar('basic')
                        ->disableMediaUpload(),
                ]),

            // ─── TAB: NỀN TẢNG CỐT LÕI ───────────────────────────────────
            Tab::make('Nền Tảng Cốt Lõi')->placement('left'),

            // Featured block (top)
            Text::make('Badge nhãn nổi bật', 'ab_pillars_feat_badge')
                ->helperText('Ví dụ: Ưu điểm nổi bật'),

            Text::make('Tiêu đề nổi bật', 'ab_pillars_feat_heading')
                ->helperText('Heading lớn cột phải.'),

            WYSIWYGEditor::make('Mô tả nổi bật', 'ab_pillars_feat_desc')
                ->helperText('1–2 câu.')
                ->tabs('visual')
                ->toolbar('basic')
                ->disableMediaUpload(),

            Image::make('Ảnh nổi bật', 'ab_pillars_feat_image')
                ->helperText('Ảnh cột trái. Tỉ lệ: 4/5.')
                ->format('array')
                ->previewSize('medium'),

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
                    WYSIWYGEditor::make('Mô tả', 'desc')
                        ->tabs('visual')
                        ->toolbar('basic')
                        ->disableMediaUpload(),
                    Textarea::make('Checklist (mỗi dòng 1 mục)', 'list_items')
                        ->helperText('Mỗi dòng là một mục trong danh sách.')
                        ->rows(4),
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
                    WYSIWYGEditor::make('Mô tả', 'desc')
                        ->tabs('visual')
                        ->toolbar('basic')
                        ->disableMediaUpload(),
                ]),

            // ─── TAB: CTA ─────────────────────────────────────────────────
            Tab::make('CTA')->placement('left'),

            Text::make('Tiêu đề CTA', 'ab_cta_heading')
                ->helperText('Ví dụ: Bắt Đầu Hành Trình Của Bạn')
                ->default('Bắt Đầu Hành Trình Của Bạn'),

            WYSIWYGEditor::make('Mô tả CTA', 'ab_cta_desc')
                ->helperText('1–2 câu.')
                ->tabs('visual')
                ->toolbar('basic')
                ->disableMediaUpload(),

            Text::make('Nút chính — nhãn', 'ab_cta_btn_primary_text')
                ->default('Đặt Lịch Trải Nghiệm'),

            URL::make('Nút chính — URL', 'ab_cta_btn_primary_url'),

            Text::make('Nút phụ — nhãn', 'ab_cta_btn_secondary_text')
                ->default('Xem Các Khóa Học'),

            URL::make('Nút phụ — URL', 'ab_cta_btn_secondary_url'),
        ],
    ], false);
}, 10);
