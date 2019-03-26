<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 11:15 PM
 */

/**
 * Hooked from Class_Designer::
 */
do_action( 'footer_content' );

if ( ! is_app() ) {
	global $temp;
	echo $temp->render( 'landing-reg-modal' );
}