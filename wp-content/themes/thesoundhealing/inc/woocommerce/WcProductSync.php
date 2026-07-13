<?php
defined('ABSPATH') || exit;

class TSH_WC_Product_Sync
{

    private const POST_TYPES = ['khoa_hoc', 'workshop', 'dich_vu'];
    public  const META_KEY   = '_wc_product_id';

    private const PRICE_FIELDS = [
        'khoa_hoc' => 'price',
        'workshop' => 'ws_price',
        'dich_vu'  => 'dv_price',
    ];

    public function __construct()
    {
        add_action('acf/save_post', [$this, 'sync'], 20);
    }

    public function sync($post_id): void
    {
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

        // WPML/WCML: mỗi ngôn ngữ 1 product RIÊNG (WCML chỉ hiện product đã dịch
        // trên trang ngôn ngữ tương ứng). Nếu meta đang trỏ product KHÔNG đúng
        // ngôn ngữ bài (vd bản dịch đang dùng chung product bài gốc do WPML copy
        // meta) → coi như chưa có → tạo product riêng cho bài này.
        if ($wc_id && !$this->product_matches_post_lang($wc_id, $post)) {
            $wc_id = 0;
        }

        // Guard chống DÙNG CHUNG: khi clone/duplicate bài, meta _wc_product_id bị
        // copy theo → bài mới trỏ product của bài GỐC (cùng ngôn ngữ nên lọt qua
        // check trên). Nếu product đang được bài KHÁC dùng → coi như chưa có →
        // tạo product riêng, tránh 2 bài ghi đè tên/giá lẫn nhau khi lưu.
        if ($wc_id && $this->is_shared_with_other_post($wc_id, $post_id)) {
            $wc_id = 0;
        }

        if ($wc_id && ($product = wc_get_product($wc_id))) {
            $this->update_product($product, $post, $price);
        } else {
            $wc_id = $this->create_product($post, $price);
            update_post_meta($post_id, self::META_KEY, $wc_id);
            // Gán ngôn ngữ WCML cho product mới; bài bản dịch → link product vào
            // cùng nhóm dịch (trid) với product của bài gốc.
            $this->register_product_language($post, $wc_id);
        }
    }

    /**
     * Mã ngôn ngữ WPML của một phần tử (product/CPT). Rỗng nếu WPML không biết.
     */
    private function element_lang(int $element_id, string $element_type): string
    {
        $lang = apply_filters('wpml_element_language_code', null, [
            'element_id'   => $element_id,
            'element_type' => $element_type,
        ]);
        return (string) $lang;
    }

    /**
     * Product $wc_id có cùng ngôn ngữ với bài $post không. WPML tắt → luôn true.
     */
    private function product_matches_post_lang(int $wc_id, WP_Post $post): bool
    {
        $default = apply_filters('wpml_default_language', null);
        if (!$default) return true; // WPML tắt

        $post_lang = $this->element_lang($post->ID, 'post_' . $post->post_type) ?: $default;
        $prod_lang = $this->element_lang($wc_id, 'post_product') ?: $default;
        return $post_lang === $prod_lang;
    }

    /**
     * Product $wc_id có đang được bài CPT KHÁC (khác $post_id) dùng làm
     * _wc_product_id không. Dùng để phát hiện clone/duplicate copy meta.
     * suppress_filters = true để không bị WPML lọc theo ngôn ngữ hiện tại.
     */
    private function is_shared_with_other_post(int $wc_id, int $post_id): bool
    {
        $others = get_posts([
            'post_type'        => self::POST_TYPES,
            'post_status'      => 'any',
            'meta_key'         => self::META_KEY,
            'meta_value'       => $wc_id,
            'post__not_in'     => [$post_id],
            'posts_per_page'   => 1,
            'fields'           => 'ids',
            'no_found_rows'    => true,
            'suppress_filters' => true,
        ]);
        return !empty($others);
    }

    /**
     * Gán ngôn ngữ WCML cho product mới theo ngôn ngữ của bài. Bài bản dịch →
     * dùng chung trid với product của bài GỐC để WCML nhận là bản dịch của nhau
     * (nhờ đó /en/ có product EN thật, đặt lịch được). No-op nếu WPML tắt.
     */
    private function register_product_language(WP_Post $post, int $wc_id): void
    {
        if (!$wc_id) return;
        $default = apply_filters('wpml_default_language', null);
        if (!$default) return; // WPML tắt

        $lang = $this->element_lang($post->ID, 'post_' . $post->post_type) ?: $default;

        $trid        = null;
        $source_lang = null;
        if ($lang !== $default) {
            // Bài là bản dịch: tìm product của bài gốc để dùng chung nhóm dịch.
            $orig_id = apply_filters('wpml_object_id', $post->ID, $post->post_type, true, $default);
            $orig_wc = $orig_id ? (int) get_post_meta($orig_id, self::META_KEY, true) : 0;
            if ($orig_wc) {
                $trid        = apply_filters('wpml_element_trid', null, $orig_wc, 'post_product');
                $source_lang = $default;
            }
        }

        do_action('wpml_set_element_language_details', [
            'element_id'           => $wc_id,
            'element_type'         => 'post_product',
            'trid'                 => $trid,        // null → product gốc, tạo trid mới
            'language_code'        => $lang,
            'source_language_code' => $source_lang, // null cho product gốc
        ]);
    }

    public static function parse_price(string $raw): float
    {
        if (empty($raw) || mb_stripos($raw, 'liên hệ') !== false) return 0.0;
        $digits = preg_replace('/[^\d]/', '', $raw);
        return $digits !== '' ? (float) $digits : 0.0;
    }

    private function create_product(WP_Post $post, float $price): int
    {
        $product = new WC_Product_Simple();
        $product->set_name($post->post_title);
        $product->set_regular_price($price > 0 ? (string) $price : '');
        $product->set_virtual(true);
        $product->set_catalog_visibility('hidden');
        $product->set_status($post->post_status === 'publish' ? 'publish' : 'draft');
        return $product->save();
    }

    private function update_product(WC_Product $product, WP_Post $post, float $price): void
    {
        $product->set_name($post->post_title);
        $product->set_regular_price($price > 0 ? (string) $price : '');
        $product->set_status($post->post_status === 'publish' ? 'publish' : 'draft');
        $product->set_virtual(true);
        $product->set_catalog_visibility('hidden');
        $product->save();
    }
}

new TSH_WC_Product_Sync();
