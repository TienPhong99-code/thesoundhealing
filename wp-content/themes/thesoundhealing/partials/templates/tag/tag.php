<?php
/**
 * This is default template for categories
 * 
 * @author MONA.Media / Website
 */

if ( ! defined( 'ABSPATH' ) ) {
   die;
}
$current_term = get_queried_object();
$taxonomy = $current_term->taxonomy;

get_header();

echo '<h1 class="hide-sitename">' . $current_term->name . '</h1>';
?>
<section class="sec-homes-hint">
    <div class="homes-hint ss-mg">
        <div class="container">
            <div class="title-gr center mb-24">
                <h2 class="title title-36 add-class t-medium text-hori" data-spl="data-spl"
                    ><?php esc_html_e('Tất cả tin tức', 'monamedia'); ?></h2>
            </div>
            <div class="homes-hint__list row gap-res rows-3">
               <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col">
                            <?php get_template_part('partials/components/loops/item', 'post'); ?>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="col">
                        <p class="mona-empty">
                            <?php esc_html_e('Không tìm thấy bài viết nào', 'monamedia'); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <?php echo mona_pagination_links(); ?>
        </div>
    </div>
</section>
<?php
get_footer();
