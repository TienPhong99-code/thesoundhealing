<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\PostObject;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
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

            // ─── TAB: SỰ KIỆN NỔI BẬT ────────────────────────────────────
            Tab::make('Sự kiện nổi bật')->placement('left'),

            Text::make('Nhãn nhỏ (label)', 'featured_label')
                ->helperText('Ví dụ: SỰ KIỆN NỔI BẬT')
                ->default('SỰ KIỆN NỔI BẬT'),

            Text::make('Tiêu đề', 'featured_heading')
                ->helperText('Ví dụ: Các Sự Kiện Nổi Bật')
                ->default('Các Sự Kiện Nổi Bật'),

            Link::make('Link "Xem tất cả"', 'featured_link')
                ->helperText('Link dẫn đến trang tổng hợp sự kiện.'),

            PostObject::make('Chọn sự kiện nổi bật', 'featured_items')
                ->helperText('Chọn các dịch vụ, khóa học hoặc workshop muốn hiển thị. Kéo thả để sắp xếp thứ tự.')
                ->postTypes(['dich_vu', 'khoa_hoc', 'workshop'])
                ->multiple()
                ->format('object'),

            // ─── TAB: HERO ────────────────────────────────────────────────
            Tab::make('Hero')->placement('left'),

            Image::make('Ảnh nền hero', 'hero_image')
                ->helperText('Kích thước đề xuất: 1440×819px. Định dạng JPG/PNG/WEBP.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Text::make('Tiêu đề', 'hero_heading')
                ->helperText('Ví dụ: Đánh Thức Sự Hài Hòa Bên Trong'),

            Textarea::make('Mô tả ngắn', 'hero_desc')
                ->helperText('1–2 câu giới thiệu hiển thị dưới tiêu đề.')
                ->rows(3),

            Text::make('Text nút CTA', 'hero_btn_text')
                ->helperText('Ví dụ: KHÁM PHÁ KHÓA HỌC')
                ->default('KHÁM PHÁ KHÓA HỌC'),

            Text::make('URL nút CTA', 'hero_btn_url')
                ->helperText('Link trang khóa học'),

            // ─── TAB: ABOUT ───────────────────────────────────────────────
            Tab::make('Giới thiệu')->placement('left'),

            Image::make('Ảnh giới thiệu', 'about_image')
                ->helperText('Ảnh chân dung/không gian — kích thước đề xuất: 560×700px (tỷ lệ 4:5).')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Text::make('Nhãn nhỏ (label)', 'about_label')
                ->helperText('Chữ in hoa nhỏ bên trên tiêu đề. Ví dụ: VỀ CHÚNG TÔI')
                ->default('VỀ CHÚNG TÔI'),

            Textarea::make('Tiêu đề', 'about_heading')
                ->helperText('Tiêu đề lớn phần giới thiệu.')
                ->rows(2),

            Textarea::make('Mô tả', 'about_desc')
                ->helperText('Đoạn văn giới thiệu chi tiết.')
                ->rows(5),

            Repeater::make('Điểm nổi bật', 'about_items')
                ->helperText('Tối đa 3 điểm nổi bật, mỗi điểm gồm icon + tiêu đề + mô tả ngắn.')
                ->layout('block')
                ->collapsed('title')
                ->minRows(1)
                ->maxRows(3)
                ->fields([
                    Image::make('Icon SVG', 'icon')
                        ->helperText('Icon 20×20px định dạng SVG.')
                        ->acceptedFileTypes(['svg', 'png'])
                        ->format('url'),

                    Text::make('Tiêu đề điểm nổi bật', 'title')
                        ->required(),

                    Text::make('Mô tả ngắn', 'desc'),
                ]),

            // ─── TAB: COURSES ─────────────────────────────────────────────
            Tab::make('Khóa học nổi bật')->placement('left'),

            Text::make('Nhãn nhỏ (label)', 'courses_label')
                ->helperText('Ví dụ: KHÓA HỌC NỔI BẬT')
                ->default('KHÓA HỌC NỔI BẬT'),

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

            // ─── TAB: CTA ─────────────────────────────────────────────────
            Tab::make('CTA')->placement('left'),

            Text::make('Tiêu đề', 'cta_heading')
                ->helperText('Ví dụ: Bắt Đầu Hành Trình Chuyển Hoá Của Bạn')
                ->default('Bắt Đầu Hành Trình Chuyển Hoá Của Bạn'),

            Textarea::make('Mô tả', 'cta_desc')
                ->helperText('1–2 câu kêu gọi hiển thị dưới tiêu đề.')
                ->rows(3),

            Text::make('Nút chính — text', 'cta_btn_primary_text')
                ->helperText('Ví dụ: ĐĂNG KÝ NGAY')
                ->default('ĐĂNG KÝ NGAY'),

            Text::make('Nút chính — URL', 'cta_btn_primary_url')
                ->helperText('Link trang đăng ký'),

            Text::make('Nút phụ — text', 'cta_btn_secondary_text')
                ->helperText('Ví dụ: TƯ VẤN MIỄN PHÍ')
                ->default('TƯ VẤN MIỄN PHÍ'),

            Text::make('Nút phụ — URL', 'cta_btn_secondary_url')
                ->helperText('Link trang tư vấn / liên hệ'),

            // ─── TAB: GALLERY ─────────────────────────────────────────────
            Tab::make('Học viên & Trải nghiệm')->placement('left'),

            Text::make('Nhãn nhỏ (label)', 'gallery_label')
                ->helperText('Ví dụ: HỌC VIÊN & TRẢI NGHIỆM')
                ->default('HỌC VIÊN & TRẢI NGHIỆM'),

            Text::make('Tiêu đề', 'gallery_heading')
                ->helperText('Ví dụ: Khoảnh Khắc Tại Aetheria')
                ->default('Khoảnh Khắc Tại Aetheria'),

            Textarea::make('Mô tả', 'gallery_desc')
                ->helperText('1–2 câu mô tả hiển thị dưới tiêu đề.')
                ->rows(2),

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

            // ─── TAB: PARTNER ─────────────────────────────────────────────
            Tab::make('Đối tác đồng hành')->placement('left'),

            Text::make('Nhãn nhỏ (label)', 'partner_label')
                ->helperText('Ví dụ: ĐỐI TÁC ĐỒNG HÀNH')
                ->default('ĐỐI TÁC ĐỒNG HÀNH'),

            Text::make('Tiêu đề', 'partner_heading')
                ->helperText('Ví dụ: Những Người Bạn Đồng Hành Tin Cậy')
                ->default('Những Người Bạn Đồng Hành Tin Cậy'),

            Repeater::make('Danh sách đối tác', 'partner_items')
                ->helperText('Mỗi đối tác gồm logo (tùy chọn), tên và đường dẫn.')
                ->layout('block')
                ->collapsed('name')
                ->minRows(1)
                ->fields([
                    Image::make('Logo', 'logo')
                        ->helperText('Upload logo (SVG/PNG/WEBP). Nếu để trống, tên văn bản sẽ được hiển thị thay thế.')
                        ->acceptedFileTypes(['svg', 'png', 'webp', 'jpg', 'jpeg'])
                        ->format('url'),

                    Text::make('Tên đối tác', 'name')
                        ->helperText('Hiện khi không có logo. Ví dụ: ZenFlow'),

                    Text::make('URL liên kết', 'url')
                        ->helperText('Đường dẫn trang web đối tác. Bỏ trống nếu không cần link.'),
                ]),
        ],
    ], false);
}, 10);
