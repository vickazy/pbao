<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/23/2019
 * Time: 9:00 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="card-deck mb-5">
	<?php foreach ( $cards as $card ) { ?>
        <div class="card">
            <div class="img-wrapper">
                <img class="card-img-top" src="<?php echo $card['img_url']; ?>" alt="Card image">
            </div>
            <div class="card-body">
                <h4 class="card-title"><?php echo $card['title']; ?></h4>
                <p class="card-text"><?php echo $card['info']; ?></p>
            </div>
        </div>
	<?php }; ?>
</div>
<div class="button-box">
    <button type="button" class="btn btn-primary btn-xl" data-toggle="modal" data-target="#myModal">Daftar Sekarang
    </button>
</div>
