<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 5:34 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Main' ) ) {
	class Class_Main {

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Main|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Main constructor.
		 */
		private function __construct() {
			$this->_load_classes();
		}

		/**
		 * Load class dependencies
		 */
		private function _load_classes() {

		}
	}
}