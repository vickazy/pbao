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
		 * Define dependency classes
		 *
		 * @var array
		 */
		private $classes = [];
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
			$this->_map_classes();
			$this->_load_classes();
		}

		/**
		 * Map dependency classes
		 */
		private function _map_classes() {
			$this->classes = [
				'assets',
				'temp',
				'designer',
				'admin',
				'navwalker',
				'ajax',
				'cpt',
				'post',
				'activation'
			];
		}

		/**
		 * Load dependency classes
		 */
		private function _load_classes() {
			foreach ( $this->classes as $class ) {
				require TEMP_DIR . "/inc/class/class-{$class}.php";
			}
		}
	}
}

Class_Main::init();