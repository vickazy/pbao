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
			$this->_register_angkatan_callback();
		}

		private function _register_angkatan_callback() {
			add_action( 'init', [ $this, '_register_angkatan_post_type_callback' ] );
		}

		function _register_angkatan_post_type_callback() {
			$args_angkatan = [
				'labels'              => [
					'name'          => _x( 'Angkatan', 'Post Type General Name' ),
					'singular_name' => _x( 'Angkatan', 'Post Type Singular Name' ),
					'menu_name'     => __( 'Angkatan' ),
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
	}
}

Class_CPT::init();