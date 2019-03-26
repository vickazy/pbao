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
			$this->_remove_page_support();
			$this->_add_theme_support();
			$this->_register_navmenu();
			$this->_customize_table_columns();
			$this->_rename_user_roles();
			$this->_filter_authentication();
		}

		/**
		 * Remove page support
		 */
		private function _remove_page_support() {
			add_action( 'admin_init', [ $this, '_remove_page_support_callback' ] );
		}

		/**
		 * Filter user who loggedin into wp-admin
		 */
		private function _filter_authentication() {
			add_action( 'init', [ $this, 'filter_authentication_callback' ] );
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

			add_filter( 'manage_kelas_posts_columns', [ $this, 'manage_kelas_column_title_callback' ] );
			add_action( 'manage_kelas_posts_custom_column', [ $this, 'manage_kelas_columns_callback' ], 10, 2 );
		}

		/**
		 * Callback for removing page support
		 */
		function _remove_page_support_callback() {
			$post_id = ! empty( $_GET['post'] ) ? $_GET['post'] : false;
			if ( ! $post_id ) {
				return;
			}

			$template_file = get_post_meta( $post_id, '_wp_page_template', true );

			if ( $template_file ) { // edit the template name
				remove_post_type_support( 'page', 'editor' );
			}
		}

		/**
		 * Callback for filtering user who can login into wp-admin
		 */
		function filter_authentication_callback() {
			if ( is_admin() && ! current_user_can( 'administrator' ) ) {
				wp_logout();
				wp_redirect( wp_login_url() );
//				exit;
			}
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
					$ikhwan = ifield( 'kuota_ikhwan', $post_id );
					$akhwat = ifield( 'kuota_akhwat', $post_id );
					$str    = $ikhwan ? "Ikhwan " . number_format( $ikhwan ) . " orang" : "";
					$str    .= $akhwat ? ( $ikhwan ? " dan akhwat " . number_format( $akhwat ) . " orang" : "Akhwat " . number_format( $akhwat ) . " orang" ) : "";
					echo $str;
					break;
				case 'status':
					$buka                = ifield( 'buka', $post_id );
					$pembukaan_timestamp = ifield( 'pembukaan', $post_id );
					if ( $pembukaan_timestamp ) {
						$readable_pembukaan = date( 'j F Y, H:i:s T', $pembukaan_timestamp );
						$now_timestamp      = strtotime( 'now' );
						if ( $pembukaan_timestamp > $now_timestamp ) {
							echo "Akan dibuka pada " . $readable_pembukaan;
						} else {
							$mulai = ifield( 'mulai', $post_id );
							echo $buka == "on" ? "<span style=\"font-weight: bold; color: #00a65a\">Dibuka</span>" : ( $mulai ? "<span style=\"font-weight: bold; color: #00a65a\">Sedang Berlangsung</span>" : "<span style=\"font-weight: bold; color: #a60000\">Ditutup</span>" );
						}
					}
					break;
				case 'action':
					$buka         = ifield( 'buka', $post_id );
					$kelas_dibuat = ifield( 'kelas_dibuat', $post_id );
					$mulai        = ifield( 'mulai', $post_id );
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

		/**
		 * Callback for customizing kelas column title
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		function manage_kelas_column_title_callback( $columns ) {
			$columns = array(
				'cb'       => '<input type="checkbox" />',
				'title'    => __( "Nama" ),
				'angkatan' => __( 'Angkatan' ),
				'khusus'   => __( 'Khusus' ),
				'kuota'    => __( 'Kuota' ),
				'jam'      => __( 'Waktu' ),
				'date'     => __( "Dibuat" ),
			);

			return $columns;
		}

		/**
		 * Callback for customizing kelas column
		 *
		 * @param $column
		 * @param $post_id
		 */
		function manage_kelas_columns_callback( $column, $post_id ) {
			switch ( $column ) {
				case 'angkatan':
					$angkatan = ifield( 'angkatan', $post_id );
					if ( $angkatan ) {
						echo "<a href=\"" . get_edit_post_link( $angkatan ) . "\">" . get_the_title( $angkatan ) . "</a>";
					}
					break;
				case 'khusus':
					$kjk = ifield( 'kjk', $post_id );
					if ( $kjk ) {
						echo $kjk == 1 ? "Kelas Ikhwan" : "Kelas Akhwat";
					}
					break;
				case 'kuota':
					$peserta = ifield( 'peserta', $post_id );
					echo $peserta ? count( $peserta ) . " Orang" : "0";
					break;
				case 'jam':
					$mulai   = ifield( 'jam_mulai', $post_id );
					$selesai = ifield( 'jam_selesai', $post_id );
					echo $mulai && $selesai ? $mulai . " - " . $selesai : "-";
					break;
				default :
					break;
			}
		}
	}
}

Class_Admin::init();