<?php

use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Trang Khoá Học',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_template', '==', 'page-template/page-khoa-hoc.php'),
        ],
        'fields' => [

            // ─── TAB: PAGE HEADER ─────────────────────────────────────────
            Tab::make('Page Header')->placement('left'),

            Text::make('Tiêu đề trang', 'kh_page_heading')
                ->helperText('Ví dụ: Course Directory')
                ->default('Course Directory'),

        ],
    ], false);
}, 10);
