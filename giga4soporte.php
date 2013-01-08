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
Version: 1.02
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
			'api_url' => 'https://api.github.com/repos/frantorres/giga4soporte',
			'raw_url' => 'https://raw.github.com/frantorres/giga4soporte/master',
			'github_url' => 'https://github.com/frantorres/giga4soporte',
			'zip_url' => 'https://github.com/frantorres/giga4soporte/zipball/master',
			'sslverify' => true,
			'requires' => '3.1',
			'tested' => '3.5',
			'readme' => 'README.md',
			'access_token' => '',
		);

		new WP_GitHub_Updater( $config );

	}

}

/* User Unique ID for access Soporte page */
function g4soporte_add_custom_user_profile_fields( $user ) {
?>
	<h3><?php _e('GIGA4 Soporte', 'g4soporte'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="g4soporte-userid"><?php _e('Identificador único de usuario', 'g4soporte'); ?>
			</label></th>
			<td>
<?php 
			if ( current_user_can( 'manage_options', $user->ID ) ){ ?>
				<input type="text" name="g4soporte-userid" id="g4soporte-userid" value="<?php echo esc_attr( get_the_author_meta( 'g4soporte-userid', $user->ID ) ); ?>" class="regular-text" />
				<br />
				<span class="description"><?php _e('No modifique esta cadena a menos que sepa qué está haciendo :)', 'g4soporte'); ?></span>

			<?php } else { ?>
				<?php echo esc_attr( get_the_author_meta( 'g4soporte-userid', $user->ID ) ); ?>
				<br />
				<span class="description"><?php _e('Sólo un administrador apañado podría modificar esto.', 'g4soporte'); ?></span>
			<?php } ?>
			</td>
		</tr>
	</table>
<?php }
function g4soporte_save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	if ( !current_user_can( 'manage_options', $user_id ) )
		return FALSE;
	update_user_meta( $user_id, 'g4soporte-userid', $_POST['g4soporte-userid'] );
}
add_action( 'show_user_profile', 'g4soporte_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'g4soporte_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'g4soporte_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'g4soporte_save_custom_user_profile_fields' );



// Admin Bar Customisation
function g4soporte_admin_bar_render() {
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
 $user=wp_get_current_user();
 $g4soporte_userid = esc_attr( get_the_author_meta( 'g4soporte-userid', $user->ID ) );
 if (!empty($g4soporte_userid)){
 	$URL = 'http://soporte.giga4.es/?autologin_code='.$g4soporte_userid;
 } else {
	 $URL = 'http://soporte.giga4.es/wp-login.php';
 }
 $wp_admin_bar->add_menu(array(
 'parent' => 'g4menu',
 'id' => 'g4soporte',
 'title' => __('Sistema de Soporte'),
 'href' => $URL
 ));
}

// Finally we add our hook function
add_action( 'wp_before_admin_bar_render', 'g4soporte_admin_bar_render' );


?>
