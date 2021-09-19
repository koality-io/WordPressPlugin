<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>koality.io Checks Overview</h2>

    <p style="max-width: 800px">
        koality.io is able to monitoring importing content metrics. On this page you are able to set the settings
        for this monitoring.
    </p>

    <form method="POST">
        <input type="hidden" name="koality_enabled[_system]" value="on">

        <?php use Koality\WordPressPlugin\Checks\WordPressCheck;

        foreach ($checks as $group => $groupChecks): ?>
            <h2><?php echo $group; ?></h2>

            <?php foreach ($groupChecks as $check): /** @var WordPressCheck $check */ ?>
                <input type="checkbox"
                       name="koality_enabled[<?php echo $check->getIdentifier(); ?>]"
                       id="<?php echo $check->getIdentifier(); ?>"
                        <?php if(array_key_exists($check->getIdentifier(), $enabledChecks)): ?>
                        checked="checked"
                        <?php endif; ?>
                >
                <label for="<?php echo $check->getIdentifier(); ?>"><?php echo $check->getName(); ?></label>
                <br>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php submit_button(); ?>

</div>
