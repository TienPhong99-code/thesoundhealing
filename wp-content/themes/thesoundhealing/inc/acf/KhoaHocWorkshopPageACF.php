<?php

use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Trang Khóa Học & Workshop',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_template', '==', 'page-template/page-khoa-hoc-workshop.php'),
        ],
        'fields' => [

            Tab::make('Page Header')->placement('left'),

            Text::make('Tiêu đề trang', 'kh_ws_page_heading')
                ->helperText('Ví dụ: Khóa Học & Workshop')
                ->default('Khóa Học & Workshop'),

            Textarea::make('Mô tả trang', 'kh_ws_page_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(3)
                ->default('Khám phá các khóa học chuyên sâu và tham gia các buổi workshop trải nghiệm trực tiếp — hành trình chuyển hóa qua âm thanh và năng lượng.'),
        ],
    ], false);
}, 10);
