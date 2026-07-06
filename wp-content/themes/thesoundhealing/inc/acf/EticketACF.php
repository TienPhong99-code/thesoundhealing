<?php

use Extended\ACF\Fields\Number;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'E-ticket quà tặng',
        'style'    => 'default',
        'position' => 'side',
        'location' => [
            Location::where('post_type', '==', 'khoa_hoc'),
            Location::where('post_type', '==', 'workshop'),
            Location::where('post_type', '==', 'dich_vu'),
        ],
        'fields' => [
            Number::make('Số ngày hiệu lực e-ticket', 'eticket_days')
                ->helperText('E-ticket quà tặng có hạn bao nhiêu ngày kể từ ngày đặt. Để trống/0 = không phát hành e-ticket.')
                ->min(0)
                ->default(0),
        ],
    ]);
});
