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
		private $public_css = [];

		/**
		 * Define js files
		 *
		 * @var array
		 */
		private $public_js = [];

		/**
		 * Define admin css files
		 *
		 * @var array
		 */
		private $admin_css = [];

		/**
		 * Define admin js files
		 *
		 * @var array
		 */
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
				'stylesheet'           => [ 'url' => get_stylesheet_uri() ],
				'bootstrap'            => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap/css/bootstrap.min.css' ],
				'bootstrap-datepicker' => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css' ],
				'bootstrap-sweetalert' => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap-sweetalert/dist/sweetalert.css' ],
				'font-awesome'         => [ 'url' => TEMP_URI . '/assets/vendor/fontawesome-free/css/all.min.css' ],
				'open-sans-gf'         => [
					'url'  => 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
					'rule' => [
						'is_app' => false
					]
				],
				'merry-weather-gf'     => [
					'url'  => 'https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic',
					'rule' => [
						'is_app' => false
					]
				],
				'landing-main'         => [
					'url'  => TEMP_URI . '/assets/landing/css/main.css',
					'rule' => [
						'is_app' => false
					]
				],
				'nunito-gf'            => [
					'url'  => 'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
					'rule' => [
						'is_app' => true
					]
				],
				'app-login'            => [
					'url'  => TEMP_URI . '/assets/app/css/login.css',
					'rule' => [
						'is_app' => true
					]
				],
				'app-main'             => [
					'url'  => TEMP_URI . '/assets/app/css/sb-admin-2.css',
					'rule' => [
						'is_app' => true
					]
				],
				'app-custom'           => [
					'url'  => TEMP_URI . '/assets/app/css/custom.css',
					'rule' => [
						'is_app' => true
					]
				]
			];

			$this->public_js = [
				'bootstrap'                  => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js' ],
				'bootstrap-datepicker'       => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js' ],
				'bootstrap-datepicker-local' => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js' ],
				'bootstrap-sweetalert'       => [ 'url' => TEMP_URI . '/assets/vendor/bootstrap-sweetalert/dist/sweetalert.min.js' ],
				'jquery-easing'              => [ 'url' => TEMP_URI . '/assets/vendor/jquery-easing/jquery.easing.min.js' ],
				'jquery-validation'          => [ 'url' => TEMP_URI . '/assets/vendor/jquery-validation/dist/jquery.validate.min.js' ],
				'jquery-validation-local'    => [ 'url' => TEMP_URI . '/assets/vendor/jquery-validation/dist/localization/messages_id.min.js' ],
				'scroll-reveal'              => [ 'url' => TEMP_URI . '/assets/vendor/scrollreveal/scrollreveal.min.js' ],
				'landing-main'               => [
					'url'  => TEMP_URI . '/assets/landing/js/main.js',
					'vars' => [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ],
					'rule' => [
						'is_app' => false
					]
				],
				'app-main'                   => [
					'url'  => TEMP_URI . '/assets/app/js/sb-admin-2.js',
					'rule' => [
						'is_app' => true
					]
				],
				'app-login'                  => [
					'url'  => TEMP_URI . '/assets/app/js/login.js',
					'vars' => [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ],
					'rule' => [
						'_wp_page_template' => 'page-login.php'
					]
				]
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
			global $post;
			$post_id = is_object( $post ) ? $post->ID : get_the_ID();

			foreach ( $this->public_js as $name => $obj ) {
				$filter_key   = false;
				$filter_value = false;
				if ( isset( $obj['rule'] ) ) {
					$filter_key   = isset( $obj['rule']['is_app'] ) ? is_app() : ( ifield( '_wp_page_template', $post_id ) ? ifield( '_wp_page_template', $post_id ) : false );
					$filter_value = isset( $obj['rule']['is_app'] ) ? $obj['rule']['is_app'] : ( isset( $obj['rule']['_wp_page_template'] ) ? $obj['rule']['_wp_page_template'] : false );
				}
				if ( $filter_key == $filter_value ) {
					wp_enqueue_script( $name, $obj['url'], array( 'jquery' ), '', true );
					if ( isset( $obj['vars'] ) ) {
						wp_localize_script( $name, 'obj', $obj['vars'] );
					}
				}
			}

			foreach ( $this->public_css as $name => $obj ) {
				$filter_key   = false;
				$filter_value = false;
				if ( isset( $obj['rule'] ) ) {
					$filter_key   = isset( $obj['rule']['is_app'] ) ? is_app() : 'a';
					$filter_value = isset( $obj['rule']['is_app'] ) ? $obj['rule']['is_app'] : false;
				}
				if ( $filter_key == $filter_value ) {
					wp_enqueue_style( $name, $obj['url'] );
				}
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
					],
					'vars' => [
						'ajax_url' => admin_url( 'admin-ajax.php' )
					]
				]
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
			foreach ( $this->admin_js as $js_key => $obj ) {
				$filter_key   = false;
				$filter_value = false;
				if ( isset( $obj['rule'] ) ) {
					$filter_key   = isset( $obj['rule']['post_type'] ) ? ( is_object( $post ) ? $post->post_type : false ) : false;
					$filter_value = isset( $obj['rule']['post_type'] ) ? $obj['rule']['post_type'] : false;
				}

				if ( $filter_key == $filter_value ) {
					wp_enqueue_script( $js_key, $obj['url'], array( 'jquery' ), '', true );
					if ( isset( $obj['vars'] ) ) {
						wp_localize_script( $js_key, 'obj', $obj['vars'] );
					}
				}
			}
		}
	}
}

Class_Assets::init();