<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/22/2019
 * Time: 1:56 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Ajax' ) ) {
	class Class_Ajax {
		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Ajax|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Ajax constructor.
		 */
		private function __construct() {
			$this->_register_endpoints();
		}

		/**
		 * Register ajax endpoint
		 */
		private function _register_endpoints() {

		}
	}
}

Class_Ajax::init();