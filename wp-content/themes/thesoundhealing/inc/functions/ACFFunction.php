<?php

use Extended\ACF\Fields\Message;
use Extended\ACF\Fields\Tab;

defined('ABSPATH') || exit;

if (! function_exists('mona_regist_acf_field_group')) {
    function mona_regist_acf_field_group(array $settings, bool $auto_render = false): void {
        $data = register_extended_field_group($settings);

        if ($auto_render && ! empty($data['fields'])) {
            $upload_dir = wp_upload_dir()['basedir'] . '/custom-acf/';
            if (! file_exists($upload_dir)) {
                mkdir($upload_dir);
            }

            file_put_contents(
                $upload_dir . sanitize_title($settings['title']) . '.txt',
                wp_json_encode($data['fields'], JSON_UNESCAPED_UNICODE)
            );
        }

        
    }
}