<?php

use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'Lưu ý – Trang thanh toán',
        'style'    => 'default',
        'position' => 'normal',
        'location' => [
            Location::where('options_page', '==', 'theme-settings'),
        ],
        'fields' => [

            Tab::make('Lưu ý – Trang thanh toán')
                ->placement('left'),

            Text::make('Câu mở đầu', 'co_notes_intro')
                ->helperText('Đoạn giới thiệu hiển thị ngay dưới tiêu đề "Lưu ý". Để trống sẽ dùng nội dung mặc định.')
                ->default('Để có trải nghiệm thoải mái và trọn vẹn nhất, vui lòng đọc qua một số lưu ý dưới đây:'),

            Text::make('Tiêu đề mục Lưu ý', 'co_notes_title')
                ->helperText('Để trống sẽ dùng "Lưu ý".')
                ->default('Lưu ý'),

            Repeater::make('Danh sách lưu ý', 'co_notes_main')
                ->helperText('Mỗi dòng là một mục lưu ý. Có thể bôi đậm một phần bằng cách bọc trong thẻ &lt;strong&gt;...&lt;/strong&gt;. Để trống toàn bộ sẽ dùng danh sách mặc định.')
                ->layout('block')
                ->button('Thêm mục lưu ý')
                ->collapsed('co_notes_main_text')
                ->fields([
                    Textarea::make('Nội dung', 'co_notes_main_text')
                        ->rows(2)
                        ->required(),
                ]),

            Text::make('Tiêu đề mục phụ', 'co_notes_sub_title')
                ->helperText('Tiêu đề nhóm gợi ý phía dưới. Để trống sẽ dùng "Để có trải nghiệm tốt hơn".')
                ->default('Để có trải nghiệm tốt hơn'),

            Repeater::make('Danh sách "Để có trải nghiệm tốt hơn"', 'co_notes_sub')
                ->helperText('Mỗi dòng là một gợi ý. Có thể bôi đậm bằng thẻ &lt;strong&gt;...&lt;/strong&gt;. Để trống toàn bộ sẽ dùng danh sách mặc định.')
                ->layout('block')
                ->button('Thêm gợi ý')
                ->collapsed('co_notes_sub_text')
                ->fields([
                    Textarea::make('Nội dung', 'co_notes_sub_text')
                        ->rows(2)
                        ->required(),
                ]),

        ],
    ]);
});
