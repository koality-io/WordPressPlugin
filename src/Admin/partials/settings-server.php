<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.koality.io
 * @since      1.0.0
 *
 * @package    Koality
 * @subpackage Koality/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>koality.io Server Monitoring Settings</h2>

    <p style="max-width: 800px">
        koality.io is able to monitoring importing server metrics. On this page you ware able to set the settings
        for this monitoring.
    </p>

    <?php settings_errors(); ?>
    <form method="POST" action="options.php">
        <?php
        settings_fields( 'koality_server_settings' );
        do_settings_sections( 'koality_server_settings' );
        ?>
        <?php submit_button(); ?>
    </form>
</div>
