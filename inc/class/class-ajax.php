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
			add_action( 'wp_ajax_ureg', [ $this, 'ureg_callback' ] );
			add_action( 'wp_ajax_nopriv_ureg', [ $this, 'ureg_callback' ] );
		}

		/**
		 * Callback for registering user
		 */
		function ureg_callback() {
			$result['is_error'] = true;

			$sObj   = ! empty( $_POST['data'] ) ? $_POST['data'] : false;
			$usObj  = maybe_unserialize( $sObj );
			$unama  = Class_Helper::get_serialized_val( $usObj, 'unama' );
			$uemail = Class_Helper::get_serialized_val( $usObj, 'uemail' );
			$uwa    = Class_Helper::get_serialized_val( $usObj, 'uwa' );
			$upass  = Class_Helper::get_serialized_val( $usObj, 'upass' );
			$upass2 = Class_Helper::get_serialized_val( $usObj, 'upass2' );
			$udate  = Class_Helper::get_serialized_val( $usObj, 'udate' );
			$ujk    = Class_Helper::get_serialized_val( $usObj, 'ujk' );
			$uaddr  = Class_Helper::get_serialized_val( $usObj, 'uaddr' );
			$uwhy   = Class_Helper::get_serialized_val( $usObj, 'uwhy' );

			$clean_unama  = sanitize_text_field( $unama );
			$clean_uemail = sanitize_email( $uemail );
			$clean_uwa    = sanitize_text_field( $uwa );
			$clean_aaddr  = sanitize_text_field( $uaddr );
			$clean_uwhy   = sanitize_textarea_field( $uwhy );

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				if ( $clean_unama && $clean_uemail && $clean_uwa && $upass && $upass2 && $udate && $ujk && $clean_aaddr ) {
					if ( $upass == $upass2 ) {
						if ( ! get_user_by( 'email', $clean_uemail ) ) {

							//Define default response
							$status            = Class_Helper::is_reg_open();
							$interupt          = true;
							$current_isi       = 0;
							$result['message'] = "Kuota pendaftaran sudah habis";

							switch ( $ujk ) {
								case '1':
									if ( $status['ikhwan_sisa_percent'] > 0 ) {
										$interupt    = false;
										$current_isi = $status['ikhwan_isi'];
									}
									break;
								case '2':
									if ( $status['akhwat_sisa_percent'] > 0 ) {
										$interupt    = false;
										$current_isi = $status['akhwat_isi'];
									}
									break;
							}
							if ( ! $interupt ) {
								$username = "PBAO-" . Class_Helper::generate_random_string( 7 );
								$userID   = wp_insert_user( array(
									'user_login'   => $username,
									'user_pass'    => $upass,
									'user_email'   => $clean_uemail,
									'first_name'   => $clean_unama,
									'nice_bane'    => sanitize_title( $unama ),
									'display_name' => $clean_unama
								) );
								if ( $userID ) {

									$verification_key   = Class_Helper::generate_unique_key();
									$result['is_error'] = false;
									update_user_meta( $userID, 'verification', $verification_key );
									update_user_meta( $userID, 'uwa', $clean_uwa );
									update_user_meta( $userID, 'udate', $udate );
									update_user_meta( $userID, 'ujk', $ujk );
									update_user_meta( $userID, 'uaddr', $clean_aaddr );
									update_user_meta( $userID, 'uwhy', $clean_uwhy );
									update_user_meta( $userID, 'angkatan', $status['angkatan_id'] );

									//update kuota angkatan
//									update_isi_angkatan( $status->angkatan_id, $ujk, $curisi + 1 );

									//Insert logs
//									insert_log( 'welcome_new_user', $userID, $status->angkatan_id );

								} else {
									$result['message'] = "Pendaftaran gagal, silahkan hubungi admin";
								}
							}
						} else {
							$result['message'] = "Email sudah terdaftar sebelumnya";
						}
					} else {
						$result['message'] = "Password konfirmasi tidak cocok";
					}
				} else {
					$result['message'] = "Semua kolom harus diisi.";
				}
			}
		}
	}
}

Class_Ajax::init();