<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>koality.io Checks Overview</h2>

    <p style="max-width: 800px">
        koality.io is able to monitoring importing content metrics. On this page you are able to set the settings
        for this monitoring.
    </p>

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
                    <td><div style="margin-top: 20px; "><?php echo $group; ?></div></td>
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
