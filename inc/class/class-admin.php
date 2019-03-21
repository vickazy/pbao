<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/21/2019
 * Time: 7:07 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Admin' ) ) {
	class Class_Admin {

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Admin|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Admin constructor.
		 */
		private function __construct() {
			self::_add_theme_support();
		}

		/**
		 * Add theme support
		 */
		private static function _add_theme_support() {
			add_theme_support( 'title-tag' );
			add_theme_support( 'menus' );
		}
	}
}

Class_Admin::init();