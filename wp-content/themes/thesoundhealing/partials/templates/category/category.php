<?php
/**
 * This is default template for categories
 * 
 * @author MONA.Media / Website
 */

if ( ! defined( 'ABSPATH' ) ) {
   die;
}
global $mona_current_permalink;

$current_term = get_queried_object();
$taxonomy = $current_term->taxonomy;

$no_children = false;
$categories = get_terms([
   'taxonomy' => 'category',
   'hide_empty' => true,
   'parent' => $current_term->term_id,
   'fields' => 'id=>name',
]);

if (! $categories || is_wp_error($categories)) {
   $no_children = true;
   $categories = get_terms([
      'taxonomy' => 'category',
      'hide_empty' => true,
      'parent' => $current_term->parent,
      'fields' => 'id=>name',
   ]);
}

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
            <?php
                if ($categories && ! is_wp_error($categories)) {
                    $blog_permalink = get_permalink(MONA_PAGE_BLOG);
                    ?>
                    <div class="homes-hint__filter">
                        <?php if ($no_children && $current_term->parent == 0) : ?>
                           <a class="homes-hint__filter-link <?php echo $blog_permalink == $mona_current_permalink ? 'active' : ''; ?>" href="<?php echo $blog_permalink; ?>"
                              ><?php esc_html_e('Tất cả', 'monamedia'); ?></a>
                        <?php endif; ?>

                        <?php foreach ($categories as $cat_id => $cat_nm) : 
                            $permalink = get_term_link($cat_id);
                            ?>
                            <a class="homes-hint__filter-link <?php echo $permalink == $mona_current_permalink ? 'active' : ''; ?>"
                                href="<?php echo $permalink; ?>">
                                <?php echo esc_html($cat_nm); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php
                }
            ?>
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
