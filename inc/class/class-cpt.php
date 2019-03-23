<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/22/2019
 * Time: 3:09 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_CPT' ) ) {
	class Class_CPT {
		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Create singleton
		 *
		 * @return Class_CPT|null
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class_CPT constructor.
		 */
		private function __construct() {
			$this->_load_dependencies();
			$this->_register_angkatan_callback();
		}

		/**
		 * Load dependency libraries
		 */
		private function _load_dependencies() {
			require_once TEMP_PATH . '/inc/lib/cmb2/init.php';
		}

		/**
		 * Register cpt for angkatan
		 */
		private function _register_angkatan_callback() {
			add_action( 'init', [ $this, '_register_angkatan_post_type_callback' ] );
			add_action( 'cmb2_admin_init', [ $this, '_register_angkatan_metabox_callback' ] );
		}

		/**
		 * Callback for registering angkatan post type
		 */
		function _register_angkatan_post_type_callback() {
			$args_angkatan = [
				'labels'              => [
					'name'          => _x( 'Angkatan', 'Post Type General Name' ),
					'singular_name' => _x( 'Angkatan', 'Post Type Singular Name' ),
				],
				'supports'            => [
					'title',
					'thumbnail',
				],
				'taxonomies'          => [],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-learn-more',
//				'capabilities'        => [
//					'create_posts' => false,
//				],

			];
			register_post_type( 'angkatan', $args_angkatan );
		}

		/**
		 * Callback for registering angkatan metabox
		 */
		function _register_angkatan_metabox_callback() {
			$cmb = new_cmb2_box( [
				'id'           => 'cmb_home_masthead',
				'title'        => __( 'Angkatan', 'cmb2' ),
				'object_types' => [ 'angkatan' ], // Post type
//				'show_on'      => [
//					'key'   => 'page-template',
//					'value' => 'front-page.php'
//				],
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
//				'cmb_styles'   => false, // false to disable the CMB stylesheet
				// 'closed'     => true, // Keep the metabox closed by default
			] );
			$cmb->add_field( [
				'name' => 'Pembukaan',
				'id'   => 'pembukaan',
				'type' => 'text_datetime_timestamp',
			] );
			$cmb->add_field( [
				'name'            => __( 'Kuota Ikhwan' ),
				'id'              => 'kuota_ikhwan',
				'type'            => 'text',
				'attributes'      => [
					'type'     => 'number',
					'pattern'  => '\d*',
					'required' => 'required',
					'min'      => '0',
					'oninput'  => 'this.value = Math.abs(this.value)'
				],
				'sanitization_cb' => 'absint',
				'escape_cb'       => 'absint',
				'after'           => " Orang"
			] );
			$cmb->add_field( [
				'name'            => __( 'Kuota Akhwat' ),
				'id'              => 'kuota_akhwat',
				'type'            => 'text',
				'attributes'      => [
					'type'     => 'number',
					'pattern'  => '\d*',
					'required' => 'required',
					'min'      => '0',
					'oninput'  => 'this.value = Math.abs(this.value)'
				],
				'sanitization_cb' => 'absint',
				'escape_cb'       => 'absint',
				'after'           => " Orang"
			] );
			$cmb->add_field( [
				'name'            => __( 'Kuota Setiap Kelas' ),
				'id'              => 'kuota_kelas',
				'type'            => 'text',
				'default'         => 100,
				'attributes'      => [
					'type'     => 'number',
					'pattern'  => '\d*',
					'required' => 'required',
					'min'      => '1',
					'oninput'  => 'this.value = Math.abs(this.value)'
				],
				'sanitization_cb' => 'absint',
				'escape_cb'       => 'absint',
				'after'           => " Orang"
			] );
			$cmb->add_field( [
				'name' => __( 'Buka Pendaftaran' ),
				'desc' => 'Jangan dicentang jika pendaftaran telah ditutup',
				'id'   => 'buka',
				'type' => 'checkbox',
			] );
			$cmb->add_field( [
				'name'       => 'Catatan',
				'id'         => 'catatan',
				'type'       => 'textarea',
				'attributes' => [
					'rows' => '6',
				],
			] );
		}
	}
}

Class_CPT::init();