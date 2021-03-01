<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div>
    <header class="py-6 px-10 flex bg-white mb-8">
        <div>
            <h1 class="text-xl font-bold">WP Locator</h1>
        </div>
    </header>

    <div class="px-10 mb-8">
        <div class="bg-white p-4 flex">
            <div class="mr-auto block">
                <span class="text-red-500 font-bold text-lg block">Disconnected</span>
                <small class="block text-xs">Last Sync: Never</small>
            </div>
            <a href="<?php echo $authorization_url ?>" class="button button-primary">Authorize</a>
        </div>
    </div>

    <div class="flex px-10">
        <div class="w-1/2 mr-8 bg-white p-4">
            <form action="options.php" method="post">
                <h2 class="font-bold text-lg mb-2">API Settings</h2>
                <p>These settings are required for the proper functioning of the plugin. They tell the plugin how to access the WP Locator API.</p>
                <?php
                settings_fields('wp-locator-api-settings');
                do_settings_sections('wp-locator-api-settings');
                submit_button();
                ?>
            </form>
        </div>
        <div class="w-1/2 bg-white p-4">
            <form action="options.php" method="post">
                <h2 class="font-bold text-lg mb-2">OAuth 2.0 Settings</h2>
                <p>These settings are used by the plugin to tell it how to authenticate with the WP Locator API.</p>
                <?php
                settings_fields('wp-locator-oauth-settings');
                do_settings_sections('wp-locator-oauth-settings');
                submit_button();
                ?>
            </form>

            <form action="admin-post.php" method="post">
                <input type="hidden" name="action" value="wp_locator_register_client">
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</div>

