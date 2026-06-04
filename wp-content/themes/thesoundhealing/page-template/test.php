<?php

/**
 * Template Name: test
 */

$products = wc_get_products([
    'limit' => 10,
    'page' => 4,
]);

foreach ($products as $product) {
    $gallery = $product->get_gallery_image_ids();
    if ($gallery) {
        update_field('mona_gallery', $gallery, $product->get_id());
    }
}
