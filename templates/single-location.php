<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<?php if (have_posts()): ?>

    <?php wp_locator_get_template_part('content', 'single-location'); ?>

<?php endif; ?>

<?php get_footer() ?>
