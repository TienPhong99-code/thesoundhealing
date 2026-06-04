<?php

/**
 * The template for displaying footer.
 *
 * @package MONA.Media / Website
 */

if (!defined('ABSPATH')) {
    die();
}

?>
</main>

<?php get_template_part('partials/sections/footer'); ?>

<?php
// Modals — render ở body level, trên mọi z-index
if (is_singular('tuyen_dung')) {
    get_template_part('partials/modals/modal-ung-tuyen');
}
if (is_front_page()) {
    get_template_part('partials/sections/home/section', 'popup-du-an');
}
?>

<?php wp_footer(); ?>
</body>

</html>