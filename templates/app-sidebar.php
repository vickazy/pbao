<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/27/2019
 * Time: 2:30 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php //wp_nav_menu( array(
//		'theme_location' => 'app_sidebar',
//		'menu_class'     => 'navbar-nav bg-gradient-primary sidebar sidebar-dark accordion',
//		'depth'          => 2,
//		'container'      => '',
//		'walker'         => new Class_Navwalker()
//	)
//); ?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
            <i class="fas fa-book-reader"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?php echo PREFIX; ?></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
	<?php
	$linknum = 0;
	foreach ( $links as $link ) {
		$sublink  = '';
		$sub_html = '';
		if ( ! empty( $link['sub'] ) ) {
			$sublink = "data-toggle=\"collapse\" data-target=\"#collapse{$linknum}\" aria-expanded=\"true\" aria-controls=\"collapse{$linknum}\"";

			$sub_html .= "<div id=\"collapse{$linknum}\" class=\"collapse\" aria-labelledby=\"heading{$linknum}\" data-parent=\"#accordionSidebar\">";
			$sub_html .= "<div class=\"py-2 collapse-inner rounded\">";
			foreach ( $link['sub'] as $sub ) {
				$sub_html .= "<a class=\"collapse-item\" href=\"{$sub['url']}\">{$sub['title']}</a>";
			}
			$sub_html .= "</div>";
			$sub_html .= "</div>";
		}
		echo "<li class=\"nav-item\">";
		echo "<a class=\"nav-link collapsed\" href=\"{$link['url']}\" {$sublink}>";
		echo "<i class=\"fas fa-fw fa-{$link['icon']}\"></i><span>{$link['title']}</span>";
		echo "</a>";
		echo $sub_html;
		echo "</li>";
		$linknum ++;
	}; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
</ul>
