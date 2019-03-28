<?php /*Template name: Page App Dashboard */

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/27/2019
 * Time: 2:40 PM
 */

global $temp;
get_header();

while ( have_posts() ) : the_post();

	echo apply_filters( 'content_title', 'Dasbor' );

	echo $temp->render( 'app-dashboard-quick-result' );

	echo $temp->render( 'app-dashboard-chart' );

endwhile;

get_footer();