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
do_action( 'header_content' );

/** If current page is not app page it will be treated as landing page, which needs top nav bar */
if ( ! is_app() ) {
	global $temp;
	echo $temp->render( 'landing-top-nav' );
}
