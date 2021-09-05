<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.koality.io
 * @since             1.0.0
 * @package           Koality
 *
 * @wordpress-plugin
 * Plugin URI:        https://github.com/koality-io/WordPressPlugin
 * Description:       This plugin is used to connect WordPress and WooCommerce with koality.io to then perform important monitoring.
 * Version:           1.0.10
 * Author:            koality.io - a WebPros company
 * Author URI:        https://www.koality.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       koality
 * Domain Path:       /languages
 * Requires at least: 5.4
 * Tested up to:      5.8
 * Stable tag:        1.0.0
 */

// If this file is called directly, abort.
use Koality\WordPressPlugin\Koality;
use Koality\WordPressPlugin\Rest\Health;

if (!defined('WPINC')) {
    die;
}

define('WP_KOALITY_IO', true);

include_once __DIR__ . '/vendor/autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('KOALITY_VERSION', '1.0.10');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-koality-activator.php
 */
function activate_koality()
{
    \Koality\WordPressPlugin\Basic\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-koality-deactivator.php
 */
function deactivate_koality()
{
    \Koality\WordPressPlugin\Basic\Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_koality');
register_deactivation_hook(__FILE__, 'deactivate_koality');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_koality()
{
    add_action(Koality::WP_ACTION_INIT_CHECKS, ['Koality\WordPressPlugin\Koality', 'initChecks']);

    $plugin = new Koality();
    $plugin->run();

    // register hooks
    $healthEndpoint = new Health();
    $healthEndpoint->addHooks();
}

run_koality();
