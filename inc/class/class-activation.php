<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/28/2019
 * Time: 3:03 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Activation' ) ) {
	class Class_Activation {

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_Activation|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_Activation constructor.
		 */
		private function __construct() {
			$this->_register_custom_table();
		}

		/**
		 * Register custom table
		 */
		private function _register_custom_table() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$table_name = $wpdb->prefix . "materi";
			$sql        = "CREATE TABLE $table_name (id int(10) unsigned NOT NULL AUTO_INCREMENT, identifier varchar(255) NOT NULL, translation varchar(255) NOT NULL, lang varchar(5) NOT NULL, notes varchar(255) DEFAULT NULL, PRIMARY KEY  (id), KEY Index_2 (lang), KEY Index_3 (identifier)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			dbDelta( $sql );
		}
	}
}

Class_Activation::init();