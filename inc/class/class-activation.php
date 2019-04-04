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
			$charset_collate = $wpdb->get_charset_collate();
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$table_materi = $wpdb->prefix . "materi";
			$table_logs   = $wpdb->prefix . "logs";
			$sql_logs     = "CREATE TABLE $table_logs (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		title varchar(100) NOT NULL,
		detail text NOT NULL,
		object mediumint(9) NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
			$sql_materi   = "CREATE TABLE $table_materi (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		halaqah_id mediumint(9) NOT NULL,
		user_id mediumint(9) NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		title varchar(100) NOT NULL,
		questions text NOT NULL,
		url text NOT NULL,
		images text NOT NULL,
		status tinytext NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
			dbDelta( $sql_materi );
			dbDelta( $sql_logs );
		}
	}
}