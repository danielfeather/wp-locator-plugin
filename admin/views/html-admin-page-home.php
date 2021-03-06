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
            <?php $this->render_status(); ?>
            <div class="flex items-center">
                <?php if ($this->get_status() !== 'connected'): ?>
                    <a href="<?php echo $authorization_url ?>" class="inline-block bg-green-500 hover:bg-green-600 focus:bg-green-600 px-4 py-2 font-bold text-white">Connect</a>
                <?php else: ?>
                    <a href="<?php echo $authorization_url ?>" class="inline-block bg-red-500 hover:bg-red-600 focus:bg-red-600 px-4 py-2 font-bold text-white">Disconnect</a>
                <?php endif; ?>
            </div>
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
        </div>
    </div>
</div>

