<?php

use Extended\ACF\Fields\Gallery;
use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    register_extended_field_group([
        'title' => 'Thiết lập menu',
        'style' => 'default',
        'position' => 'acf_after_title',
        'location' => [
            Location::where('nav_menu_item', '==', 'location/header-menu-pc'),
        ],
        'fields' => [
            Text::make('Tiêu đề panel', 'title_panel')
                ->helperText('Chỉ áp dụng cho lv1'),
            Gallery::make('Ảnh', 'images')
                ->helperText('Kích thước đề xuất: 350x700px<br>
                Chỉ áp dụng cho lv2, lv3')
                ->acceptedFileTypes([
                    'png', 'jpg', 'jpeg', 'gif',
                    'svg', 'avif', 'webp',
                ])
                ->maxFiles(2)
                ->format('id')
        ],
    ]);
}, 10);