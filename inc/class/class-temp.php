<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 11:21 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Class_Temp' ) ) {
	class Class_Temp {

		/**
		 * Create instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Location of expected template
		 *
		 * @var string
		 */
		private static $folder;

		/**
		 * Create singleton
		 *
		 * @return null|Class_Temp
		 */
		static function init() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Template constructor.
		 *
		 */
		private function __construct() {
			$folder = TEMP_PATH . '/templates';
			if ( $folder ) {
				self::set_folder( $folder );
			}
		}

		/**
		 * Simple method for updating the base folder where templates are located.
		 *
		 * @param $folder
		 */
		private static function set_folder( $folder ) {
			// normalize the internal folder value by removing any final slashes
			self::$folder = rtrim( $folder, '/' );
		}

		/**
		 * Find and attempt to render a template with variables
		 *
		 * @param $suggestions
		 * @param $variables
		 *
		 * @return string
		 */
		public function render( $suggestions, $variables = [] ) {
			$template = $this->find_template( $suggestions );
			$output   = '';
			if ( $template ) {
				$output = $this->render_template( $template, $variables );
			}

			return $output;
		}

		/**
		 * Look for the first template suggestion
		 *
		 * @param $suggestions
		 *
		 * @return bool|string
		 */
		private function find_template( $suggestions ) {
			if ( ! is_array( $suggestions ) ) {
				$suggestions = array( $suggestions );
			}
			$suggestions = array_reverse( $suggestions );
			$found       = false;
			foreach ( $suggestions as $suggestion ) {
				$file = self::$folder . "/{$suggestion}.php";
				if ( file_exists( $file ) ) {
					$found = $file;
					break;
				}
			}

			return $found;
		}

		/**
		 * Execute the template by extracting the variables into scope, and including
		 * the template file.
		 *
		 * @param $template
		 * @param $variables
		 *
		 * @return string
		 */
		private function render_template( $template, $variables = [] ) {
			ob_start();
			foreach ( $variables as $key => $value ) {
				${$key} = $value;
			}
			include $template;

			return ob_get_clean();
		}
	}
}

global $temp;
$temp = Class_Temp::init();