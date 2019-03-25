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
			$this->_add_theme_support();
			$this->_register_navmenu();
			$this->_customize_table_columns();
			$this->_rename_user_roles();
		}

		/**
		 * Rename user roles
		 */
		private function _rename_user_roles() {
			add_action( 'init', [ $this, 'rename_user_roles_callback' ] );
		}

		/**
		 * Add theme support
		 */
		private function _add_theme_support() {
			add_theme_support( 'title-tag' );
			add_theme_support( 'menus' );
		}

		/**
		 * Add theme nav menu
		 */
		private function _register_navmenu() {
			register_nav_menus( array(
				'main_menu' => 'Main Menu',
			) );
		}

		/**
		 * Customize admin table columns
		 */
		private function _customize_table_columns() {
			add_filter( 'manage_angkatan_posts_columns', [ $this, 'manage_angkatan_column_title_callback' ] );
			add_action( 'manage_angkatan_posts_custom_column', [ $this, 'manage_angkatan_columns_callback' ], 10, 2 );
		}

		/**
		 * Callback for renaming user roless
		 */
		function rename_user_roles_callback() {
			global $wp_roles;
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
			//You can use any of the roles "administrator" "editor", "author", "contributor" or "subscriber"...
			$wp_roles->roles['subscriber']['name']  = __( 'Peserta' );
			$wp_roles->roles['contributor']['name'] = __( 'Ketua Kelas' );
		}

		/**
		 * Callback for customizing angkatan column title
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		function manage_angkatan_column_title_callback( $columns ) {
			$columns = array(
				'cb'     => '<input type="checkbox" />',
				'title'  => __( "Nama" ),
				'amount' => __( 'Kuota' ),
				'status' => __( 'Status' ),
				'action' => __( 'Aksi' ),
				'date'   => __( "Dibuat" )
			);

			return $columns;
		}

		/**
		 * Callback for customizing angkatan columns
		 *
		 * @param $column
		 * @param $post_id
		 */
		function manage_angkatan_columns_callback( $column, $post_id ) {
			switch ( $column ) {
				case 'amount':
					$ikhwan = Class_Helper::ifield( 'kuota_ikhwan', $post_id );
					$akhwat = Class_Helper::ifield( 'kuota_akhwat', $post_id );
					$str    = $ikhwan ? "Ikhwan " . number_format( $ikhwan ) . " orang" : "";
					$str    .= $akhwat ? ( $ikhwan ? " dan akhwat " . number_format( $akhwat ) . " orang" : "Akhwat " . number_format( $akhwat ) . " orang" ) : "";
					echo $str;
					break;
				case 'status':
					$buka                = Class_Helper::ifield( 'buka', $post_id );
					$pembukaan_timestamp = Class_Helper::ifield( 'pembukaan', $post_id );
					if ( $pembukaan_timestamp ) {
						$readable_pembukaan = date( 'j F Y, H:i:s T', $pembukaan_timestamp );
						$now_timestamp      = strtotime( 'now' );
						if ( $pembukaan_timestamp > $now_timestamp ) {
							echo "Akan dibuka pada " . $readable_pembukaan;
						} else {
							$mulai = Class_Helper::ifield( 'mulai', $post_id );
							echo $buka == "on" ? "<span style=\"font-weight: bold; color: #00a65a\">Dibuka</span>" : ( $mulai ? "<span style=\"font-weight: bold; color: #00a65a\">Sedang Berlangsung</span>" : "<span style=\"font-weight: bold; color: #a60000\">Ditutup</span>" );
						}
					}
					break;
				case 'action':
					$buka         = Class_Helper::ifield( 'buka', $post_id );
					$kelas_dibuat = Class_Helper::ifield( 'kelas_dibuat', $post_id );
					$mulai        = Class_Helper::ifield( 'mulai', $post_id );
					if ( $buka != "on" && $kelas_dibuat != "on" && get_post_status( $post_id ) == 'publish' ) {
						echo "<button type='button' class=\"bkel button button-primary\" data-id=\"$post_id\">Bentuk Kelas</button>";
					} elseif ( $kelas_dibuat == "on" && $mulai != "on" ) {
						echo "<button type='button' class=\"mulang button button-primary\" data-id=\"$post_id\">Mulai Halaqah</button>";
					} elseif ( $mulai ) {
						echo "<a href=\"#\">Lihat grup</a>";
					}
					break;
				default :
					break;
			}
		}
	}
}

Class_Admin::init();