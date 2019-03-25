<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 5:29 PM
 */

defined( 'TEMP_DIR' ) || define( 'TEMP_DIR', get_template_directory() );
defined( 'TEMP_URI' ) || define( 'TEMP_URI', get_template_directory_uri() );
defined( 'TEMP_PATH' ) || define( 'TEMP_PATH', get_theme_file_path() );
defined( 'PREFIX' ) || define( 'PREFIX', 'PBAO' );

require_once TEMP_PATH . '/inc/class/class-main.php';