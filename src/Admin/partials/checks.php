<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>koality.io Checks Overview</h2>

    <p style="max-width: 800px">
        koality.io can perform a variety of checks. In this overview, all possible checks are listed, but only those
        that have been enabled are actually executed.
    </p>

    <?php if ($stored): ?>
        <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
            <p><strong>Settings saved.</strong></p>
            <button type="button" class="notice-dismiss"><span
                        class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="koality_enabled[_system]" value="on">
        <table class="widefat fixed koalitytable" cellspacing="0" style="margin-top: 50px">
            <thead>
            <tr>
                <td style="width: 20px;"></td>
                <td style="width: 300px">Name</td>
                <td>Description</td>
            </tr>
            </thead>
            <tbody>
            <?php use Koality\WordPressPlugin\Checks\WordPressCheck;

            foreach ($checks as $group => $groupChecks): ?>
                <tr>
                    <td></td>
                    <td>
                        <div style="margin-top: 20px; "><?php echo $group; ?></div>
                    </td>
                </tr>
                <?php foreach ($groupChecks as $check): /** @var WordPressCheck $check */ ?>
                    <tr>
                        <td>
                            <input type="checkbox"
                                   name="koality_enabled[<?php echo $check->getIdentifier(); ?>]"
                                   id="<?php echo $check->getIdentifier(); ?>"
                                <?php if (array_key_exists($check->getIdentifier(), $enabledChecks)): ?>
                                    checked="checked"
                                <?php endif; ?>
                            ></td>
                        <td>
                            <label for="<?php echo $check->getIdentifier(); ?>"><strong><?php echo $check->getName(); ?></strong></label>
                        </td>
                        <td>
                            <?php echo $check->getDescription(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>

            </tbody>
        </table>

        <?php submit_button(); ?>

</div>
