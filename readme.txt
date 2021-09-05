=== 360° monitoring (by koality.io) ===
Plugin URI:        https://github.com/koality-io/WordPressPlugin
Description:       This plugin is used to connect WordPress and WooCommerce with koality.io to then perform important monitoring.
Version:           ##KOALITY_VERSION##
Author:            koality.io - a WebPros company
Author URI:        https://www.koality.io
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       koality
Domain Path:       /languages
Requires at least: 5.4
Tested up to:      5.8
Stable tag:        1.0.0

== Description ==

With the help of koality.io plugin, the WordPress site or WooCommerce store is monitored with [360° monitoring](https://www.koality.io).

**important** this plugin connects WordPress with an existing koality.io account.

# WordPress features

- Server
 - Inform if there is no space left in the device.

- Security
 - Inform if the installed WordPress version is **outdated or insecure**
 - Inform if there are **too many / unknown admin users** in the system
 - Inform if there are **too many plugins that need an update**

- Content
 - Inform if there are too many pending comments
 - Inform if there are too many spam comments

# WooCommerce features

- Inform if the **number of orders** per hour reaches a critical value
- Inform if the **number of products** in the shop is too low.

# General Features

The koality.io basic subscription already includes many monitoring features that will take care of the WordPress or WooCommerce installation. All the checks are done continuously. All these checks are not WordPress specific and can be used for every website.

- **Uptime Monitoring** - every 5 minutes we check if the webserver and all important urls (avg. 15) are still responding.
- **Performance Monitoring** - We check browser load time, server load time, page sizes and Lighthouse performance.
- **SEO Monitoring** - Google Lighthouse checks, Google mobile friendly check and sitemap.xml validation
- **Content Monitoring** - Checking for dead links and broken images and files.
- **Security Monitoring** - Monitor HTTPS certificates, cookies, HTTP content on HTTPS pages

# Custom checks

We built the monitoring plugin with a simple idea in mind: everybody should be able to extend the metrics that are collected by its own. That is why koality.io will take all the data that is sent and will create beautiful graphs and statistics of it. It will also alert via email, slack or teams if the metrics do not meet the expectations.

== Installation ==

This section describes how to install the plugin and get it working.

1. After activating the plugin there appear new menu items in the backend (koality.io). On the main element you will find the API key for koality.io. Please copy this into your clipboard.


2. Login into koality.io (monitor.koality.io). If you do not have an account yet use the [registration form](https://monitor.koality.io/?register&lang=en)


3. Choose the project you want to connect to the WordPress plugin. And go to "extras" in the main menu.


4. Activate the WordPress plugin. It's free. Afterwards there will be a new menu item "WordPress".


5. Switch to the "WordPress" page and click on settings. There you have to enter the API key you copied in step 1.


6. Done. From now on koality. io does not only monitor your website from the outside but also from the inside.

== Screenshots ==
1. koality.io - The koality.io backend where all data come together.
2. Server Monitoring - Settings in the WordPress backend to configure the server settings.
3. Security Monitoring - Settings in the WordPress backend to configure the security settings.
4. WooCommerce Monitoring -  Settings in the WordPress backend to configure the WooCommerce settings.

== Changelog ==

= 1.0.0 =
 Initial version of the koality.io WordPress plugin. It creates data for the koality.io backend and will allow the monitoring tool to alert if any anomalies are found.
