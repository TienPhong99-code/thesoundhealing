<?php

use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

/**
 * Công tắc "Ẩn khỏi danh sách" (unlisted) cho khoá học / workshop / dịch vụ.
 *
 * Bật → bài biến mất khỏi mọi nơi liệt kê ở front-end (trang chủ, danh sách, tìm kiếm,
 * load-more, archive) và không lên Google, nhưng link trực tiếp vẫn mở được bình thường.
 * Logic lọc nằm ở inc/hooks/HiddenPostHook.php.
 */
add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'Ẩn bài viết',
        'style'    => 'default',
        'position' => 'side',
        'location' => [
            Location::where('post_type', '==', 'khoa_hoc'),
            Location::where('post_type', '==', 'workshop'),
            Location::where('post_type', '==', 'dich_vu'),
        ],
        'fields' => [
            TrueFalse::make('Ẩn khỏi danh sách', 'tsh_hidden')
                ->stylized('Ẩn', 'Hiện')
                ->helperText('Bài vẫn vào được bằng link trực tiếp, nhưng không hiện ở trang chủ, danh sách, tìm kiếm và không lên Google. Dùng cho sự kiện riêng tư — chỉ gửi link cho người được mời.')
                ->default(false),
        ],
    ]);
});
