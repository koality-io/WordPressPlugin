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
    <h2>koality.io Monitoring</h2>

    <p style="max-width: 800px">
        Thank you for installing the koality.io WordPress plugin. To activate the monitoring
        please activate the plugin within koality.io and enter the API secret you can find on
        this page.
    </p>

    <input readonly="readonly" type="text" value="<?php echo get_option(Koality::OPTION_API_KEY); ?>" style="width: 350px; margin-top: 30px">

</div>
