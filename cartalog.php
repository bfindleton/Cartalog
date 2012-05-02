<?php
/*
Plugin Name: Cartalog
Plugin URI: http://www.arbalestmedia.com
Description: Wordpress Shopping Cart Catalog add-on
Version: 0.3.4
Author: Bruce Findleton
Author URI: http://www.arbalestmedia.com
Text Domain: cartalog
Domain Path: /languages/

------------------------------------------------------------------------
Cartalog WordPress Ecommerce Plugin
Copyright 2012  Bruce Findleton

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!class_exists('Cartalog')) {
    ob_start();
    define("CARTALOG_PATH", plugin_dir_path( __FILE__ ) );
    define('CARTALOG_URL', plugins_url() . '/' . basename(dirname(__FILE__)));

    require_once(CARTALOG_PATH . "/models/Cartalog.php");
    require_once(CARTALOG_PATH . "/library/class-shortcode-mgr.php");
    require_once(CARTALOG_PATH . "/library/class-options.php");
    require_once(CARTALOG_PATH . "/library/class-admin.php");
    
    define('CARTALOG_VERSION', '0.3.4');
    define('CARTALOG_OPTIONS_KEY', 'cartalog_options');

    define('CART_CART66', 'cart66-lite/cart66.php');
    define('CART_WOOCOM', 'woocommerce/woocommerce.php');

    // IN_ADMIN is true when the dashboard or the administration panels are displayed
    if(!defined("IN_ADMIN")) {
        define("IN_ADMIN", is_admin());
    }

    $cartalog = new Cartalog();

    // Register activation hook to install Cartalog WP options
    register_activation_hook(__FILE__, array($cartalog, 'install'));
    
    // Register deactivation hook to uninstall Cartalog WP options
    register_deactivation_hook(__FILE__, array($cartalog, 'uninstall'));
    
    // Check for WordPress 3.1 auto-upgrades
    if(function_exists('register_update_hook')) {
        register_update_hook(__FILE__, array($cartalog, 'install'));
    }

    add_action('init', array($cartalog, 'init'));
}
