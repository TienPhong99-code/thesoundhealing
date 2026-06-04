<?php

use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    register_extended_field_group([
        'title'    => 'Thiết lập footer',
        'style'    => 'default',
        'position' => 'acf_after_title',
        'location' => [
            Location::where('options_page', '==', 'theme-settings'),
        ],
        'fields' => [

            Tab::make('Thông tin công ty')
                ->placement('left'),

            Group::make('Thông tin công ty', 'footer_company')
                ->fields([
                    Image::make('Logo', 'logo')
                        ->helperText('Kích thước đề xuất: 230x80px')
                        ->acceptedFileTypes(['png', 'jpg', 'jpeg', 'gif', 'webp', 'avif', 'svg'])
                        ->format('id'),
                    Text::make('Tên công ty', 'name')
                        ->required(),
                    Text::make('Mã số thuế', 'tax'),
                    Text::make('Email', 'email'),
                    Textarea::make('Địa chỉ', 'address')
                        ->newLines('br')
                        ->rows(3),
                    Text::make('Hotline', 'hotline'),
                ]),

            Textarea::make('Tagline', 'footer_tagline')
                ->helperText('Dòng mô tả ngắn hiển thị dưới logo. VD: Conscious growth for the modern soul.')
                ->rows(2),

            Tab::make('Mạng xã hội')
                ->placement('left'),

            Repeater::make('Mạng xã hội', 'footer_socials')
                ->layout('block')
                ->collapsed('label')
                ->fields([
                    Text::make('Nhãn', 'label')
                        ->column(50)
                        ->required(),
                    Text::make('Đường dẫn', 'url')
                        ->column(50),
                ]),

            Tab::make('Bản quyền')
                ->placement('left'),

            Text::make('Dòng bản quyền', 'footer_copyright')
                ->helperText('VD: © 2024 AETHERIA SPIRITUAL EDUCATION. ALL RIGHTS RESERVED.'),
        ],
    ]);
}, 10);
