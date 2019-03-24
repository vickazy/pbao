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
		 * Define localize var
		 *
		 * @var array
		 */
		private $public_vars = [];

		/**
		 * Define css files
		 *
		 * @var array
		 */
		private $public_css = [];

		/**
		 * Define js files
		 *
		 * @var array
		 */
		private $public_js = [];

		private $admin_vars = [];

		private $admin_css = [];

		private $admin_js = [];

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
			$this->_map_public_assets();
			$this->_load_public_assets();

			$this->_map_admin_assets();
			$this->_load_admin_assets();
		}

		/**
		 * Map public assets
		 */
		private function _map_public_assets() {
			$this->public_css = [
				'stylesheet'           => get_stylesheet_uri(),
				'bootstrap'            => TEMP_URI . '/assets/vendor/bootstrap/css/bootstrap.min.css',
				'bootstrap-datepicker' => TEMP_URI . '/assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'font-awesome'         => TEMP_URI . '/assets/vendor/fontawesome-free/css/all.min.css',
				'open-sans-gf'         => 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
				'merry-weather-gf'     => 'https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic',
				'main'                 => TEMP_URI . '/assets/landing/css/main.css'
			];

			$this->public_vars = [
				[ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
			];

			$this->public_js = [
				'bootstrap'                  => TEMP_URI . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
				'bootstrap-datepicker'       => TEMP_URI . '/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'bootstrap-datepicker-local' => TEMP_URI . '/assets/vendor/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
				'jquery-easing'              => TEMP_URI . '/assets/vendor/jquery-easing/jquery.easing.min.js',
				'jquery-validation'          => TEMP_URI . '/assets/vendor/jquery-validation/dist/jquery.validate.min.js',
				'jquery-validation-local'    => TEMP_URI . '/assets/vendor/jquery-validation/dist/localization/messages_id.min.js',
				'scroll-reveal'              => TEMP_URI . '/assets/vendor/scrollreveal/scrollreveal.min.js',
				'main'                       => TEMP_URI . '/assets/landing/js/main.js'
			];
		}

		/**
		 * Load public assets
		 */
		private function _load_public_assets() {
			add_action( 'wp_enqueue_scripts', [ $this, 'public_assets_callback' ] );
		}

		/**
		 * Callback for loading public assets
		 */
		function public_assets_callback() {
			foreach ( $this->public_js as $name => $url ) {
				wp_enqueue_script( $name, $url, array( 'jquery' ), '', true );
			}

			foreach ( $this->public_vars as $var ) {
				wp_localize_script( 'main', 'obj', $var );
			}

			foreach ( $this->public_css as $name => $url ) {
				wp_enqueue_style( $name, $url );
			}


		}

		/**
		 * Map admin assets
		 */
		private function _map_admin_assets() {
			$this->admin_js = [
				'angkatan' => [
					'url'  => TEMP_URI . '/assets/admin/js/angkatan.js',
					'rule' => [
						'post_type' => 'angkatan'
					]
				]
			];

			$this->admin_vars = [
				[ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
			];
		}

		/**
		 * Load admin assets
		 */
		private function _load_admin_assets() {
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets_callback' ] );
		}

		/**
		 * Callback for loading admin assets
		 */
		function admin_assets_callback() {
			global $post;
			foreach ( $this->admin_js as $js_key => $js_obj ) {
				if ( $js_obj['rule'] ) {
					$filter_key   = ! empty( $js_obj['rule']['post_type'] ) ? $post->post_type : false;
					$filter_value = ! empty( $js_obj['rule']['post_type'] ) ? $js_obj['rule']['post_type'] : false;

					if ( $filter_key == $filter_value ) {
						wp_enqueue_script( $js_key, $js_obj['url'], array( 'jquery' ), '', true );
					}
				}
			}

			foreach ( $this->admin_vars as $var ) {
				wp_localize_script( 'main', 'obj', $var );
			}
		}
	}
}

Class_Assets::init();