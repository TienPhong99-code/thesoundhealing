<?php
defined('ABSPATH') || exit;

// ── Rewrite rule cho /tim-kiem ────────────────────────────────────────────
add_action('init', function () {
    add_rewrite_rule('^tim-kiem/?$', 'index.php?mona_page=search', 'top');
    add_rewrite_tag('%mona_page%', '([^&]+)');

    // Flush 1 lần duy nhất sau khi thêm rule mới
    if (!get_option('mona_search_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('mona_search_rewrite_flushed', '1');
    }
}, 5);

// ── Load template kết quả tìm kiếm ───────────────────────────────────────
add_filter('template_include', function ($template) {
    if (get_query_var('mona_page') === 'search') {
        $t = locate_template('page-template/page-search-results.php');
        return $t ?: $template;
    }
    return $template;
});

// ── Title tag cho trang /tim-kiem ─────────────────────────────────────────
add_filter('document_title_parts', function ($title) {
    if (get_query_var('mona_page') === 'search') {
        $title['title'] = 'Kết quả tìm kiếm';
    }
    return $title;
});
