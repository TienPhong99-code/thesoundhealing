<?php
defined( 'ABSPATH' ) || exit;

$list_ajax = require MONA_THEME_CONFIG_PATH . '/ajax.php';

foreach ($list_ajax as $key => $value) {
    add_action('wp_ajax_' . $key, $value);
    add_action('wp_ajax_nopriv_' . $key, $value);
}