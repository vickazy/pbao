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
			$this->register_hooks();
		}

		/**
		 * Register designer hooks
		 */
		private function register_hooks() {
			add_action( 'wp_head', [ $this, 'head_content_callback' ] );
			add_action( 'header_content', [ $this, 'header_open_callback' ], 10 );
			add_action( 'header_content', [ $this, 'landing_top_nav_callback' ], 20 );
			add_action( 'landing_content', [ $this, 'landing_content_callback' ], 10 );
			add_filter( 'landing_reg_content', [ $this, 'landing_reg_content_callback' ] );
			add_action( 'footer_content', [ $this, 'footer_landing_callback' ], 10 );
			add_action( 'footer_content', [ $this, 'footer_close_callback' ], 20 );
			add_action( 'footer_content', [ $this, 'reg_modal_callback' ], 30 );
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
		 * Callback for landing page top nav
		 */
		function landing_top_nav_callback() {
			echo self::$temp->render( 'landing-top-nav' );
		}

		/**
		 * Callback for landing page content
		 */
		function landing_content_callback() {
			echo self::$temp->render( 'landing-masthead' );
			echo self::$temp->render( 'landing-about' );
			echo self::$temp->render( 'landing-how-to' );
			echo self::$temp->render( 'landing-faq' );
			echo self::$temp->render( 'landing-reg', [ 'reg_form_content' => '' ] );
		}

		/**
		 * Callback for rendering landing page register form
		 */
		function landing_reg_content_callback() {
			$reg_status = Class_Helper::is_reg_open();
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
		 * Callback for footer landing content
		 */
		function footer_landing_callback() {
			echo self::$temp->render( 'footer-landing',
				[ 'footer_networks' => '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ]
			);
		}

		/**
		 * Callback for footer closing tag
		 */
		function footer_close_callback() {
			echo self::$temp->render( 'footer-close' );
		}


		/**
		 * Callback for rendering registration modal
		 */
		function reg_modal_callback() {
			echo self::$temp->render( 'landing-reg-modal', [ '' ] );
		}
	}
}

Class_Designer::init();