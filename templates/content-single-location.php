<?php
    $location_details = get_post_meta(get_the_ID(), '_wp_locator_location', true);
    $opening_hours = get_post_meta(get_the_ID(), '_wp_locator_opening_hours', true);
?>

<div class="flex justify-center h-screen flex-wrap">
    <div id="map" class="w-1/2" data-lat="<?= $location_details['coordinates']['lat'] ?>" data-long="<?= $location_details['coordinates']['long'] ?>" data-maker-title="<?= the_title() ?>"></div>
    <div class="w-1/2 bg-white p-6">
        <h2 class="text-2xl font-bold mb-4"><?php the_title() ?></h2>
        <p class="text-base my-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad alias aliquid aperiam aut corporis cupiditate debitis dolore dolorem, fugit, iste maxime neque nobis officiis provident saepe sequi voluptas voluptate.</p>
        <div class="w-1/2">
            <h3 class="text-xl font-bold mb-2">Opening Hours</h3>
            <ul class="list-none p-0">
                <?php foreach ($opening_hours as $day => $hours) : ?>
                    <li class="text-base flex">
                    <span class="w-1/3">
                        <?php echo ucfirst($day) ?>
                    </span>
                    <span class="w-2/3">
                        <?php echo $hours ?>
                    </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
