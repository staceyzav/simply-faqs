<?php
/**
 * Plugin Name: Simply FAQs
 * Plugin URI:  https://simplydesign.com
 * Description: FAQ post type with categories. Categories attach to pages so [simply_faqs] auto-detects which FAQs to show. Accordion expand/collapse, zero dependencies.
 * Author:      Simply Design
 * Author URI:  https://simplydesign.com
 * Version:     1.1.0
 * License:     GPL-2.0-or-later
 * Text Domain: simply-faqs
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SF_VERSION', '1.1.0' );
define( 'SF_PATH',    plugin_dir_path( __FILE__ ) );
define( 'SF_URL',     plugin_dir_url( __FILE__ ) );

require_once SF_PATH . 'includes/class-github-updater.php';
new Simply_GitHub_Updater( 'plugin', plugin_basename( __FILE__ ), 'staceyzav/simply-faqs', SF_VERSION );

require_once SF_PATH . 'includes/cpt.php';
require_once SF_PATH . 'includes/shortcode.php';
require_once SF_PATH . 'admin/settings.php';

add_action( 'wp_enqueue_scripts', 'sf_enqueue' );

function sf_enqueue() {
	wp_enqueue_style(
		'simply-faqs',
		SF_URL . 'assets/css/simply-faqs.css',
		array(),
		SF_VERSION
	);
	wp_enqueue_script(
		'simply-faqs',
		SF_URL . 'assets/js/simply-faqs.js',
		array(),
		SF_VERSION,
		true
	);
}
