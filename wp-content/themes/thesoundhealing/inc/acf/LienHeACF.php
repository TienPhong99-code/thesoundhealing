<?php

use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Liên Hệ',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_template', '==', 'page-template/page-lien-he.php'),
        ],
        'fields' => [

            // ─── TAB: HERO ────────────────────────────────────────────────
            Tab::make('Hero')->placement('left'),

            Text::make('Tiêu đề', 'lh_hero_heading')
                ->helperText('Ví dụ: Kết Nối Cùng Aetheria')
                ->default('Kết Nối Cùng Aetheria'),

            Textarea::make('Mô tả', 'lh_hero_desc')
                ->helperText('1–2 câu hiển thị dưới tiêu đề hero.')
                ->rows(3),

            // ─── TAB: THÔNG TIN LIÊN HỆ ──────────────────────────────────
            Tab::make('Thông tin liên hệ')->placement('left'),

            Text::make('Tên công ty', 'lh_company')
                ->helperText('Ví dụ: HEALIVERSE HOLDINGS.,JSC')
                ->default('HEALIVERSE HOLDINGS.,JSC'),

            Textarea::make('Địa chỉ', 'lh_address')
                ->helperText('Địa chỉ cơ sở. Xuống dòng để tách 2 dòng hiển thị.')
                ->rows(2),

            Text::make('Số điện thoại 1', 'lh_phone')
                ->helperText('Ví dụ: 0939 624 684'),

            Text::make('Ghi chú số 1', 'lh_phone_label')
                ->helperText('Ví dụ: Kennedy - English'),

            Text::make('Số điện thoại 2', 'lh_phone_2')
                ->helperText('Ví dụ: 0906 502 582'),

            Text::make('Ghi chú số 2', 'lh_phone_2_label')
                ->helperText('Ví dụ: Việt - tiếng Việt'),

            Text::make('Email', 'lh_email')
                ->helperText('Ví dụ: khanh@thesoundhealing.vn'),

            Text::make('URL Instagram', 'lh_instagram_url')
                ->helperText('Đường dẫn đến trang Instagram.'),

            Text::make('URL Facebook', 'lh_facebook_url')
                ->helperText('Đường dẫn đến trang Facebook.'),

            // ─── TAB: FORM LIÊN HỆ ───────────────────────────────────────
            Tab::make('Form liên hệ')->placement('left'),

            Text::make('Shortcode CF7', 'lh_cf7_shortcode')
                ->helperText('Dán shortcode Contact Form 7. Ví dụ: [contact-form-7 id="123" title="Liên Hệ"]'),

            // ─── TAB: BẢN ĐỒ ─────────────────────────────────────────────
            Tab::make('Bản đồ')->placement('left'),

            Text::make('Embed URL Google Maps', 'lh_map_embed_url')
                ->helperText('URL nhúng từ Google Maps: Share → Embed a map → lấy phần src="...".'),

            Text::make('Tiêu đề bản đồ (title)', 'lh_pin_label')
                ->helperText('Dùng làm thuộc tính title của iframe. Ví dụ: The Sound Healing')
                ->default('The Sound Healing'),

            // ─── TAB: FAQ / CTA ───────────────────────────────────────────
            Tab::make('FAQ / CTA')->placement('left'),

            Text::make('Tiêu đề', 'lh_faq_heading')
                ->helperText('Ví dụ: Bạn Cần Hỗ Trợ Nhanh?')
                ->default('Bạn Cần Hỗ Trợ Nhanh?'),

            Textarea::make('Mô tả', 'lh_faq_desc')
                ->helperText('1–2 câu mô tả ngắn.')
                ->rows(2),

            Repeater::make('Danh sách câu hỏi', 'lh_faq_items')
                ->helperText('Thêm các câu hỏi thường gặp. Mỗi mục hiển thị dạng accordion.')
                ->layout('block')
                ->collapsed('faq_question')
                ->button('+ Thêm câu hỏi')
                ->fields([
                    Text::make('Câu hỏi', 'faq_question')->required(),
                    Textarea::make('Câu trả lời', 'faq_answer')->rows(3)->required(),
                ]),
        ],
    ], false);
}, 10);
