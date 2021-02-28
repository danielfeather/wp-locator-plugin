<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div>
    <header class="py-6 px-10 flex bg-white mb-10">
        <div>
            <h1 class="text-xl font-bold">WP Locator</h1>
        </div>
    </header>
    <div class="px-10 mb-10">

    </div>
    <div class="flex px-10">
        <div class="w-1/2 mr-4">
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
        <div class="w-1/2">
            <form action="options.php" method="post">
                <h2 class="font-bold text-lg mb-2">OAuth 2.0 Settings</h2>
                <p>These settings are used by the plugin to tell it how to authenticate with the WP Locator API.</p>
                <?php
                settings_fields('wp-locator-oauth-settings');
                do_settings_sections('wp-locator-oauth-settings');
                submit_button();
                ?>
            </form>
        </div>
    </div>
</div>

