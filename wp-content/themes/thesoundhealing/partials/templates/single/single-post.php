<?php

/**
 * This is template for single post
 *
 * @author MONA.Retail / Website
 */

defined('ABSPATH') || exit;

if (! function_exists('kiena_time_ago')) {
    function kiena_time_ago(int $post_id): string
    {
        return human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) . ' trước';
    }
}

$post_id       = get_the_ID();
$permalink     = get_the_permalink();
$link_encoded  = urlencode($permalink);
$title_encoded = urlencode(get_the_title());

// Related posts — same category
$cats = wp_get_post_categories($post_id);
$related_query = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 5,
    'post__not_in'   => [$post_id],
    'orderby'        => 'date',
    'order'          => 'DESC',
    'category__in'   => $cats ?: [],
]);

get_header();

$tin_tuc_page = get_page_by_path('tin-tuc');
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => __('Trang chủ', 'monamedia'), 'url' => home_url('/'), 'is-active' => false],
        ['title' => __('Tin tức', 'monamedia'), 'url' => $tin_tuc_page ? get_permalink($tin_tuc_page) : home_url('/tin-tuc'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '', 'is-active' => true],
    ],
]); ?>

<section class="single-tin-tuc relative py-8">
    <div class="container">
        <div class="row">

            <!-- ── Left: Mục lục (Easy TOC plugin) ── -->
            <!-- <div class="col col-3 max-lg:hidden">
                <div class="sticky top-24">
                    <?php echo do_shortcode('[ez-toc]'); ?>
                </div>
            </div> -->

            <!-- ── Center: Nội dung bài viết ── -->
            <div class="col col-8 max-lg:w-full!">

                <!-- Tiêu đề -->
                <h1 class="text-sec text-[48px] font-bold leading-normal mb-2 max-md:text-[24px]">
                    <?php the_title(); ?>
                </h1>

                <!-- Share row -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <p class="text-[#121214] text-[14px]"><?php esc_html_e('Hoặc chia sẻ ngay:', 'monamedia'); ?></p>
                        <div class="flex items-center gap-2">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $link_encoded; ?>"
                                target="_blank" rel="noopener"
                                class="w-6 h-6 rounded-full bg-[#028fe3] overflow-hidden flex items-center justify-center shrink-0"
                                aria-label="Chia sẻ lên Facebook">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-share-facebook.svg"
                                    class="w-full h-full object-contain" alt="">
                            </a>
                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $link_encoded; ?>"
                                target="_blank" rel="noopener"
                                class="w-6 h-6 shrink-0"
                                aria-label="Chia sẻ lên LinkedIn">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-share-linkedin.svg"
                                    class="w-full h-full object-contain" alt="">
                            </a>
                            <!-- Twitter / X -->
                            <a href="https://x.com/intent/tweet?url=<?php echo $link_encoded; ?>&text=<?php echo $title_encoded; ?>"
                                target="_blank" rel="noopener"
                                class="w-6 h-6 shrink-0"
                                aria-label="Chia sẻ lên Twitter">
                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-twitter.svg"
                                    class="w-full h-full object-contain" alt="">
                            </a>
                        </div>
                    </div>
                    <p class="text-[#ababab] text-[12px] whitespace-nowrap">
                        <?php echo kiena_time_ago($post_id); ?>
                    </p>
                </div>

                <!-- Ảnh đại diện -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="relative rounded-[5px] overflow-hidden bg-pri aspect-600/389 mb-8">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>"
                            class="absolute inset-0 w-full h-full object-cover" alt="">
                    </div>
                <?php endif; ?>

                <!-- Nội dung bài viết -->
                <div class="article-content text-[#121214] text-[16px] leading-normal">
                    <div class="mona-content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Tác giả -->
                <!-- <div class="flex items-center gap-3 mt-8 pt-6 border-t border-[#e8e8e8]">
                    <?php $author_avatar = get_avatar_url(get_the_author_meta('ID'), ['size' => 48]); ?>
                    <?php if ($author_avatar) : ?>
                        <img src="<?php echo esc_url($author_avatar); ?>"
                            class="w-12 h-12 rounded-full object-cover shrink-0" alt="">
                    <?php endif; ?>
                    <div>
                        <p class="text-[12px] text-[#ababab]">Tác giả</p>
                        <p class="text-[15px] font-semibold text-[#121214]">
                            <?php echo esc_html(get_the_author()); ?>
                        </p>
                    </div>
                </div> -->

            </div>

            <!-- ── Right: Có thể bạn quan tâm ── -->
            <div class="col col-4 max-xl:hidden">
                <div class="sticky top-24">
                    <p class="text-pri text-[20px] font-bold mb-4"><?php esc_html_e('Có thể bạn quan tâm', 'monamedia'); ?></p>

                    <?php if ($related_query->have_posts()) : ?>
                        <div class="flex flex-col gap-4">
                            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                <div class="relative group flex gap-3 items-center">
                                    <!-- Thumbnail -->
                                    <div class="relative rounded-lg overflow-hidden shrink-0 w-37 h-24">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'thumbnail')); ?>"
                                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="">
                                        <?php endif; ?>
                                    </div>
                                    <!-- Meta -->
                                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                                        <p class="text-pri text-[16px] font-bold group-hover:text-sec transition-colors line-clamp-3">
                                            <?php the_title(); ?>
                                        </p>
                                        <p class="text-[#ababab] text-[12px]">
                                            <?php echo kiena_time_ago(get_the_ID()); ?>
                                        </p>
                                    </div>
                                    <a href="<?php the_permalink(); ?>"
                                        class="absolute inset-0 z-1"
                                        aria-label="<?php the_title_attribute(); ?>"></a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_template_part('partials/sections/tin-tuc/tin-tuc-slide'); ?>

<?php get_footer(); ?>