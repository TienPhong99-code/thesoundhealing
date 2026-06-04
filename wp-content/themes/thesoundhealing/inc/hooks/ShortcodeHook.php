<?php

use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\PostObject;
use Extended\ACF\Fields\Relationship;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('init', function () {
    mona_register_custom_post_type('mona_shortcode', 'Shortcode', [
        'show_in_menu' => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => ['title',],
        'publicly_queryable' => false,
    ]);
}, 10);

add_action('acf/init', function () {
    $fields = [];

    if (isset($_GET['post'])) {
        $fields[] = Text::make('Shortcode')
            ->default('[mona_shortcode id="' . sanitize_text_field($_GET['post']) . '"]')
            ->readOnly();
    }

    $fields[] = Select::make('Loại', 'type')
        ->choices([
            'product' => 'Sản phẩm',
            'list_products' => 'Danh sách sản phẩm',
        ])
        ->format('value');

    $fields[] = PostObject::make('Sản phẩm', 'product')
        ->conditionalLogic([
            ConditionalLogic::where('type', '==', 'product'),
        ])
        ->format('id')
        ->postStatus(['publish'])
        ->postTypes(['product']);

    $fields[] = Relationship::make('Sản phẩm', 'products')
        ->conditionalLogic([
            ConditionalLogic::where('type', '==', 'list_products'),
        ])
        ->postTypes(['product'])
        ->postStatus(['publish'])
        ->format('id')
        ->filters([
            'search',
            'taxonomy',
        ]);

    register_extended_field_group([
        'title' => 'Shortcode settings',
        'style' => 'default',
        'position' => 'acf_after_title',
        'location' => [
            Location::where('post_type', '==', 'mona_shortcode'),
        ],
        'fields' => $fields,
    ]);
}, 10);

