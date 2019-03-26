<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/26/2019
 * Time: 10:33 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Sign In</h5>
                    <div class="login_alert"></div>
                    <form class="form-signin" id="frmLogin" method="post" action="#">
                        <div class="form-label-group">
                            <input type="text" id="upin" name="upin" class="form-control" placeholder="PIN" required autofocus>
                            <label for="upin">PIN</label>
                        </div>

                        <div class="form-label-group">
                            <input type="password" id="upass" name="upass" class="form-control" placeholder="Kata Sandi"
                                   required>
                            <label for="upass">Kata Sandi</label>
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" name="urem" id="urem">
                            <label class="custom-control-label" for="urem">Ingat saya</label>
                        </div>

                        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
