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
		 * Cek status pendaftaran, apakah ada angkatan yang dibuka atau tidak
		 *
		 * @return array
		 */
		static function is_reg_open() {
			$result        = [];
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
					$result['ikhwan_sisa_percent'] = $ikhwan_kuota > 0 ? $ikhwan_sisa * 100 / $ikhwan_kuota : 0;
					$result['ikhwan_isi_percent']  = 100 - $result['ikhwan_sisa_percent'];
					$result['akhwat_isi']          = $akhwat_isi;
					$result['akhwat_kuota']        = $akhwat_kuota;
					$result['akhwat_sisa_percent'] = $akhwat_kuota ? $akhwat_sisa * 100 / $akhwat_kuota : 0;
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
	}
}