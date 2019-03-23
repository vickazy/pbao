<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/23/2019
 * Time: 12:26 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reg_form_content = apply_filters( 'landing_reg_content', $reg_form_content );
?>

<section id="reg" class="reg">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="section-heading">Pendaftaran</h2>
                <hr class="my-4">
                <p class="text-normal mb-4">Silahkan lakukan pendaftaran selama kuota masih tersedia</p>
				<?php echo $reg_form_content; ?>
            </div>
        </div>
    </div>
</section>
