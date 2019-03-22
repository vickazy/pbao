<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/21/2019
 * Time: 6:52 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Start Bootstrap</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
	        <?php wp_nav_menu( array(
			        'theme_location' => 'main_menu',
			        'menu_class'     => 'navbar-nav ml-auto',
			        'depth'          => 1,
			        'container'      => '',
			        'walker'         => new Class_Navwalker()
		        )
	        ); ?>
        </div>
    </div>
</nav>
