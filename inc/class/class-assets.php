<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 10:53 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Assets' ) ) {
	class Class_Assets {

		/**
		 * Define css files
		 *
		 * @var array
		 */
		private static $public_css = [];

		/**
		 * Define js files
		 *
		 * @var array
		 */
		private static $public_js = [];

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Assets|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Assets constructor.
		 */
		private function __construct() {
			self::_map_public_assets();
			self::_load_public_assets();
		}

		/**
		 * Map public assets
		 */
		private static function _map_public_assets() {
			self::$public_css = [
				'stylesheet'       => get_stylesheet_uri(),
				'bootstrap'        => TEMP_URI . '/assets/vendor/bootstrap/css/bootstrap.min.css',
				'font-awesome'     => TEMP_URI . '/assets/vendor/fontawesome-free/css/all.min.css',
				'open-sans-gf'     => 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
				'merry-weather-gf' => 'https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic',
				'main'             => TEMP_URI . '/assets/landing/css/main.css'
			];

			self::$public_js = [
				'bootstrap'             => TEMP_URI . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
				'jquery-easing'         => TEMP_URI . '/assets/vendor/jquery-easing/jquery.easing.min.js',
				'jquery-magnific-popup' => TEMP_URI . '/assets/vendor/magnific-popup/jquery.magnific-popup.min.js',
				'scroll-reveal'         => TEMP_URI . '/assets/vendor/scrollreveal/scrollreveal.min.js',
				'main'                  => TEMP_URI . '/assets/landing/js/main.js'
			];
		}

		/**
		 * Load public assets
		 */
		private static function _load_public_assets() {
			add_action( 'wp_enqueue_scripts', [ __CLASS__, 'public_assets_callback' ] );
		}

		/**
		 * Callback for loading public assets
		 */
		static function public_assets_callback() {
			foreach ( self::$public_js as $name => $url ) {
				wp_enqueue_script( $name, $url, array( 'jquery' ), '', true );
			}

			foreach ( self::$public_css as $name => $url ) {
				wp_enqueue_style( $name, $url );
			}
		}
	}
}

Class_Assets::init();