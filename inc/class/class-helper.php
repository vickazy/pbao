<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/23/2019
 * Time: 11:05 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Helper' ) ) {
	class Class_Helper {

		/**
		 * Get post meta value
		 *
		 * @param $key
		 * @param bool $post_id
		 *
		 * @return mixed
		 */
		static function ifield( $key, $post_id = false ) {
			return get_post_meta( ! $post_id ? get_the_ID() : $post_id, $key, true );
		}

		/**
		 * Get user meta value
		 *
		 * @param $key
		 * @param $user_id
		 *
		 * @return mixed
		 */
		static function ufield( $key, $user_id ) {
			return get_user_meta( $user_id, $key, true );
		}

		/**
		 * Cek status pendaftaran, apakah ada angkatan yang dibuka atau tidak
		 *
		 * @return array
		 */
		static function is_reg_open() {
			$result        = [
				'message' => ''
			];
			$qAllAngakatan = new WP_Query( array(
				'post_type'      => 'angkatan',
				'orderby'        => 'date',
				'order'          => 'ASC',
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'   => 'buka',
						'value' => 'on'
					)
				)
			) );
			if ( $qAllAngakatan->have_posts() ) {
				$result['is_open'] = true;
				while ( $qAllAngakatan->have_posts() ):
					$qAllAngakatan->the_post();
					$result['angkatan_id'] = get_the_ID();
					$pembukaan_timestamp   = self::ifield( 'pembukaan' );
					$now_timestamp         = strtotime( 'now' );
					if ( $pembukaan_timestamp > $now_timestamp ) {
						$result['is_open']                = false;
						$result['upcoming_open']          = $pembukaan_timestamp;
						$result['upcoming_open_readable'] = date( 'j F Y, H:i:s T', $pembukaan_timestamp );
						$result['message']                = "Saat ini belum ada pendaftaran yang dibuka, pendaftaran akan dibuka pada " . $result['upcoming_open_readable'];
					}

					$ikhwan_kuota = self::ifield( 'kuota_ikhwan' );
					$ikhwan_isi   = self::ifield( 'ikhwan_isi' ) ? self::ifield( 'ikhwan_isi' ) : 0;
					$ikhwan_sisa  = $ikhwan_kuota - $ikhwan_isi;
					$akhwat_kuota = self::ifield( 'kuota_akhwat' );
					$akhwat_isi   = self::ifield( 'akhwat_isi' ) ? self::ifield( 'akhwat_isi' ) : 0;
					$akhwat_sisa  = $akhwat_kuota - $akhwat_isi;

					$result['ikhwan_isi']          = $ikhwan_isi;
					$result['ikhwan_kuota']        = $ikhwan_kuota;
					$result['ikhwan_sisa_percent'] = $ikhwan_kuota > 0 ? number_format( $ikhwan_sisa * 100 / $ikhwan_kuota, 2 ) : 0;
					$result['ikhwan_isi_percent']  = 100 - $result['ikhwan_sisa_percent'];
					$result['akhwat_isi']          = $akhwat_isi;
					$result['akhwat_kuota']        = $akhwat_kuota;
					$result['akhwat_sisa_percent'] = $akhwat_kuota ? number_format( $akhwat_sisa * 100 / $akhwat_kuota, 2 ) : 0;
					$result['akhwat_isi_percent']  = 100 - $result['akhwat_sisa_percent'];
					if ( $result['ikhwan_sisa_percent'] <= 0 && $result['akhwat_sisa_percent'] <= 0 ) {
						$result['is_open'] = false;
						$result['message'] = "Kuota pendaftaran saat sudah terpenuhi";
					}
				endwhile;
			} else {
				$result['is_open'] = false;
				$result['message'] = "Belum ada pendaftaran yang dibuka";
			}
			wp_reset_query();

			return $result;
		}

		/**
		 * Get serialized value
		 *
		 * @param $objs
		 * @param $key
		 *
		 * @return array|bool|mixed
		 */
		static function get_serialized_val( $objs, $key ) {
			$result = false;
			$temres = array();
			foreach ( $objs as $obj ) {
				if ( $obj['name'] == $key ) {
					$temres[] = $obj['value'];
				}
			}
			$countarr = count( $temres );
			if ( $countarr > 0 ) {
				$result = count( $temres ) > 1 ? $temres : $temres[0];
			}

			return $result;
		}

		/**
		 * Generate random string
		 *
		 * @param int $length
		 * @param bool $numbers_only
		 *
		 * @return string
		 */
		static function generate_random_string( $length = 7, $numbers_only = false ) {
			$characters       = $numbers_only ? '1234567890' : '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen( $characters );
			$randomString     = '';
			for ( $i = 0; $i < $length; $i ++ ) {
				$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
			}

			return $randomString;
		}

		/**
		 * Generate random unique key
		 *
		 * @return string
		 */
		static function generate_unique_key() {
			return md5( uniqid( rand(), true ) );
		}

		/**
		 * Change angkatan status
		 *
		 * @param $angkatan_id
		 * @param $ujk
		 */
		static function update_isi_angkatan( $angkatan_id, $ujk ) {
			$status = self::is_reg_open();
			switch ( $ujk ) {
				case '1':
					update_post_meta( $angkatan_id, 'ikhwan_isi', $status['ikhwan_isi'] + 1 );
					break;
				case '2':
					update_post_meta( $angkatan_id, 'akhwat_isi', $status['akhwat_isi'] + 1 );
					break;
			}
		}

		/**
		 * Update post meta
		 *
		 * @param $post_id
		 * @param $args
		 * @param bool $update_user
		 */
		static function update_fields( $post_id, $args, $update_user = false ) {
			if ( $update_user ) {
				foreach ( $args as $arg_key => $arg_value ) {
					update_user_meta( $post_id, $arg_key, $arg_value );
				}
			} else {
				foreach ( $args as $arg_key => $arg_value ) {
					update_post_meta( $post_id, $arg_key, $arg_value );
				}
			}
		}

		/**
		 * Generate kelas
		 *
		 * @param array $users
		 * @param $angkatan
		 * @param int $jenis_kelamin
		 *
		 * @return bool|mixed
		 */
		static function generate_kelas( $users, $angkatan, $jenis_kelamin = 1 ) {
			$result     = false;
			$prefix_jk  = $jenis_kelamin == 1 ? "I" : "A";
			$nama_kelas = "HBAO" . $angkatan . "-" . $prefix_jk . self::generate_random_string( 5, true );

			$new_kelas = wp_insert_post( array(
				'post_type'   => 'kelas',
				'post_title'  => $nama_kelas,
				'post_name'   => sanitize_title( $nama_kelas ),
				'post_status' => 'publish'
			) );
			if ( $new_kelas ) {
				self::update_fields( $new_kelas, [
					'angkatan' => $angkatan,
					'peserta'  => $users,
					'kjk'      => $jenis_kelamin
				] );
				foreach ( $users as $user ) {
					//TODO: Update user log
//					insert_log( 'added_group', $user, $new_kelas );
					update_user_meta( $user, 'kelas', $new_kelas );
				}

				//Update status angkatan
				update_post_meta( $angkatan, 'kelas_dibuat', 'on' );

				$result = $new_kelas;
			}

			return $result;
		}

		/**
		 * Get list of ketua kelas
		 *
		 * @return array
		 */
		static function get_ketua_kelas() {
			$result         = [];
			$qAllKetuaKelas = array(
				'role'   => 'Contributor',
				'number' => - 1,
//			'meta_query' => array(
//				array(
//					'key'   => 'ujk',
//					'value' => $ujk,
//				),
//			)
			);

			$allKetuaKelas = new WP_User_Query( $qAllKetuaKelas );
			if ( ! empty( $allKetuaKelas->get_results() ) ) {
				foreach ( $allKetuaKelas->get_results() as $user ) {
					$result[ $user->ID ] = $user->display_name;
				}
			}
			wp_reset_query();

			return $result;
		}
	}
}