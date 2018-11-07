<?php
/**
*	Candy Wordpress Plugin
*
*	@package Candy
*
*	Plugin Name: Candy
*	Plugin URI: https://jacobford.co.uk
*	Description: A shopping cart for Wordpress
*	Version: 1.0.0
*	Author: Jacob Ford
*	Author URI: https://jacobford.co.uk
*	License: GPLv2 or later
*	Text Domain: candy
*/

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define('CANDY_PATH', plugin_dir_path(__FILE__));
define('CANDY_URL', plugin_dir_url(__FILE__));

require_once CANDY_PATH . 'inc/cart.class.php';
require_once CANDY_PATH . 'inc/order.class.php';
require_once CANDY_PATH . 'inc/store.class.php';
require_once CANDY_PATH . '/candy-admin.php';
require_once CANDY_PATH . '/candy-ajax.php';

$candy = new Candy_Cart;