add_shortcode('mona_shortcode', function (array $attrs) {
    global $product;
    $main_product = $product;

    if (! isset($attrs['id'])) return '';

    $type = get_field('type', $attrs['id']);
    if (! $type) return '';

    switch ($type) {
        case 'list_products':
            $list_products = get_field('products', $attrs['id']);

            if (
                empty($list_products)
                || ! is_array($list_products)
            ) return '';

            $products = wc_get_products([
                'type'  => ['simple', 'variable'],
                'status' => 'publish',
                'limit' => count($list_products),
                'include' => array_map('intval', $list_products),
                'orderby' => 'include',
            ]);

            if (empty($products)) return '';

            ob_start();
            ?>
            <div class="shortcode">
                <div class="prod-slide__swiper fluid-slide relative">
                    <div class="swiper row gap-res">
                        <div class="swiper-wrapper">
                            <?php foreach ($products as $p) :
                                if (! $p->is_visible()) continue;

                                $product = $p;
                                setup_postdata($product->get_id()); ?>
                                <div class="swiper-slide col">
                                    <?php wc_get_template_part('content', 'product'); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="swiper-control posi minus">
                        <div class="swiper-control-btn swiper-prev"> <i class="fa-solid fa-arrow-left"></i></div>
                        <div class="swiper-control-btn swiper-next"> <i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                </div>
            </div>
            <?php
            wp_reset_postdata();
            $product = $main_product;
            return ob_get_clean();

        case 'product':
            $prod = get_field('product', $attrs['id']);
            if (empty($prod)) return '';

            $prod = wc_get_product((int) $prod);
            if (empty($prod)) return '';

            $is_variable = $prod->is_type('variable');
            $link_shopee = get_field('link_shopee', $prod->get_id());

            ob_start();
            ?>
            <div class="shortcode mona-short-product <?php echo $is_variable ? 'variable' : 'simple' ?>">
                <div class="prod-dt__box">
                    <div class="inner">
                        <div class="img">
                            <?php if ($prod->is_on_sale()) : ?>
                                <div class="img-tags">
                                    <p class="img-tag">
                                        <?php
                                            if ($prod->is_type('variable')) {
                                                esc_html_e('Giảm giá', 'monamedia');
                                            } else {
                                                echo sprintf(
                                                    __('Giảm %d%%', 'monamedia'), 
                                                    mona_calc_discount_percent($prod->get_regular_price(), $prod->get_sale_price())
                                                );
                                            }
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <div class="img-inner">
                                <?php echo $prod->get_image('full'); ?>
                            </div>
                        </div>
                        <div class="info">
                            <p class="info-tt"><?php echo $prod->get_name(); ?></p>
                            <?php if (! $prod->is_in_stock() || ! $prod->is_purchasable()) : ?>
                                <div class="box-price">
                                    <span class="price"><?php esc_html_e('Hết hàng', 'monamedia'); ?></span>
                                </div>
                            <?php else : ?>
                                <div class="box-price">
                                    <?php echo $prod->get_price_html(); ?>
                                </div>

                                <div class="info-variations">
                                    <?php
                                        if ($is_variable) {
                                            $attributes = $prod->get_variation_attributes();

                                            if (! empty($attributes)) {
                                                foreach ($attributes as $attribute_name => $options) {
                                                    $label = wc_attribute_label($attribute_name);
                                                    ?>
                                                    <div class="info-variation-sl">
                                                        <span class="label"><?php printf(esc_html__('Chọn %s:', 'monamedia'), esc_html($label)); ?></span>
                                                        <div class="selectNor">
                                                            <select class="re-select-main" name="attribute_<?php echo $attribute_name ?>" data-placeholder="<?php printf(esc_attr__('Chọn %s', 'monamedia'), esc_attr($label)); ?>">
                                                                <option value=""><?php printf(esc_html__('Chọn %s', 'monamedia'), esc_html($label)); ?></option>

                                                                <?php
                                                                    foreach ($options as $option) {
                                                                        $term = get_term_by('slug', $option, $attribute_name);

                                                                        echo '<option value="' . $option . '">' . esc_html($term->name) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>

                                    <div class="info-variation-sl">
                                        <span class="label"><?php esc_html_e('Chọn số lượng:', 'monamedia'); ?></span>
                                        <div class="count">
                                            <button type="button" class="count-btn count-minus"> <i class="fas fa-minus icon"></i></button>
                                            <?php
                                                $qty = $prod->get_min_purchase_quantity();
                                                woocommerce_quantity_input(
                                                    array(
                                                        'min_value'   => apply_filters('woocommerce_quantity_input_min', $prod->get_min_purchase_quantity(), $prod),
                                                        'max_value'   => apply_filters('woocommerce_quantity_input_max', $prod->get_max_purchase_quantity(), $prod),
                                                        'input_value' => $qty, // WPCS: CSRF ok, input var ok.
                                                    )
                                                );
                                            ?>
                                            <p class="count-number"><?php echo $qty; ?></p>
                                            <button type="button" class="count-btn count-plus"> <i class="fas fa-plus icon"></i></button>
                                        </div>

                                    </div>
                                </div>
                                <div class="info-action">
                                    <div class="flex row">
                                        <div class="col">
                                            <button class="mona-shortcode-btn-buynow btn pri no-rds full" type="submit" name="buy-now" 
                                                value="<?php echo $prod->get_id(); ?>" 
                                                disabled>
                                                <span class="txt">
                                                    <span class="txt-inner"><?php esc_html_e('Mua ngay', 'monamedia'); ?> </span>
                                                    <span class="txt-icon"><i class="fa-solid fa-chevron-right"></i></span>
                                                </span>
                                            </button>
                                        </div>

                                        <div class="col">
                                            <div class="btn-box">
                                                <button class="mona-shortcode-btn-cart btn trans no-rds full" name="add-to-cart" type="button" disabled>
                                                    <span class="txt"><?php esc_html_e('Thêm vào giỏ hàng', 'monamedia'); ?></span>
                                                </button>
                                            </div>
                                        </div>

                                        <?php if (! empty($link_shopee)) : ?>
                                            <div class="col">
                                                <div class="btn-box">
                                                    <a class="btn trans rev no-rds full" href="<?php echo esc_url($link_shopee); ?>">
                                                        <span class="txt">
                                                            <span class="txt-inner"><?php esc_html_e('Mua qua Shopee', 'monamedia'); ?> </span>
                                                            <span class="txt-icon">
                                                                <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-shopee.svg" alt="image" loading="lazy" />
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php
                                    if ($is_variable) {
                                        $available_variations = count( $prod->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $prod );
                                        $available_variations = $available_variations ? $prod->get_available_variations() : false;
                                        $variations_json = wp_json_encode( $available_variations );
                                        $variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
                                        echo '<input type="hidden" name="product_variations" value=\'' . $variations_attr . '\' />';
                                    }
                                ?>

                                <input type="hidden" name="product_id" value="<?php echo $prod->get_id(); ?>" />
                                <input type="hidden" name="variation_id" value="0" />
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();

        default:
            return '';
    }
});
