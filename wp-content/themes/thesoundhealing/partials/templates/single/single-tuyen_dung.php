<?php

/**
 * Template for single: Tuyển dụng chi tiết
 *
 * @author MONA.Retail / Website
 */

defined('ABSPATH') || exit;

$post_id = get_the_ID();

$time_ago_vi = function ($timestamp) {
    $diff = current_time('timestamp') - $timestamp;
    if ($diff < 60)       return 'vừa xong';
    if ($diff < 3600)     return floor($diff / 60) . ' phút trước';
    if ($diff < 86400)    return floor($diff / 3600) . ' giờ trước';
    if ($diff < 2592000)  return floor($diff / 86400) . ' ngày trước';
    if ($diff < 31536000) return floor($diff / 2592000) . ' tháng trước';
    return floor($diff / 31536000) . ' năm trước';
};

// Other jobs: ưu tiên cùng danh mục, fallback về mới nhất nếu không đủ 3
$related       = [];
$related_ids   = [];
$term_ids      = wp_get_post_terms($post_id, 'danh_muc_tuyen_dung', ['fields' => 'ids']);

if (!empty($term_ids) && !is_wp_error($term_ids)) {
    $cat_query = new WP_Query([
        'post_type'      => 'tuyen_dung',
        'posts_per_page' => 3,
        'post__not_in'   => [$post_id],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [[
            'taxonomy' => 'danh_muc_tuyen_dung',
            'field'    => 'term_id',
            'terms'    => $term_ids,
        ]],
    ]);
    while ($cat_query->have_posts()) {
        $cat_query->the_post();
        $related_ids[]  = get_the_ID();
        $related[] = [
            'title'    => get_the_title(),
            'location' => get_field('td_location') ?: '',
            'salary'   => get_field('td_salary')   ?: '',
            'type'     => get_field('td_work_type') ?: '',
            'posted'   => 'Đã đăng ' . $time_ago_vi(get_the_time('U')),
            'url'      => get_permalink(),
        ];
    }
    wp_reset_postdata();
}

// Fallback: bổ sung bài mới nhất nếu chưa đủ 3
if (count($related) < 3) {
    $exclude   = array_merge([$post_id], $related_ids);
    $fill_query = new WP_Query([
        'post_type'      => 'tuyen_dung',
        'posts_per_page' => 3 - count($related),
        'post__not_in'   => $exclude,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    while ($fill_query->have_posts()) {
        $fill_query->the_post();
        $related[] = [
            'title'    => get_the_title(),
            'location' => get_field('td_location') ?: '',
            'salary'   => get_field('td_salary')   ?: '',
            'type'     => get_field('td_work_type') ?: '',
            'posted'   => 'Đã đăng ' . $time_ago_vi(get_the_time('U')),
            'url'      => get_permalink(),
        ];
    }
    wp_reset_postdata();
}

$data = [
    'status'        => get_field('td_status')        ?: 'open',
    'title'         => get_the_title(),
    'salary'        => get_field('td_salary')        ?: '',
    'work_type'     => get_field('td_work_type')     ?: '',
    'posted'        => 'Đã đăng ' . $time_ago_vi(get_the_time('U')),
    'location'      => get_field('td_location')      ?: '',
    'apply_url'     => get_field('td_apply_url')     ?: '#',
    'mo_ta'         => get_field('td_mo_ta')         ?: '',
    'yc_chuyen_mon' => get_field('td_yc_chuyen_mon') ?: '',
    'yc_ky_nang'    => get_field('td_yc_ky_nang')    ?: '',
    'quyen_loi'     => get_field('td_quyen_loi')     ?: '',
    'related'       => $related,
];

get_header();

$tuyen_dung_page = get_page_by_path('tuyen-dung');
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => __('Trang chủ', 'monamedia'), 'url' => home_url('/'), 'is-active' => false],
        ['title' => __('Tuyển dụng', 'monamedia'), 'url' => $tuyen_dung_page ? get_permalink($tuyen_dung_page) : home_url('/tuyen-dung'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '', 'is-active' => true],
    ],
]); ?>

