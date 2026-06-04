<?php
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\RadioButton;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\URL;

defined('ABSPATH') || exit;

/**
 * Convert text tel to link
 * 
 * @param string $hotline
 * 
 * @return string
 */
if ( ! function_exists( 'mona_replace_tel' ) ) {
    function mona_replace_tel( $hotline = '' ) {
        if ( empty ( $hotline ) ) {
            return;
        }
        $string   = preg_replace( '/\s+/','',$hotline );
        $stringaz = preg_replace( '/[^a-zA-Z0-9_ -]/s', '', $string );
        $tel = 'tel:'.$stringaz;
        return $tel;
    }
}


/**
 * Debug variable
 */
if ( ! function_exists( 'mona_debug' ) ) {
    function mona_debug( ...$args ) {
        echo '<pre>';
        var_dump( $args );
        echo '</pre>';
    }
}


/**
 * Remove p tag
 * 
 * @param string $content
 * @return string
 */
if ( ! function_exists( 'mona_remove_p_tag' ) ) {
    function mona_remove_p_tag( string $content ) : string {
        return preg_replace( '/<p>(.*)<\/p>/', '\1', $content );
    }
}


/**
 * Get list link for breadcrumb
 */
if ( ! function_exists( 'mona_get_list_breadcrumb' ) ) {
    function mona_get_list_breadcrumb() {
        global $wp_rewrite, $post;

        $rewriteUrl = $wp_rewrite->using_permalinks();

        // Function create item link
        $create_item_link = function ( string $title, string $permalink, bool $is_active = false ) {
            return array(
                'title' => $title,
                'url' => $permalink,
                'is-active' => $is_active,
            );
        };

        // Result
        $result = array();

        // Default title pages
        $ar_title = array(
            'home'   => __('Trang chủ', 'monamedia'),
            'search' => __('Kết quả tìm kiếm ', 'monamedia'),
            '404'    => __('Lỗi 404', 'monamedia'),
            'tagged' => __('Được gán thẻ ', 'monamedia'),
            'author' => __('Các bài viết được đăng bởi ', 'monamedia'),
            'page'   => __('Trang', 'monamedia'),
        );

        // If not front page
        if ( ! is_front_page() || is_paged() ) {
            // Home
            $homeLink = esc_url( home_url('/') );
            $result[] = $create_item_link( $ar_title['home'], $homeLink );

            // Category page
            if ( is_category() ) {
                // Page blog
                if (MONA_PAGE_BLOG) {
                    $result[] = $create_item_link(
                        get_post_field('post_title', MONA_PAGE_BLOG),
                        get_permalink(MONA_PAGE_BLOG)
                    );
                }

                // Cats
                $current_cat = get_queried_object();

                // Parent
                if ( $current_cat->parent != 0 ) {
                    $parent_cat = get_category($current_cat->parent);

                    $result[] = $create_item_link( $parent_cat->name, get_category_link( $parent_cat->term_id ) );
                }

                $result[] = $create_item_link( $current_cat->name, '#', true );
            }
            // Tag page
            elseif ( is_tag() ) {
                // Page blog
                if (MONA_PAGE_BLOG) {
                    $result[] = $create_item_link(
                        get_post_field('post_title', MONA_PAGE_BLOG),
                        get_permalink(MONA_PAGE_BLOG)
                    );
                }

                $result[] = $create_item_link( single_tag_title('', false), '#', true );
            }
            // Taxonomy page
            elseif ( is_tax() ) {
                $term = get_queried_object();

                // Parent
                if ($term->parent != 0) {
                    $parent = get_term($term->parent, $term->taxonomy);

                    $result[] = $create_item_link( $parent->name, get_term_link( $parent->term_id ) );
                }

                $result[] = $create_item_link( single_tag_title('', false), '#', true );
            }
            // Blog page
            elseif ( is_home() ) {
                $result[] = $create_item_link( get_the_title(MONA_PAGE_BLOG), '#', true );
            }
            // Search page
            elseif ( is_search() ) {
                $result[] = $create_item_link( $ar_title['search'] . '"' . get_search_query() . '"', '#', true );
            }
            // Day archive
            elseif ( is_day() ) {
                $result[] = $create_item_link( get_the_time('d'), '#', true );
            }
            // Month archive
            elseif ( is_month() ) {
                $result[] = $create_item_link( get_the_time('F'), '#', true );
            }
            // Year archive
            elseif ( is_year() ) {
                $result[] = $create_item_link( get_the_time('Y'), '#', true );
            }
            // Single
            elseif ( is_single() && ! is_attachment() ) {
                switch ($post->post_type) {
                    case 'post':
                        // Page blog
                        if (MONA_PAGE_BLOG) {
                            $result[] = $create_item_link(
                                get_post_field('post_title', MONA_PAGE_BLOG),
                                get_permalink(MONA_PAGE_BLOG)
                            );
                        }

                        // Category
                        $cat = mona_get_primary_term($post->ID);
                        if ($cat && ! is_wp_error($cat)) {
                            $result[] = $create_item_link($cat->name, get_category_link( $cat->term_id ));
                        }

                        // Current post
                        $result[] = $create_item_link( get_the_title(), '#', true );
                        break;

                    default:
                        $url = "#";

                        $post_type = get_post_type_object($post->post_type);

                        $post_type_name = $post_type->labels->singular_name;

                        // Detech page template case
                        if ( ! empty( $post_type->rewrite['slug'] ) ) {

                            $pages = get_pages(
                                array(
                                    'post_type' => 'page',
                                    'meta_key'  => '_wp_page_template',
                                    'meta_value' => 'page-template/' . trim($post_type->rewrite['slug']) . '-template.php'
                                )
                            );

                            if (isset($pages[0])) {
                                $array = (array) $pages[0];

                                $url   = get_page_link($array['ID']);
                            }
                        }

                        if ($rewriteUrl) {
                            $result[] = $create_item_link( $post_type_name, $url );
                        }
                        else {
                            $result[] = $create_item_link( $post_type_name, $homeLink . '?post_type=' . $post->post_type );
                        }

                        $result[] = $create_item_link( get_the_title(), '#', true );
                        break;
                }
            }
            // Attachment
            elseif ( is_attachment() ) {
                if ( $post->post_parent != 0) {
                    $parent = get_post($post->post_parent);
                    $cat = get_the_category($parent->ID);

                    // Category
                    if ( ! empty($cat) ) {
                        $cat = $cat[0];
                        $result[] = $create_item_link( $cat->name, get_category_link( $cat->term_id ) );
                    }

                    $result[] = $create_item_link( $parent->post_title, get_permalink( $parent ) );
                }

                $result[] = $create_item_link( get_the_title(), '#', true );
            }
            // Page not have parent
            elseif ( is_page() && ! $post->post_parent ) {
                $result[] = $create_item_link( get_the_title(), '#', true );
            }
            // Page have parent
            elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();

                while ($parent_id > 0) {
                    $page = get_post($parent_id);

                    $breadcrumbs[] = $create_item_link( get_the_title($page->ID), get_permalink($page->ID) );

                    $parent_id  = $page->post_parent;
                }

                // Reverse sibling
                $breadcrumbs = array_reverse($breadcrumbs);
                
                $breadcrumbs[] = $create_item_link( get_the_title(), '#', true );

                $result = array_merge( $result, $breadcrumbs );
            }
            // Author page
            elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);

                $result[] = $create_item_link( $ar_title['author'] . $userdata->display_name, '#', true );
            }
            // 404 page
            elseif ( is_404() ) {
                $result[] = $create_item_link( $ar_title['404'], '#', true );
            }
            else {
                $result[] = $create_item_link( get_the_title(), '#', true );
            }
        }

        return $result;
    }
}


