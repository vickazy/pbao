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
				'stylesheet' => get_stylesheet_uri(),
				'main'       => TEMP_URI . '/assets/css/main.css'
			];

			self::$public_js = [
				'main' => TEMP_URI . '/assets/js/main.js'
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