<section class="section-tuyen-dung-chi-tiet py-(--pd-sc)">
    <div class="container">
        <div class="flex gap-8 max-xl:flex-col">

            <!-- ── MAIN CONTENT ── -->
            <div class="flex-1 min-w-0 flex flex-col gap-8 mb-8">

                <!-- Header: badge + title -->
                <div class="flex flex-col">
                    <span class="inline-block self-start px-3 rounded-sm py-1  font-bold text-white <?php echo $data['status'] === 'open' ? 'bg-[#f14950]' : 'bg-[#808080]'; ?>">
                        <?php echo $data['status'] === 'open' ? esc_html__('ĐANG TUYỂN', 'monamedia') : esc_html__('ĐÃ TUYỂN', 'monamedia'); ?>
                    </span>
                    <h1 class="text-[40px] max-lg:text-[28px] max-md:text-[22px] font-bold text-pri uppercase leading-normal">
                        <?php echo esc_html($data['title']); ?>
                    </h1>
                </div>

                <!-- Ảnh đại diện -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="relative overflow-hidden aspect-805/345 w-full rounded-lg">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>"
                            class="absolute inset-0 w-full h-full object-cover" alt="">
                    </div>
                <?php endif; ?>

                <!-- Meta info box -->
                <div class="bg-[#f4f5f8] p-6 max-md:p-5 flex flex-col gap-5 rounded-xl">
                    <div class="grid grid-cols-2 gap-5 max-sm:grid-cols-1">

                        <!-- Mức lương -->
                        <div class="flex gap-4 max-sm:gap-3">
                            <div class="w-11 h-11 max-sm:w-9 max-sm:h-9 shrink-0 bg-pri flex items-center justify-center rounded-full">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-coin.svg"
                                    class="w-5 h-5 max-sm:w-4 max-sm:h-4 brightness-0 invert" alt="">
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class=" text-[#818181]"><?php esc_html_e('Mức lương:', 'monamedia'); ?></span>
                                <span class="text-[16px] font-semibold text-pri"><?php echo esc_html($data['salary']); ?></span>
                            </div>
                        </div>

                        <!-- Loại hình -->
                        <div class="flex gap-4 max-sm:gap-3">
                            <div class="w-11 h-11 max-sm:w-9 max-sm:h-9 shrink-0 bg-pri flex items-center justify-center rounded-full">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-time.svg"
                                    class="w-5 h-5 max-sm:w-4 max-sm:h-4 brightness-0 invert" alt="">
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class=" text-[#818181]"><?php esc_html_e('Loại hình công việc:', 'monamedia'); ?></span>
                                <span class="text-[16px] font-semibold text-pri"><?php echo esc_html($data['work_type']); ?></span>
                            </div>
                        </div>

                        <!-- Ngày đăng -->
                        <div class="flex gap-4 max-sm:gap-3">
                            <div class="w-11 h-11 max-sm:w-9 max-sm:h-9 shrink-0 bg-pri flex items-center justify-center rounded-full">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-time.svg"
                                    class="w-5 h-5 max-sm:w-4 max-sm:h-4 brightness-0 invert" alt="">
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class=" text-[#818181]"><?php esc_html_e('Ngày đăng:', 'monamedia'); ?></span>
                                <span class="text-[16px] font-semibold text-pri"><?php echo esc_html($data['posted']); ?></span>
                            </div>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="flex gap-4 max-sm:gap-3">
                            <div class="w-11 h-11 max-sm:w-9 max-sm:h-9 shrink-0 bg-pri flex items-center justify-center rounded-full">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-pin.svg"
                                    class="w-5 h-5 max-sm:w-4 max-sm:h-4 brightness-0 invert" alt="">
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class=" text-[#818181]"><?php esc_html_e('Địa chỉ:', 'monamedia'); ?></span>
                                <span class="text-[16px] font-semibold text-pri leading-normal"><?php echo esc_html($data['location']); ?></span>
                            </div>
                        </div>

                    </div>

                    <!-- CTA -->
                    <button type="button" data-modal-open="ung-tuyen"
                        data-vi-tri="<?php echo esc_attr($data['title']); ?>"
                        class="btn btn-white text-center justify-center w-full">
                        <span><?php esc_html_e('Ứng tuyển ngay', 'monamedia'); ?></span>
                    </button>
                </div>

                <!-- Mô tả công việc -->
                <div class="flex flex-col">
                    <h2 class="text-[20px] font-bold text-pri"><?php esc_html_e('Mô tả công việc', 'monamedia'); ?></h2>
                    <div class="text-[16px] text-pri leading-relaxed whitespace-pre-line">
                        <?php echo esc_html($data['mo_ta']); ?>
                    </div>
                </div>

                <hr class="border-t border-[#d9d9d9]">

                <!-- Yêu cầu ứng viên -->
                <div class="flex flex-col gap-5">
                    <h2 class="text-[20px] font-bold text-pri"><?php esc_html_e('Yêu cầu ứng viên', 'monamedia'); ?></h2>
                    <div class="flex flex-col">
                        <h3 class="text-[16px] font-bold text-pri"><?php esc_html_e('Yêu cầu về chuyên môn', 'monamedia'); ?></h3>
                        <div class="text-[16px] text-pri leading-relaxed whitespace-pre-line">
                            <?php echo esc_html($data['yc_chuyen_mon']); ?>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-[16px] font-bold text-pri"><?php esc_html_e('Yêu cầu về kỹ năng', 'monamedia'); ?></h3>
                        <div class="text-[16px] text-pri leading-relaxed whitespace-pre-line">
                            <?php echo esc_html($data['yc_ky_nang']); ?>
                        </div>
                    </div>
                </div>

                <hr class="border-t border-[#d9d9d9]">

                <!-- Quyền lợi -->
                <div class="flex flex-col">
                    <h2 class="text-[20px] font-bold text-pri"><?php esc_html_e('Quyền lợi được hưởng', 'monamedia'); ?></h2>
                    <div class="text-[16px] text-pri leading-relaxed whitespace-pre-line">
                        <?php echo esc_html($data['quyen_loi']); ?>
                    </div>
                </div>

            </div>

            <!-- ── SIDEBAR: Có thể bạn quan tâm ── -->
            <!-- <div class="w-[384px] shrink-0 max-xl:w-full">
                <div class="sticky top-24 flex flex-col gap-4 sidebar-td">

                    <h2 class="text-[20px] font-bold text-pri">Có thể bạn quan tâm</h2>

                    <?php if (empty($data['related'])) : ?>
                        <div class="flex items-center justify-center py-16 bg-gray-100 rounded-xl">
                            <p class="text-[16px] text-[#808080]">Hiện chưa có vị trí tuyển dụng nào.</p>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($data['related'] as $job) : ?>
                        <?php get_template_part('partials/components/card-tuyen-dung', null, $job); ?>
                    <?php endforeach; ?>

                </div>
            </div> -->

        </div>
    </div>
</section>

<?php
get_template_part('partials/sections/tuyen-dung/section-vi-tri-lien-quan', null, [
    'jobs' => $data['related'],
]);
?>

<?php get_footer(); ?>