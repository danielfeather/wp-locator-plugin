<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

?>

<?php if (have_posts()): ?>
    <div class="flex list-none flex-wrap box-border">
        <?php while (have_posts()): the_post(); ?>
            <div class="pl-8 w-1/4">
                <div class="bg-white p-6 rounded ">
                    <h2 class="text-2xl font-bold mb-4"><?php the_title() ?></h2>
                    <p class="text-base my-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad alias aliquid aperiam aut corporis cupiditate debitis dolore dolorem, fugit, iste maxime neque nobis officiis provident saepe sequi voluptas voluptate.</p>

                    <a href="<?= get_permalink() ?>" class="text-base rounded inline-block bg-blue-400 px-4 py-2 text-white">View</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php get_footer() ?>