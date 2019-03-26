<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 11:17 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Designer' ) ) {
	class Class_Designer {

		/**
		 * Instance template class
		 *
		 * @var null
		 */
		private static $temp = null;

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Designer|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Designer constructor.
		 */
		private function __construct() {
			$temp       = Class_Temp::init();
			self::$temp = $temp;
			$this->_register_header_hooks();
			$this->_register_landing_hooks();
			$this->_register_app_hooks();
			$this->_register_footer_hooks();
		}

		private function _register_app_hooks() {

		}

		/**
		 * Register designer for header content
		 */
		private function _register_header_hooks() {
			add_action( 'wp_head', [ $this, 'head_content_callback' ] );
			add_action( 'header_content', [ $this, 'header_open_callback' ], 10 );
		}

		/**
		 * Register designer for landing page
		 */
		private function _register_landing_hooks() {
			add_filter( 'landing_reg_content', [ $this, 'landing_reg_content_callback' ] );
		}

		/**
		 * Register designer for footer content
		 */
		private function _register_footer_hooks() {
			add_action( 'footer_content', [ $this, 'footer_content_callback' ], 10 );
			add_action( 'footer_content', [ $this, 'footer_close_callback' ], 20 );
		}

		/**
		 * Callback for head content
		 */
		function head_content_callback() {
			echo "<meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
		}

		/**
		 * Callback for header opening tag
		 */
		function header_open_callback() {
			echo self::$temp->render( 'header-open' );
		}

		/**
		 * Callback for rendering landing page register form
		 */
		function landing_reg_content_callback() {
			$reg_status = is_reg_open();
			$result     = self::$temp->render( 'landing-reg-close', [ 'message' => $reg_status['message'] ] );
			if ( $reg_status['is_open'] ) {
				$result = self::$temp->render( 'landing-reg-open', [
					'cards' => [
						[
							'img_url' => TEMP_URI . '/assets/landing/img/ikhwan.png',
							'title'   => 'Ikhwan',
							'info'    => 'Kuota sudah terisi ' . $reg_status['ikhwan_isi_percent'] . '%'
						],
						[
							'img_url' => TEMP_URI . '/assets/landing/img/akhwat.png',
							'title'   => 'Akhwat',
							'info'    => 'Kuota sudah terisi ' . $reg_status['akhwat_isi_percent'] . '%'
						]
					]
				] );
			}

			return $result;
		}

		/**
		 * Callback for footer content
		 */
		function footer_content_callback() {
			$temp_name = is_app() ? 'footer-app' : 'footer-landing';
			echo self::$temp->render( $temp_name,
				[ 'footer_networks' => '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ]
			);
		}

		/**
		 * Callback for footer closing tag
		 */
		function footer_close_callback() {
			echo self::$temp->render( 'footer-close' );
		}
	}
}

Class_Designer::init();