/**
 * Ouput breadcrumb
 */
if ( ! function_exists( 'mona_output_breadcrumb' ) ) {
    function mona_output_breadcrumb() {
        $links = mona_get_list_breadcrumb();

        if ( empty( $links ) ) return;
        
        get_template_part( 'partials/components/breadcrumb', null, array(
            'links' => $links,
        ) );
    }
}

/**
 * Register custom post type
 * 
 * @param string $post_type
 * @param string $label
 * @param array $args
 * 
 * @return void
 */
if (! function_exists('mona_register_custom_post_type')) {
    function mona_register_custom_post_type($post_type, $label, $args = array()) {
        $default_args = array(
            'public' => true,
            'labels' => array(
                'name'                     => __( $label, 'monamedia' ),
                'singular_name'            => __( $label, 'monamedia' ),
                'add_new'                  => __( 'Thêm mới', 'monamedia' ),
                'add_new_item'             => __( 'Thêm mới ' . $label, 'monamedia' ),
                'edit_item'                => __( 'Sửa ' . $label, 'monamedia' ),
                'new_item'                 => __( $label . ' mới', 'monamedia' ),
                'view_item'                => __( 'Xem ' . $label, 'monamedia' ),
                'view_items'               => __( 'Xem danh sách ' . $label, 'monamedia' ),
                'search_items'             => __( 'Tìm kiếm ' . $label, 'monamedia' ),
                'not_found'                => __( 'Không tìm thấy ' . $label . ' nào.', 'monamedia' ),
                'not_found_in_trash'       => __( 'Không tìm thấy ' . $label . ' nào trong thùng rác', 'monamedia' ),
                'parent_item_colon'        => __( 'Các ' . $label . ' cha:', 'monamedia' ),
                'all_items'                => __( 'Tất cả ' . $label, 'monamedia' ),
                'archives'                 => __( 'Các lưu trữ ' . $label, 'monamedia' ),
                'attributes'               => __( 'Các thuộc tính ' . $label, 'monamedia' ),
                'insert_into_item'         => __( 'Chèn vào ' . $label, 'monamedia' ),
                'uploaded_to_this_item'    => __( 'Đã tải ' . $label . ' này lên', 'monamedia' ),
                'featured_image'           => __( 'Ảnh đại diện', 'monamedia' ),
                'set_featured_image'       => __( 'Thiết lập ảnh đại diện', 'monamedia' ),
                'remove_featured_image'    => __( 'Xóa ảnh đại diện', 'monamedia' ),
                'use_featured_image'       => __( 'Dùng như ảnh đại diện', 'monamedia' ),
                'menu_name'                => __( $label, 'monamedia' ),
                'filter_items_list'        => __( 'Lọc danh sách ' . $label, 'monamedia' ),
                'filter_by_date'           => __( 'Lọc bởi ngày', 'monamedia' ),
                'items_list_navigation'    => __( 'Danh sách điều hướng các ' . $label, 'monamedia' ),
                'items_list'               => __( 'Danh sách ' . $label, 'monamedia' ),
                'item_published'           => __( $label . ' đã được xuất bản.', 'monamedia' ),
                'item_published_privately' => __( $label . ' được xuất bản riêng tư.', 'monamedia' ),
                'item_reverted_to_draft'   => __( $label . ' đã chuyển thành bản nháp.', 'monamedia' ),
                'item_scheduled'           => __( $label . ' đã được lên lịch.', 'monamedia' ),
                'item_updated'             => __( $label . ' đã được cập nhật.', 'monamedia' ),
                'item_link'                => __( 'Đường dẫn ' . $label, 'monamedia' ),
                'item_link_description'    => __( 'Một liên kết đến một ' . $label . '.', 'monamedia' ),
            ),
        );

        $register_args = wp_parse_args($args, $default_args);

        // Regist post type
        register_post_type($post_type, $register_args);
    }
}

