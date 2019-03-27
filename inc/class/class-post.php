<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/26/2019
 * Time: 10:58 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Post' ) ) {
	class Class_Post {

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Post|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Post constructor.
		 */
		private function __construct() {
			$this->_register_post_handler();
		}

		/**
		 * Register post handler
		 */
		private function _register_post_handler() {
			add_action( 'admin_post_nopriv_verify', [ $this, 'verify_callback' ] );
			add_action( 'admin_post_verify', [ $this, 'verify_callback' ] );
		}

		/**
		 * Callback for `verify` post
		 */
		function verify_callback() {
			$code = ! empty( $_GET['kode'] ) ? $_GET['kode'] : false;
			if ( $code ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'usermeta';
				$user_id    = $wpdb->get_var( "SELECT user_id FROM {$table_name} where meta_key = 'verification' and meta_value = '{$code}'" );
				if ( $user_id ) {
					delete_user_meta($user_id,'verification');
					wp_die('Akun berhasil diverifikasi','Sukses!',['response' => 200]);
				} else {
					wp_die( 'Maaf, akun Anda tidak ditemukan', 'Akun Tidak Ditemukan', [ 'response' => 404 ] );
				}
			} else {
				wp_die( 'Gagal melakukan verifikasi akun, silahkan coba lagi', 'Kesalaha!~' );
			}
		}
	}
}

Class_Post::init();