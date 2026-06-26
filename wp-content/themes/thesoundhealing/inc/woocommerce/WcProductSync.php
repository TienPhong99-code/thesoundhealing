<?php
defined('ABSPATH') || exit;

class TSH_WC_Product_Sync {

    private const POST_TYPES = ['khoa_hoc', 'workshop', 'dich_vu'];
    public  const META_KEY   = '_wc_product_id';

    private const PRICE_FIELDS = [
        'khoa_hoc' => 'price',
        'workshop' => 'ws_price',
        'dich_vu'  => 'dv_price',
    ];

    public function __construct() {
        add_action('acf/save_post', [$this, 'sync'], 20);
    }

    public function sync($post_id): void {
        if (!is_numeric($post_id)) return;
        $post_id = (int) $post_id;
        $post    = get_post($post_id);
        if (!$post || !in_array($post->post_type, self::POST_TYPES, true)) return;
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
        if (!function_exists('wc_get_product')) return;

        $field     = self::PRICE_FIELDS[$post->post_type] ?? 'price';
        $price_raw = (string) (get_field($field, $post_id) ?: '');
        $price     = self::parse_price($price_raw);

        $wc_id = (int) get_post_meta($post_id, self::META_KEY, true);

        if ($wc_id && ($product = wc_get_product($wc_id))) {
            $this->update_product($product, $post, $price);
        } else {
            $wc_id = $this->create_product($post, $price);
            update_post_meta($post_id, self::META_KEY, $wc_id);
        }
    }

    public static function parse_price(string $raw): float {
        if (empty($raw) || mb_stripos($raw, 'liên hệ') !== false) return 0.0;
        $digits = preg_replace('/[^\d]/', '', $raw);
        return $digits !== '' ? (float) $digits : 0.0;
    }

    private function create_product(WP_Post $post, float $price): int {
        $product = new WC_Product_Simple();
        $product->set_name($post->post_title);
        $product->set_regular_price($price > 0 ? (string) $price : '');
        $product->set_virtual(true);
        $product->set_catalog_visibility('hidden');
        $product->set_status($post->post_status === 'publish' ? 'publish' : 'draft');
        return $product->save();
    }

    private function update_product(WC_Product $product, WP_Post $post, float $price): void {
        $product->set_name($post->post_title);
        $product->set_regular_price($price > 0 ? (string) $price : '');
        $product->set_status($post->post_status === 'publish' ? 'publish' : 'draft');
        $product->set_virtual(true);
        $product->set_catalog_visibility('hidden');
        $product->save();
    }
}

new TSH_WC_Product_Sync();
