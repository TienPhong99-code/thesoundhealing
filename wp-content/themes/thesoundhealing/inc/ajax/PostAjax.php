<?php
defined('ABSPATH') || exit;

if (! function_exists('mona_ajax_get_posts')) {
    function mona_ajax_get_posts()
    {
        try {
            if (! check_ajax_referer('mona-ajax-security', 'security', false)) {
                throw new Exception(__('Hành động không được xác thực', 'monamedia'));
            }

            $response = [
                'success' => true,
                'data' => [],
            ];

            $posts_per_page = filter_input(INPUT_POST, 'posts_per_page', FILTER_VALIDATE_INT);
            if (! $posts_per_page) {
                $posts_per_page = MONA_POSTS_PER_PAGE;
            }

            $paged = filter_input(INPUT_POST, 'paged', FILTER_VALIDATE_INT);
            if (! $paged) {
                $paged = 1;
            }

            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'offset' => ($paged - 1) * $posts_per_page,
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'fields' => 'ids',
                'meta_query' => ['relation' => 'AND'],
                'tax_query' => ['relation' => 'AND'],
                'orderby' => ['date' => 'DESC'],
            ];

            /**
             * Input format
             * 
             * [
             *     'category' => [1, 2],
             *     'post_tag' => [1, 2],
             * ]
             */
            if (! empty($_POST['taxonomies']) && is_array($_POST['taxonomies'])) {
                foreach ($_POST['taxonomies'] as $taxonomy => $terms) {
                    if (! is_array($terms)) continue;

                    $args['tax_query'][] = [
                        'taxonomy' => sanitize_text_field($taxonomy),
                        'field' => 'term_id',
                        'terms' => array_map(function ($term) {
                            return sanitize_text_field($term);
                        }, $terms),
                    ];
                }
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                $response['data']['posts'] = [];
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();

                    $response['data']['posts'][] = [
                        'id' => $post_id,
                        'title' => get_the_title($post_id),
                        'thumbnail' => get_the_post_thumbnail($post_id, 'full'),
                        'permalink' => get_the_permalink($post_id),
                        'date' => get_the_date('d/m/Y', $post_id),
                        'author' => get_the_author($post_id),
                    ];
                }

                $response['data']['pagination_html'] = mona_pagination_links($query, $paged);
            } else {
                $response['data']['empty_message'] = __('Không có bài viết nào được tìm thấy', 'monamedia');
            }

            wp_send_json($response);
        } catch (\Throwable $th) {
            wp_send_json([
                'success' => false,
                'errors' => [
                    __('Đã xảy ra sự cố', 'monamedia'),
                    // $th->getMessage(), // Debug
                ],
            ]);
        }

        wp_die();
    }
}

if (! function_exists('kiena_ajax_load_more_khoa_hoc')) {
    function kiena_ajax_load_more_khoa_hoc()
    {
        $offset   = filter_input(INPUT_POST, 'offset', FILTER_VALIDATE_INT);
        $offset   = ($offset !== false && $offset !== null && $offset >= 0) ? $offset : 6;
        $per_page = 3;

        $query = new WP_Query([
            'post_type'      => 'khoa_hoc',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        ]);

        if (! $query->have_posts()) {
            wp_send_json(['success' => true, 'html' => '', 'has_more' => false]);
            wp_die();
        }

        $total = (int) $query->found_posts;

        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $post_id   = get_the_ID();
            $thumb     = get_the_post_thumbnail_url($post_id, 'full');
            $terms     = get_the_terms($post_id, 'bo_mon_khoa_hoc');
            $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

            $item = [
                'image' => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
                'level' => get_field('level', $post_id),
                'term'  => $term_name,
                'title' => get_the_title($post_id),
                'desc'  => get_field('short_desc', $post_id),
                'price' => get_field('price', $post_id),
                'url'   => get_permalink($post_id),
            ];

            echo '<div class="col col-4 max-lg:!w-1/2 max-sm:!w-full">';
            get_template_part('partials/components/card-khoa-hoc', null, ['item' => $item]);
            echo '</div>';
        }
        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json([
            'success'  => true,
            'html'     => $html,
            'has_more' => ($offset + $per_page) < $total,
        ]);

        wp_die();
    }
}

if (! function_exists('kiena_ajax_load_more_workshop')) {
    function kiena_ajax_load_more_workshop()
    {
        $offset   = filter_input(INPUT_POST, 'offset', FILTER_VALIDATE_INT);
        $offset   = ($offset !== false && $offset !== null && $offset >= 0) ? $offset : 6;
        $per_page = 3;

        $query = new WP_Query([
            'post_type'      => 'workshop',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        ]);

        if (! $query->have_posts()) {
            wp_send_json(['success' => true, 'html' => '', 'has_more' => false]);
            wp_die();
        }

        $total = (int) $query->found_posts;

        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $post_id   = get_the_ID();
            $thumb     = get_the_post_thumbnail_url($post_id, 'full');
            $terms     = get_the_terms($post_id, 'loai_workshop');
            $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

            $item = [
                'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
                'type'     => $type_name,
                'status'   => get_field('ws_status', $post_id) ?: 'open',
                'date'     => get_field('ws_date', $post_id),
                'time'     => get_field('ws_time', $post_id),
                'title'    => get_the_title($post_id),
                'location' => get_field('ws_location', $post_id),
                'desc'     => get_field('ws_short_desc', $post_id),
                'price'    => get_field('ws_price', $post_id),
                'url'      => get_permalink($post_id),
            ];

            echo '<div class="col col-4">';
            get_template_part('partials/components/card-workshop', null, ['item' => $item]);
            echo '</div>';
        }
        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json([
            'success'  => true,
            'html'     => $html,
            'has_more' => ($offset + $per_page) < $total,
        ]);

        wp_die();
    }
}

if (! function_exists('kiena_ajax_load_more_tin_tuc')) {
    function kiena_ajax_load_more_tin_tuc()
    {
        $offset   = filter_input(INPUT_POST, 'offset', FILTER_VALIDATE_INT);
        $offset   = ($offset !== false && $offset !== null && $offset >= 0) ? $offset : 6;
        $per_page = 4;

        $query = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if (! $query->have_posts()) {
            wp_send_json(['success' => true, 'html' => '', 'has_more' => false]);
            wp_die();
        }

        $total = (int) $query->found_posts;

        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="col col-6 max-md:w-full! mb-6 max-xl:mb-4 max-md:mb-3">';
            get_template_part('partials/components/card-tin-tuc', null, ['post_id' => get_the_ID()]);
            echo '</div>';
        }
        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json([
            'success'  => true,
            'html'     => $html,
            'has_more' => ($offset + $per_page) < $total,
        ]);

        wp_die();
    }
}
