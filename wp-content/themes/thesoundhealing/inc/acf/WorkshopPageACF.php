<?php

use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Trang Workshop',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_template', '==', 'page-template/page-workshop.php'),
        ],
        'fields' => [

            Tab::make('Page Header')->placement('left'),

            Text::make('Tiêu đề trang', 'ws_page_heading')
                ->helperText('Ví dụ: Workshop & Sự Kiện')
                ->default('Workshop & Sự Kiện'),

            Textarea::make('Mô tả trang', 'ws_page_desc')
                ->helperText('1–2 câu hiển thị dưới tiêu đề.')
                ->rows(3)
                ->default('Tham gia các buổi trải nghiệm trực tiếp — nơi âm thanh và năng lượng hội tụ để tạo ra sự thay đổi sâu sắc.'),
        ],
    ], false);
}, 10);
