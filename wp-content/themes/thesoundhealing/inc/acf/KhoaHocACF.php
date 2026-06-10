<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'Chi tiết khóa học',
        'style'    => 'default',
        'position' => 'normal',
        'location' => [
            Location::where('post_type', '==', 'khoa_hoc'),
        ],
        'fields' => [

            // ─── TAB: GIỚI THIỆU ─────────────────────────────────────────
            Tab::make('Giới thiệu')->placement('left'),

            Text::make('Cấp độ', 'level')
                ->helperText('Ví dụ: KHOÁ HỌC CHUYÊN SÂU, CƠ BẢN, CẤP ĐỘ 1'),

            Text::make('Ngày khai giảng', 'start_date')
                ->helperText('Ví dụ: 15 THÁNG 1, 2025'),

            Text::make('Thời lượng', 'duration')
                ->helperText('Ví dụ: 4 TUẦN, 2 NGÀY, CUỐI TUẦN'),

            Textarea::make('Mô tả ngắn', 'short_desc')
                ->helperText('Hiển thị trên card trang chủ và hero trang chi tiết.')
                ->rows(3),

            Text::make('Học phí', 'price')
                ->helperText('Ví dụ: 8.500.000 VNĐ'),

            // ─── TAB: TRẢI NGHIỆM ────────────────────────────────────────
            Tab::make('Trải nghiệm')->placement('left'),

            Text::make('Tiêu đề', 'exp_title')
                ->helperText('Ví dụ: Không gian của sự tĩnh lặng')
                ->default('Không gian của sự tĩnh lặng'),

            Textarea::make('Mô tả', 'exp_desc')
                ->helperText('Đoạn văn mô tả trải nghiệm khóa học.')
                ->rows(5),

            Image::make('Ảnh trải nghiệm 1', 'exp_image_1')
                ->helperText('Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh trải nghiệm 2', 'exp_image_2')
                ->helperText('Kích thước đề xuất: 300×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 4', 'gallery_4')
                ->helperText('Ảnh thứ 4 trong bộ gallery trang chi tiết. Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Image::make('Ảnh gallery 5', 'gallery_5')
                ->helperText('Ảnh thứ 5 trong bộ gallery trang chi tiết. Kích thước đề xuất: 600×300px.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            // ─── TAB: LỘ TRÌNH ───────────────────────────────────────────
            Tab::make('Lộ trình')->placement('left'),

            Text::make('Nhãn lộ trình', 'roadmap_label')
                ->helperText('Ví dụ: LỘ TRÌNH HỌC')
                ->default('LỘ TRÌNH HỌC'),

            Text::make('Tiêu đề lộ trình', 'roadmap_heading')
                ->helperText('Ví dụ: Hành trình 4 tuần')
                ->default('Hành trình 4 tuần'),

            Repeater::make('Các giai đoạn', 'roadmap_items')
                ->helperText('Mỗi giai đoạn gồm tiêu đề, mô tả và tag.')
                ->layout('block')
                ->collapsed('week_title')
                ->fields([
                    Text::make('Tiêu đề giai đoạn', 'week_title')
                        ->helperText('Ví dụ: Tuần 1: Căn nguyên của âm thanh')
                        ->required(),

                    Textarea::make('Mô tả', 'week_desc')
                        ->rows(3),

                    Text::make('Tags', 'week_tags')
                        ->helperText('Các tag cách nhau bởi dấu phẩy. Ví dụ: Lý thuyết tần số, Hệ thống luân xa'),
                ]),

            // ─── TAB: NGƯỜI HƯỚNG DẪN ────────────────────────────────────
            Tab::make('Người hướng dẫn')->placement('left'),

            Text::make('Nhãn', 'instructor_label')
                ->default('NGƯỜI HƯỚNG DẪN'),

            Image::make('Ảnh', 'instructor_image')
                ->helperText('Ảnh vuông 96×96px, bo tròn.')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                ->format('array'),

            Text::make('Tên', 'instructor_name')
                ->helperText('Ví dụ: Elena Vu'),

            Textarea::make('Giới thiệu', 'instructor_bio')
                ->rows(3),

            Text::make('Instagram', 'instructor_instagram')
                ->helperText('URL Instagram đầy đủ. Ví dụ: https://instagram.com/linhtam'),

            Text::make('Facebook', 'instructor_facebook')
                ->helperText('URL Facebook đầy đủ. Ví dụ: https://facebook.com/linhtam'),

            Text::make('WhatsApp', 'instructor_whatsapp')
                ->helperText('Số điện thoại hoặc link WhatsApp. Ví dụ: https://wa.me/84901234567'),

            Text::make('Facebook Messenger', 'instructor_messenger')
                ->helperText('Link Messenger. Ví dụ: https://m.me/linhtam'),

            // ─── TAB: LỢI ÍCH ────────────────────────────────────────────
            Tab::make('Lợi ích')->placement('left'),

            Text::make('Tiêu đề', 'benefits_heading')
                ->default('Bạn sẽ nhận được gì?'),

            Repeater::make('Danh sách lợi ích', 'benefits_items')
                ->layout('block')
                ->collapsed('benefit_title')
                ->fields([
                    Text::make('Tiêu đề', 'benefit_title')->required(),
                    Textarea::make('Mô tả', 'benefit_desc')->rows(2),
                ]),

            // ─── TAB: CẢM NHẬN ───────────────────────────────────────────
            Tab::make('Cảm nhận')->placement('left'),

            Text::make('Tiêu đề', 'feedbacks_heading')
                ->default('Cảm nhận của học viên'),

            Repeater::make('Hình ảnh học viên', 'feedbacks')
                ->helperText('Thêm hình ảnh học viên tham gia khóa học.')
                ->layout('table')
                ->fields([
                    Image::make('Hình ảnh', 'fb_image')
                        ->required()
                        ->helperText('Ảnh vuông, ví dụ 400×400px.')
                        ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp'])
                        ->format('array'),
                ]),

            // ─── TAB: CTA ────────────────────────────────────────────────
            Tab::make('CTA')->placement('left'),

            Text::make('Tiêu đề CTA', 'cta_title')
                ->default('Bắt đầu hành trình của bạn'),

            Textarea::make('Mô tả CTA', 'cta_desc')
                ->rows(2)
                ->default('Lớp học giới hạn số lượng học viên để đảm bảo chất lượng hướng dẫn tốt nhất. Vui lòng đăng ký sớm để giữ chỗ.'),
        ],
    ], false);
}, 10);
