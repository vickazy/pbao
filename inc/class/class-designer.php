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
			self::register_hooks();
		}

		/**
		 * Register designer hooks
		 */
		private static function register_hooks() {
			add_action( 'header_content', [ __CLASS__, 'header_content_callback' ], 10 );
		}

		/**
		 * Callback for header content
		 */
		static function header_content_callback() {
			echo self::$temp->render('header-open');
		}
	}
}

Class_Designer::init();