<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\PostObject;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\WYSIWYGEditor;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

// Xóa field group "Thiết lập trang chủ" cũ (lưu trong DB) khỏi trang admin
add_filter('acf/get_field_groups', function (array $groups): array {
    return array_values(array_filter($groups, fn($g) => $g['title'] !== 'Thiết lập trang chủ'));
});

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'          => 'Sound Healing — Trang chủ',
        'style'          => 'default',
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
        'location'       => [
            Location::where('page_type', '==', 'front_page'),
        ],
        'fields' => [

            // ─── TAB: HERO ────────────────────────────────────────────────
            Tab::make('Hero')->placement('left'),

            Image::make('Ảnh nền hero', 'hero_image')
                ->helperText('Kích thước đề xuất: 1440×819px. Định dạng JPG/PNG/WEBP/GIF.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'gif'])
                ->format('array'),

            WYSIWYGEditor::make('Tiêu đề', 'hero_heading')
                ->helperText('Có thể xuống hàng, đổi màu, đổi font trực tiếp trong editor.')
                ->tabs('visual')
                ->toolbar('basic'),

            WYSIWYGEditor::make('Mô tả ngắn', 'hero_desc')
                ->helperText('1–2 câu giới thiệu hiển thị dưới tiêu đề. Có thể đổi màu, font, xuống hàng. Dùng tab "Text" để thêm <br> hoặc class.')
                ->tabs('all')
                ->toolbar('basic'),

            // ─── TAB: SỰ KIỆN NỔI BẬT ────────────────────────────────────
            Tab::make('Sự kiện nổi bật')->placement('left'),

            Text::make('Tiêu đề', 'featured_heading')
                ->helperText('Ví dụ: Các Sự Kiện Nổi Bật')
                ->default('Các Sự Kiện Nổi Bật'),

            Textarea::make('Mô tả', 'featured_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(2),

            Link::make('Link "Xem tất cả"', 'featured_link')
                ->helperText('Link dẫn đến trang tổng hợp sự kiện.'),

            PostObject::make('Chọn sự kiện nổi bật', 'featured_items')
                ->helperText('Chọn các dịch vụ, khóa học hoặc workshop muốn hiển thị. Kéo thả để sắp xếp thứ tự.')
                ->postTypes(['dich_vu', 'khoa_hoc', 'workshop'])
                ->multiple()
                ->format('object'),

            // ─── TAB: DỊCH VỤ ────────────────────────────────────────────
            Tab::make('Dịch vụ nổi bật')->placement('left'),

            Text::make('Tiêu đề', 'service_heading')
                ->helperText('Ví dụ: Trải Nghiệm Dịch Vụ')
                ->default('Trải Nghiệm Dịch Vụ'),

            Textarea::make('Mô tả', 'service_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(2),

            Link::make('Link "Xem tất cả"', 'service_link')
                ->helperText('Link dẫn đến trang danh sách dịch vụ.'),

            PostObject::make('Dịch vụ nổi bật', 'service_items')
                ->helperText('Chọn các dịch vụ muốn hiển thị trên trang chủ. Để trống → lấy dịch vụ mới nhất.')
                ->postTypes(['dich_vu'])
                ->multiple()
                ->format('object'),

            // ─── TAB: COURSES ─────────────────────────────────────────────
            Tab::make('Khóa học nổi bật')->placement('left'),

            Text::make('Tiêu đề', 'courses_heading')
                ->helperText('Ví dụ: Đào Tạo Chuyên Sâu')
                ->default('Đào Tạo Chuyên Sâu'),

            Link::make('Link "Xem tất cả"', 'courses_link')
                ->helperText('Link dẫn đến trang danh sách khóa học.'),

            PostObject::make('Khóa học nổi bật', 'courses_items')
                ->helperText('Tick chọn các khóa học muốn hiển thị trên trang chủ.')
                ->postTypes(['khoa_hoc'])
                ->multiple()
                ->format('object'),

            // ─── TAB: WORKSHOP ────────────────────────────────────────────
            Tab::make('Workshop nổi bật')->placement('left'),

            Text::make('Nhãn nhỏ (label)', 'ws_home_label')
                ->helperText('Ví dụ: SỰ KIỆN SẮP TỚI')
                ->default('SỰ KIỆN SẮP TỚI'),

            Text::make('Tiêu đề', 'ws_home_heading')
                ->helperText('Ví dụ: Workshop & Trải Nghiệm')
                ->default('Workshop & Trải Nghiệm'),

            Link::make('Link "Xem tất cả"', 'ws_home_link')
                ->helperText('Link dẫn đến trang danh sách Workshop.'),

            PostObject::make('Workshop nổi bật', 'ws_home_items')
                ->helperText('Tick chọn các Workshop muốn hiển thị trên trang chủ. Để trống → lấy 3 workshop mới nhất.')
                ->postTypes(['workshop'])
                ->multiple()
                ->format('object'),

            // ─── TAB: KHÓA HỌC & WORKSHOP (mục gộp) ───────────────────────
            Tab::make('Khóa Học & Workshop')->placement('left'),

            Text::make('Tiêu đề', 'cwlist_heading')
                ->helperText('Ví dụ: Khóa Học & Workshop')
                ->default('Khóa Học & Workshop'),

            Textarea::make('Mô tả', 'cwlist_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(2)
                ->default('Tham gia các khóa học và workshop được thiết kế để dẫn dắt bạn qua từng giai đoạn chuyển hóa tâm thức sâu sắc.'),

            Link::make('Link "Xem tất cả"', 'cwlist_link_all')
                ->helperText('Link dẫn đến trang tổng hợp Khóa học & Workshop.'),

            // ─── TAB: GALLERY ─────────────────────────────────────────────
            Tab::make('Học viên & Trải nghiệm')->placement('left'),

            Text::make('Tiêu đề', 'gallery_heading')
                ->helperText('Ví dụ: Khoảnh Khắc Tại Aetheria')
                ->default('Khoảnh Khắc Tại Aetheria'),

            Repeater::make('Danh sách ảnh', 'gallery_items')
                ->helperText('Kích thước đề xuất: 360×480px (tỷ lệ 3:4).')
                ->layout('block')
                ->collapsed('image')
                ->minRows(1)
                ->fields([
                    Image::make('Ảnh', 'image')
                        ->helperText('Kích thước đề xuất: 360×480px (tỷ lệ 3:4).')
                        ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                        ->format('array')
                        ->required(),

                    Text::make('Trích dẫn (quote)', 'quote')
                        ->helperText('Hiện khi hover. Bỏ trống nếu không có overlay.'),

                    Text::make('Tên học viên', 'name')
                        ->helperText('Ví dụ: — MAI LAN'),
                ]),

            // ─── TAB: TEAMS ───────────────────────────────────────────────
            Tab::make('Đội ngũ chuyên gia')->placement('left'),

            Text::make('Tiêu đề', 'teams_heading')
                ->helperText('Ví dụ: Đội Ngũ Chuyên Gia')
                ->default('Đội Ngũ Chuyên Gia'),

            Repeater::make('Danh sách chuyên gia', 'teams_items')
                ->helperText('Mỗi chuyên gia gồm ảnh, tên, vai trò và mô tả ngắn.')
                ->layout('block')
                ->collapsed('name')
                ->minRows(1)
                ->fields([
                    Image::make('Ảnh', 'image')
                        ->helperText('Kích thước đề xuất: 400×533px (tỷ lệ 3:4).')
                        ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                        ->format('array')
                        ->required(),

                    Text::make('Tên chuyên gia', 'name')
                        ->helperText('Ví dụ: Elena Vu')
                        ->required(),

                    Text::make('Vai trò', 'role')
                        ->helperText('Ví dụ: MASTER SOUND HEALER'),

                    Textarea::make('Mô tả ngắn', 'desc')
                        ->helperText('Hiện khi hover vào card. Khoảng 1–2 câu.')
                        ->rows(3),
                ]),

            // ─── TAB: PARTNER ─────────────────────────────────────────────
            Tab::make('Đối tác đồng hành')->placement('left'),

            Text::make('Tiêu đề', 'partner_heading')
                ->helperText('Ví dụ: Những Người Bạn Đồng Hành Tin Cậy')
                ->default('Những Người Bạn Đồng Hành Tin Cậy'),

            Repeater::make('Danh sách đối tác', 'partner_items')
                ->helperText('Upload logo SVG/PNG/WEBP cho từng đối tác.')
                ->layout('block')
                ->collapsed('logo')
                ->minRows(1)
                ->fields([
                    Image::make('Logo', 'logo')
                        ->helperText('Kích thước đề xuất: 260×100px. Định dạng SVG/PNG/WEBP.')
                        ->acceptedFileTypes(['svg', 'png', 'webp', 'jpg', 'jpeg'])
                        ->format('url'),
                ]),
        ],
    ], false);
}, 10);
