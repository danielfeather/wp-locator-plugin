<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

?>

<?php if (have_posts()): ?>
    <div class="flex list-none flex-wrap box-border">
        <?php while (have_posts()): the_post(); ?>

            <?php wp_locator_get_template_part('content', 'location'); ?>

        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php get_footer() ?>