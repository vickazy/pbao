<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/22/2019
 * Time: 11:42 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<footer class="footer-distributed">
    <div class="container">
        <div class="footer-right">
            <a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a>
            <a class="linkedin" href="#"><i class="fab fa-linkedin-in"></i></a>
            <a class="twitter" href="#"><i class="fab fa-twitter"></i></a>
            <a class="instagram" href="#"><i class="fab fa-instagram"></i></a>
        </div>
        <div class="footer-left">
			<?php wp_nav_menu( array(
					'theme_location' => 'main_menu',
					'menu_class'     => 'footer-links',
					'depth'          => 1,
					'container'      => '',
					'walker'         => new Class_Navwalker()
				)
			); ?>
            <p class="cname"><?php echo $footer_networks; ?></p>
        </div>
    </div>
</footer>
