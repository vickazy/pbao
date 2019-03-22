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
			add_action( 'header_content', [ $this, 'header_open_callback' ], 10 );
			add_action( 'header_content', [ $this, 'landing_top_nav_callback' ], 20 );
			add_action( 'landing_content', [ $this, 'landing_content_callback' ], 10 );
			add_action( 'footer_content', [ $this, 'footer_landing_callback' ], 10 );
			add_action( 'footer_content', [ $this, 'footer_close_callback' ], 20 );
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
	}
}

Class_Designer::init();