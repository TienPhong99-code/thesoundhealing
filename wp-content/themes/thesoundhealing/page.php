<?php
/**
 * The template for single post type is page
 * 
 * @author MONA.Media / Website
 */

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

get_header();

if (is_cart() || is_checkout() || is_account_page()) {
    the_content();
} else {
    ?>
    <?php mona_output_breadcrumb(); ?>

    <section class="sec-policy">
        <div class="policy ss-mg-b">
            <div class="container">
                <div class="mona-content">
                    <?php
                        if (get_the_content()) {
                            the_content();
                        } else {
                            esc_html_e('Nội dung sẽ sớm được cập nhật', 'monamedia');
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

get_footer();