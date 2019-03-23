<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/23/2019
 * Time: 12:26 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<section id="reg" class="reg">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="section-heading">Pendaftaran</h2>
                <hr class="my-4">
                <p class="text-normal mb-4">Silahkan lakukan pendaftaran selama kuota masih tersedia</p>
                <div class="card-deck mb-5">
					<?php foreach ( $cards as $card ) { ?>
                        <div class="card">
                            <img class="card-img-top" src="<?php echo $card['img_url']; ?>" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $card['title'] ;?></h4>
                                <p class="card-text"><?php echo $card['info']; ?></p>
                            </div>
                        </div>
					<?php }; ?>
                </div>
                <div class="button-box">
                    <button type="button" class="btn btn-primary btn-xl">Daftar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</section>
