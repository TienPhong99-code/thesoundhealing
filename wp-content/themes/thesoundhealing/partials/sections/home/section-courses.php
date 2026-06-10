<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'KHÓA HỌC NỔI BẬT',
    'heading' => 'Đào Tạo Chuyên Sâu',
    'link'    => ['url' => home_url('/khoa-hoc'), 'title' => 'XEM TẤT CẢ', 'target' => ''],
    'items'   => [
        [
            'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Hoà Âm 7 Chuông Pha Lê'],
            'term'       => 'Bộ Môn Âm Thanh',
            'level'      => 'Foundation',
            'title'      => 'Hoà Âm 7 Chuông Pha Lê',
            'desc'       => 'Nắm vững kỹ thuật chơi và hoà âm 7 luân xa với chuông pha lê, mang lại sự cân bằng sâu sắc cho cơ thể và tâm trí.',
            'start_date' => '15 THÁNG 2, 2025',
            'duration'   => '4 tuần · Cuối tuần',
            'instructor' => 'Linh Tâm',
            'price'      => '12.000.000 VNĐ',
            'url'        => '#',
        ],
        [
            'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Năng Lượng Usui Reiki Level 1'],
            'term'       => 'Bộ Môn Năng Lượng',
            'level'      => 'Cơ bản',
            'title'      => 'Năng Lượng Usui Reiki Level 1',
            'desc'       => 'Khởi đầu hành trình chữa lành bằng năng lượng vũ trụ. Học cách tự trị liệu và cân bằng các luân xa cơ bản.',
            'start_date' => '01 THÁNG 3, 2025',
            'duration'   => '2 ngày · Cuối tuần',
            'instructor' => 'Linh Tâm',
            'price'      => '3.800.000 VNĐ',
            'url'        => '#',
        ],
        [
            'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Liệu Pháp Sound Bath'],
            'term'       => 'Bộ Môn Âm Thanh',
            'level'      => 'Chuyên sâu',
            'title'      => 'Liệu Pháp Sound Bath',
            'desc'       => 'Đào tạo chuyên sâu kỹ năng tổ chức và dẫn dắt các buổi tắm âm thanh trị liệu chuyên nghiệp.',
            'start_date' => '15 THÁNG 3, 2025',
            'duration'   => '6 tuần · Cuối tuần',
            'instructor' => 'Linh Tâm',
            'price'      => '20.000.000 VNĐ',
            'url'        => '#',
        ],
    ],
];

$raw_link    = get_field('courses_link', $page_id);
$raw_objects = get_field('courses_items', $page_id); // mảng WP_Post objects

// Fallback: nếu admin chưa chọn → lấy 6 khóa học mới nhất từ CPT
if (empty($raw_objects)) {
    $raw_objects = get_posts([
        'post_type'      => 'khoa_hoc',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}

// Map WP_Post objects → format chuẩn
$acf_items = [];
foreach ($raw_objects as $post) {
    $thumb = get_the_post_thumbnail_url($post->ID, 'full');

    $terms     = get_the_terms($post->ID, 'bo_mon_khoa_hoc');
    $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $acf_items[] = [
        'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'term'       => $term_name,
        'level'      => get_field('level',           $post->ID),
        'title'      => $post->post_title,
        'desc'       => get_field('short_desc',      $post->ID),
        'start_date' => get_field('start_date',      $post->ID),
        'duration'   => get_field('duration',        $post->ID),
        'instructor' => get_field('instructor_name', $post->ID),
        'location'   => get_field('location',        $post->ID),
        'branch'     => get_field('kh_branch',       $post->ID),
        'status'     => get_field('kh_status',       $post->ID) ?: '',
        'price'      => get_field('price',           $post->ID),
        'spots'      => get_field('kh_spots',        $post->ID),
        'url'        => get_permalink($post->ID),
    ];
}

$data = [
    'label'   => get_field('courses_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('courses_heading', $page_id) ?: $sample['heading'],
    'link'    => $raw_link                              ?: $sample['link'],
    'items'   => $acf_items                            ?: $sample['items'],
];
?>

<section class="sec-courses relative section-pd-t">
    <span class="absolute inset-0 z-[-1]"></span>

    <div class="container">
        <!-- Header -->
        <div class="flex max-md:flex-col gap-4 items-center max-md:text-center md:items-end md:justify-between mb-4 md:mb-12">
            <div>
                <p class="text-pri text-[16px] font-semibold uppercase tracking-[1.2px] mb-4">
                    <?php echo esc_html($data['label']); ?>
                </p>
                <h2 class="font-title text-sec text-[32px] font-normal max-sm:text-[24px]">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
            </div>

            <?php if (!empty($data['link']['url'])) : ?>
                <a href="<?php echo esc_url($data['link']['url']); ?>"
                    target="<?php echo esc_attr($data['link']['target'] ?? ''); ?>"
                    class="flex items-center gap-1 text-pri text-[16px] font-semibold uppercase tracking-[1.2px] shrink-0">
                    <?php echo esc_html($data['link']['title'] ?: 'XEM TẤT CẢ'); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Cards -->
        <div class="row">
            <?php foreach ($data['items'] as $item) : ?>
                <div class="col col-5i max-lg:!w-1/2">
                    <?php get_template_part('partials/components/card-khoa-hoc', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>