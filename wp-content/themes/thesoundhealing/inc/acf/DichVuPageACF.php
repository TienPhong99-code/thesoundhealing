<?php

use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Trang Dịch Vụ',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_template', '==', 'page-template/page-dich-vu.php'),
        ],
        'fields' => [

            // ─── TAB: PAGE HEADER ─────────────────────────────────────────
            Tab::make('Page Header')->placement('left'),

            Text::make('Tiêu đề trang', 'dv_page_heading')
                ->helperText('Ví dụ: Dịch Vụ Trị Liệu')
                ->default('Dịch Vụ Trị Liệu'),

            Textarea::make('Mô tả trang', 'dv_page_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(3)
                ->default('Khám phá các liệu pháp chữa lành cá nhân hóa, từ trị liệu âm thanh đến năng lượng, được thiết kế để mang lại sự an lạc tuyệt đối.'),
        ],
    ], false);
}, 10);
