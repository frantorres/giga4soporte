<?php
/*
Plugin Name: GIGA4 Soporte
Donate link: http://giga4.es/
Plugin URI: http://soporte.giga4.es/
Author: FranTorres
Author URI: http://frantorres.es/
Description: Coloca links para acceder fácilmente al sistema de soporte de GIGA4
Requires at least: 3.1
Tested up to: 3.5
Version: 1.00
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: soporte, giga4

@TODO: Integrate support in back-end
*/

add_action( 'init', 'github_plugin_updater_test_init' );
function github_plugin_updater_test_init() {

	include_once 'updater.php';

	define( 'WP_GITHUB_FORCE_UPDATE', true );

	if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin

		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'giga4soporte',
			'api_url' => 'https://api.github.com/repos/jkudish/WordPress-GitHub-Plugin-Updater',
			'raw_url' => 'https://raw.github.com/jkudish/WordPress-GitHub-Plugin-Updater/master',
			'github_url' => 'https://github.com/jkudish/WordPress-GitHub-Plugin-Updater',
			'zip_url' => 'https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/zipball/master',
			'sslverify' => true,
			'requires' => '3.1',
			'tested' => '3.5',
			'readme' => 'README.md',
			'access_token' => '',
		);

		new WP_GitHub_Updater( $config );

	}

}

// Admin Bar Customisation
function g4_admin_bar_render() {
 global $wp_admin_bar;

 // Remove an existing link using its $id
 // Here we remove the 'Updates' drop-down link
 //$wp_admin_bar->remove_menu('updates');

 // Add a new top level menu link
 // Here we add a customer support URL link
 $URL = 'http://www.giga4.es/';
 $wp_admin_bar->add_menu( array(
 'parent' => false,
 'id' => 'g4menu',
 'title' => __('GIGA4'),
 'href' => $URL
 ));

 // Add a new sub-menu to the link above
 // Here we add a link to our contact us web page
 $URL = 'http://soporte.giga4.es/';
 $wp_admin_bar->add_menu(array(
 'parent' => 'g4menu',
 'id' => 'g4soporte',
 'title' => __('Sistema de Soporte'),
 'href' => $URL
 ));
}

// Finally we add our hook function
add_action( 'wp_before_admin_bar_render', 'g4_admin_bar_render' );


?>
