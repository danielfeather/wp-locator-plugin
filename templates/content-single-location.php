<?php
    $location_details = get_post_meta(get_the_ID(), '_wp_locator_location', true);
    $opening_hours = get_post_meta(get_the_ID(), '_wp_locator_opening_hours', true);
?>

<div class="flex justify-center flex-wrap">
    <div id="map" class="w-full xl:w-1/2" style="min-height: 400px;" data-lat="<?= $location_details['coordinates']['lat'] ?>" data-long="<?= $location_details['coordinates']['long'] ?>" data-maker-title="<?= the_title() ?>"></div>
    <div class="w-full xl:w-1/2 bg-white p-6">
        <h2 class="text-2xl font-bold mb-4"><?php the_title() ?></h2>
        <p class="text-base my-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad alias aliquid aperiam aut corporis cupiditate debitis dolore dolorem, fugit, iste maxime neque nobis officiis provident saepe sequi voluptas voluptate.</p>
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2 mb-4 md:mb-0">
                <h3 class="text-xl font-bold mb-2">Address</h3>
                <ul class="list-none p-0 text-base">
                    <li class="flex">
                        <span class="w-1/3">
                            Address Line 1
                        </span>
                        <span class="w-2/3">
                            <?php echo $location_details['address']['address_line_1'] ?>
                        </span>
                    </li>
                    <li class="flex">
                        <span class="w-1/3">
                            Address Line 2
                        </span>
                        <span class="w-2/3">
                            <?php echo $location_details['address']['address_line_2'] ?>
                        </span>
                    </li>
                    <li class="flex">
                        <span class="w-1/3">
                            Town/City
                        </span>
                        <span class="w-2/3">
                            <?php echo $location_details['address']['city'] ?>
                        </span>
                    </li>
                    <li class="flex">
                        <span class="w-1/3">
                            County
                        </span>
                        <span class="w-2/3">
                            <?php echo $location_details['address']['county'] ?>
                        </span>
                    </li>
                    <li class="flex">
                        <span class="w-1/3">
                            Post Code
                        </span>
                        <span class="w-2/3">
                            <?php echo $location_details['address']['post_code'] ?>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="w-full md:w-1/2"">
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
</div>