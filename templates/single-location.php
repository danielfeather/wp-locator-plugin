<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<?php if (have_posts()): ?>

    <?php
        $location_details = get_post_meta(get_the_ID(), '_wp_locator_location', true);
        $opening_hours = get_post_meta(get_the_ID(), '_wp_locator_opening_hours', true);
    ?>

    <div class="flex justify-center h-screen">
        <div id="map" class="w-1/2" data-lat="<?= $location_details['coordinates']['lat'] ?>" data-long="<?= $location_details['coordinates']['long'] ?>" data-maker-title="<?= the_title() ?>"></div>
        <div class="w-1/2 bg-white p-6">
            <h2 class="text-2xl font-bold mb-4"><?php the_title() ?></h2>
            <p class="text-base my-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad alias aliquid aperiam aut corporis cupiditate debitis dolore dolorem, fugit, iste maxime neque nobis officiis provident saepe sequi voluptas voluptate.</p>

        </div>
    </div>

<?php endif; ?>

<?php get_footer() ?>
