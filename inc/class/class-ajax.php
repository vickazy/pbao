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

			add_action( 'wp_ajax_create_groups', [ $this, 'create_groups_callback' ] );
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
			$clean_uaddr  = sanitize_text_field( $uaddr );
			$clean_uwhy   = sanitize_textarea_field( $uwhy );

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				if ( $clean_unama && $clean_uemail && $clean_uwa && $upass && $upass2 && $udate && $ujk && $clean_uaddr ) {
					if ( $upass == $upass2 ) {
						if ( ! get_user_by( 'email', $clean_uemail ) ) {

							//Define default response
							$status            = Class_Helper::is_reg_open();
							$interupt          = true;
							$result['message'] = "Kuota pendaftaran sudah habis";

							switch ( $ujk ) {
								case '1':
									if ( $status['ikhwan_sisa_percent'] > 0 ) {
										$interupt = false;
									}
									break;
								case '2':
									if ( $status['akhwat_sisa_percent'] > 0 ) {
										$interupt = false;
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
									$result['message']  = "Pendaftaran sukses, silahkan periksa email untuk konfirmasi";

									Class_Helper::update_fields( $userID, [
										'verification' => $verification_key,
										'uwa'          => $clean_uwhy,
										'udate'        => $udate,
										'ujk'          => $ujk,
										'uaddr'        => $clean_uaddr,
										'uwhy'         => $clean_uwhy,
										'angkatan'     => $status['angkatan_id']
									], true );

									//update kuota angkatan
									Class_Helper::update_isi_angkatan( $status['angkatan_id'], $ujk );

									//TODO: Save user logs
									//Insert logs
//									insert_log( 'welcome_new_user', $userID, $status->angkatan_id );

									//TODO: Send email to user for verification


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

			wp_send_json( $result );
		}

		/**
		 * Callback for creating groups
		 */
		function create_groups() {
			$sObj   = $_POST['angkatan_id'];
			$result = [ 'is_error' => true ];
			if ( current_user_can( 'administrator' ) ) {
				if ( $sObj ) {
					$angkatan = get_post( $sObj );
					if ( $angkatan->post_type == 'angkatan' && $angkatan->post_status == 'publish' ) {
						$buka         = Class_Helper::ifield( 'buka', $angkatan->ID );
						$kelas_dibuat = Class_Helper::ifield( 'kelas_dibuat', $angkatan->ID );
						if ( $buka != "on" && $kelas_dibuat != "on" ) {
							$max_per_group = Class_Helper::ifield( 'kuota_kelas', $angkatan ) ? Class_Helper::ifield( 'kuota_kelas', $angkatan ) : 100;
							$qAllIkhwan    = new WP_User_Query( [
								'role'       => 'Subscriber',
								'number'     => - 1,
								'meta_query' => [
									[
										'key'     => 'ujk',
										'value'   => [ 1, 2 ],
										'compare' => 'IN'
									],
									[
										'key'   => 'angkatan',
										'value' => $angkatan->ID
									]
								]
							] );

							$arrKelas      = [];
							$arrPesertaIkh = [];
							$arrPesertaAh  = [];
							if ( ! empty( $qAllIkhwan->get_results() ) ) {
								foreach ( $qAllIkhwan->get_results() as $user ) {
									$user_id = $user->ID;
									$ujk     = Class_Helper::ufield( 'ujk', $user_id );
									if ( $ujk == 1 ) { //Laki-laki
										if ( count( $arrPesertaIkh ) >= $max_per_group ) {
											$arrKelas[]    = [
												"jk"  => 1,
												"ids" => $arrPesertaIkh
											];
											$arrPesertaIkh = [ $user_id ];
										} else {
											$arrPesertaIkh[] = $user_id;
										}
									} else if ( $ujk == 2 ) { //Perempuan
										if ( count( $arrPesertaAh ) >= $max_per_group ) {
											$arrKelas[]   = [
												"jk"  => 2,
												"ids" => $arrPesertaAh
											];
											$arrPesertaAh = [ $user_id ];
										} else {
											$arrPesertaAh[] = $user_id;
										}
									}
								}

								//Check if there's unused peserta
								if ( ! empty( $arrPesertaIkh ) ) {
									$arrKelas[] = [
										"jk"  => 1,
										"ids" => $arrPesertaIkh
									];
								}
								if ( ! empty( $arrPesertaAh ) ) {
									$arrKelas[] = [
										"jk"  => 2,
										"ids" => $arrPesertaAh
									];
								}

								//Loop grouped user_ids
								foreach ( $arrKelas as $datakls ) {
									$result['items'][] = Class_Helper::generate_kelas( $datakls['ids'], $angkatan->ID, $datakls['jk'] );
								}
								$result['is_error'] = false;
							}
						} else {
							$result['message'] = "Angkatan masih dibuka atau sudah punya kelas";
						}
					} else {
						$result['message'] = "Jenis pos tidak diizinkan";
					}
				} else {
					$result['message'] = "Pilih angkatan yang ingin dibuatkan grup";
				}
			} else {
				$result['message'] = "Anda tidak memiliki kredibilitas.";
			}
			wp_send_json( $result );
		}
	}
}

Class_Ajax::init();