/**
 * Register custom taxonomy
 * 
 * @param string $taxonomy
 * @param string $label
 * @param string $post_type
 * @param array $args
 * 
 * @return void
 */
if (! function_exists('mona_register_custom_taxonomy')) {
    function mona_register_custom_taxonomy($taxonomy, $label, $post_type, $args = array()) {
        $default_args = array(
            'public' => true,
            'show_ui' => true,
            'labels' => array(
                'name'                       => _x( $label, 'taxonomy general name', 'monamedia' ),
                'singular_name'              => _x( $label, 'taxonomy singular name', 'monamedia' ),
                'search_items'               => __( 'Tìm kiếm ' . $label, 'monamedia' ),
                'popular_items'              => __( $label . ' phổ biến', 'monamedia' ),
                'all_items'                  => __( 'Tất cả ' . $label, 'monamedia' ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Sửa ' . $label, 'monamedia' ),
                'update_item'                => __( 'Cập nhật ' . $label, 'monamedia' ),
                'add_new_item'               => __( 'Thêm mới ' . $label, 'monamedia' ),
                'new_item_name'              => __( 'Tên ' . $label . ' mới', 'monamedia' ),
                'separate_items_with_commas' => __( 'Phân chia các ' . $label . ' với dấu \',\'', 'monamedia' ),
                'add_or_remove_items'        => __( 'Thêm hoặc xóa ' . $label, 'monamedia' ),
                'choose_from_most_used'      => __( 'Chọn từ danh sách ' . $label . ' được dùng nhiều nhất', 'monamedia' ),
                'not_found'                  => __( 'Không tìm thấy ' . $label . ' nào.', 'monamedia' ),
                'menu_name'                  => __( $label, 'monamedia' ),
            ),
        );

        $register_args = wp_parse_args($args, $default_args);

        // Regist taxonomy
        register_taxonomy($taxonomy, $post_type, $register_args);
    }
}

if (! function_exists('mona_get_page_id_from_template')) {
    function mona_get_page_id_from_template(string $template) {
        $pages = get_posts([
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_key' => '_wp_page_template',
            'meta_value' => $template,
            'fields' => 'ids',
        ]);

        if ($pages) {
            return $pages[0];
        }

        return null;
    }
}

if (! function_exists('mona_get_current_permalink')) {
    function mona_get_current_permalink() {
        return (is_tax() || is_category() || is_tag())
            ? get_term_link(get_queried_object_id())
            : get_permalink(get_queried_object_id());
    }
}

if (! function_exists('mona_generate_username_from_email')) {
    function mona_generate_username_from_email($email) {
        $email = sanitize_email($email);

        // Lấy phần trước @
        $base = sanitize_user(current(explode('@', $email)), true);

        // Fallback nếu email quá dị
        if (empty($base)) {
            $base = 'user';
        }

        $username = $base;
        $i = 1;

        // Đảm bảo username không trùng
        while (username_exists($username)) {
            $username = $base . $i;
            $i++;
        }

        return $username;
    }

}