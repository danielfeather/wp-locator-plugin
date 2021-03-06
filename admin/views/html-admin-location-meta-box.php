<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<?php foreach ($fields as $key => $name): ?>
    <label for="wp-locator-api-id"><?= $name ?></label>
    <input type="text" value="<?= get_post_meta($location_id, $key, true) ?>">
<?php endforeach; ?>
