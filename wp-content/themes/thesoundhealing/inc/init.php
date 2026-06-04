<?php
defined('ABSPATH') || exit;

/********************
 * Load files
 *******************/
$loadFiles = require MONA_THEME_CONFIG_PATH . '/loadFile.php';

foreach ($loadFiles as $file) {
    if (! file_exists($file)) continue;

    require_once $file;
}

// Init theme
Mona_SetupTheme::get_instance();