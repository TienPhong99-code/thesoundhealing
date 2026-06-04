<?php

/**
 * The template for single page
 * 
 * @author MONA.Media / Website
 */

if (! defined('ABSPATH')) {
    die();
}

get_template_part('partials/templates/single/single', get_post_type());
