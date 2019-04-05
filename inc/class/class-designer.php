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
			global $temp;
			self::$temp = $temp;
			$this->_register_header_hooks();
			$this->_register_landing_hooks();
			$this->_register_app_hooks();
			$this->_register_footer_hooks();
		}

		/**
		 * Register designer for app page
		 */
		private function _register_app_hooks() {
			add_action( 'login_content', [ $this, 'login_content_callback' ] );
			add_action( 'header_content', [ $this, 'maybe_check_authentication_callback' ], 5 );
			add_action( 'header_content', [ $this, 'maybe_app_sidebar_callback' ], 20 );
			add_action( 'header_content', [ $this, 'maybe_app_topbar_callback' ], 30 );
			add_action( 'header_content', [ $this, 'maybe_app_content_callback' ], 40 );
			add_filter( 'content_title', [ $this, 'content_title_callback' ] );
			add_action( 'footer_content', [ $this, 'maybe_app_after_content_callback' ], 5 );
			add_action( 'footer_content', [ $this, 'maybe_app_after_footer_callback' ], 15 );
		}

		/**
		 * Register designer for header content
		 */
		private function _register_header_hooks() {
			add_action( 'wp_head', [ $this, 'head_content_callback' ] );
			add_action( 'header_content', [ $this, 'header_open_callback' ], 10 );
		}

		/**
		 * Register designer for landing page
		 */
		private function _register_landing_hooks() {
			add_filter( 'landing_reg_content', [ $this, 'landing_reg_content_callback' ] );
		}

		/**
		 * Register designer for footer content
		 */
		private function _register_footer_hooks() {
			add_action( 'footer_content', [ $this, 'footer_content_callback' ], 10 );
			add_action( 'footer_content', [ $this, 'footer_close_callback' ], 20 );
		}

		/**
		 * Check current user authentication
		 */
		function maybe_check_authentication_callback() {
			if ( is_app( false ) ) {
				if ( ! is_user_logged_in() ) {
					wp_redirect( home_url() );
				} else {
					if ( in_array( 'administrator', wp_get_current_user()->roles ) ) {
						wp_redirect( home_url() );
					}
				}
			}
		}

		/**
		 * Callback for rendering app sidebar
		 */
		function maybe_app_sidebar_callback() {
			if ( is_app( false ) ) {
				echo self::$temp->render( 'app-before-sidebar' ); // div#wrapper
				echo self::$temp->render( 'app-sidebar', [
					'links' => [
						[
							'icon'  => 'tachometer-alt',
							'url'   => 'app',
							'title' => 'Dasbor',
						],
						[
							'icon'  => 'book-open',
							'url'   => '#',
							'title' => 'Halaqah',
							'sub'   => [
								[
									'url'   => 'materi',
									'title' => 'Materi'
								],
								[
									'url'   => 'soal',
									'title' => 'Soal'
								],
								[
									'url'   => 'hasil',
									'title' => 'Hasil'
								]
							]
						],
						[
							'icon'  => 'chart-bar',
							'url'   => '#',
							'title' => 'Peringkat',
							'sub'   => [
								[
									'url'   => 'angkatan',
									'title' => 'Angkatan'
								],
								[
									'url'   => 'kelas',
									'title' => 'Kelas'
								]
							]
						],
						[
							'icon'  => 'cog',
							'url'   => '#',
							'title' => 'Pengaturan',
							'sub'   => [
								[
									'url'   => 'profil',
									'title' => 'Profil'
								],
								[
									'url'   => 'kata-sandi',
									'title' => 'Kata Sandi'
								]
							]
						]
					]
				] );
			}
		}

		/**
		 * Callback for rendering app topbar
		 */
		function maybe_app_topbar_callback() {
			if ( is_app( false ) ) {
				$me = wp_get_current_user();
				echo self::$temp->render( 'app-before-topbar' ); // div#content-wrapper div#content
				echo self::$temp->render( 'app-topbar', [
					'me' => [
						'name'       => $me->display_name,
						'avatar_url' => get_avatar_url( $me->ID )
					]
				] );
			}
		}

		/**
		 * Callback for rendering app content
		 */
		function maybe_app_content_callback() {
			if ( is_app( false ) ) {
				echo self::$temp->render( 'app-before-content' ); // div.container-fluid
			}
		}

		/**
		 * Callback for rendering content title
		 *
		 * @param $title
		 *
		 * @return string
		 */
		function content_title_callback( $title ) {
			return self::$temp->render( 'app-content-title', [ 'title' => $title ] ); // h1/
		}

		/**
		 * Callback for rendering app footer
		 */
		function maybe_app_after_content_callback() {
			if ( is_app( false ) ) {
				echo self::$temp->render( 'app-after-content' ); // /div.container-fluid
				echo self::$temp->render( 'app-before-footer' ); // /div#content
			}
		}

		/**
		 * Callback for rendering app after footer
		 */
		function maybe_app_after_footer_callback() {
			if ( is_app( false ) ) {
				echo self::$temp->render( 'app-after-footer' ); // /div#content-wrapper /div#wrapper
			}
		}

		/**
		 * Register designer for login page content
		 */
		function login_content_callback() {
			echo self::$temp->render( 'app-login' );
		}

		/**
		 * Callback for head content
		 */
		function head_content_callback() {
			echo "<meta charset=\"" . get_bloginfo( 'charset' ) . "\" />";
			echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
			echo "<link rel=\"pingback\" href=\"" . get_bloginfo( 'pingback_url' ) . "\"/>";
		}

		/**
		 * Callback for header opening tag
		 */
		function header_open_callback() {
			echo self::$temp->render( 'header-open' );
		}

		/**
		 * Callback for rendering landing page register form
		 */
		function landing_reg_content_callback() {
			$reg_status = is_reg_open();
			$result     = self::$temp->render( 'landing-reg-close', [ 'message' => $reg_status['message'] ] );
			if ( $reg_status['is_open'] ) {
				$result = self::$temp->render( 'landing-reg-open', [
					'cards' => [
						[
							'img_url' => TEMP_URI . '/assets/landing/img/ikhwan.png',
							'title'   => 'Ikhwan',
							'info'    => 'Kuota sudah terisi ' . $reg_status['ikhwan_isi_percent'] . '%'
						],
						[
							'img_url' => TEMP_URI . '/assets/landing/img/akhwat.png',
							'title'   => 'Akhwat',
							'info'    => 'Kuota sudah terisi ' . $reg_status['akhwat_isi_percent'] . '%'
						]
					]
				] );
			}

			return $result;
		}

		/**
		 * Callback for footer content
		 */
		function footer_content_callback() {
			$temp_name = is_app() ? 'footer-app' : 'footer-landing';
			echo self::$temp->render( $temp_name,
				[ 'footer_networks' => '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ]
			);
		}

		/**
		 * Callback for footer closing tag
		 */
		function footer_close_callback() {
			echo self::$temp->render( 'footer-close' );
		}
	}
}

Class_Designer::init();