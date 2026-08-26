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
                ->helperText('Ví dụ: Dịch Vụ Trải Nghiệm')
                ->default('Dịch Vụ Trải Nghiệm'),

        ],
    ], false);
}, 10);
