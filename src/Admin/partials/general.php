<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>koality.io Monitoring</h2>

    <p style="max-width: 800px">
        Thank you for installing the koality.io WordPress plugin. To activate the monitoring
        please activate the plugin within koality.io and enter the API secret you can find on
        this page.
    </p>

    <input readonly="readonly" type="text" value="<?php use Koality\WordPressPlugin\Koality;

    echo esc_attr(get_option(Koality::OPTION_API_KEY)); ?>" style="width: 350px; margin-top: 30px; margin-bottom: 50px">


    <?php settings_errors(); ?>
    <form method="POST" action="options.php">
        <?php
        settings_fields( 'koality_general_settings' );
        do_settings_sections( 'koality_general_settings' );
        ?>
        <?php submit_button(); ?>
    </form>

</div>
