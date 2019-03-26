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
                    <form class="form-signin">
                        <div class="form-label-group">
                            <input type="text" id="upin" class="form-control" placeholder="PIN" required autofocus>
                            <label for="upin">PIN</label>
                        </div>

                        <div class="form-label-group">
                            <input type="password" id="upass" class="form-control" placeholder="Kata Sandi"
                                   required>
                            <label for="upass">Kata Sandi</label>
                        </div>

                        <!--                        <div class="custom-control custom-checkbox mb-3">-->
                        <!--                            <input type="checkbox" class="custom-control-input" id="customCheck1">-->
                        <!--                            <label class="custom-control-label" for="customCheck1">Remember password</label>-->
                        <!--                        </div>-->
                        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Masuk</button>
                        <!--                        <hr class="my-4">-->
                        <!--                        <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i-->
                        <!--                                    class="fab fa-google mr-2"></i> Sign in with Google-->
                        <!--                        </button>-->
                        <!--                        <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i-->
                        <!--                                    class="fab fa-facebook-f mr-2"></i> Sign in with Facebook-->
                        <!--                        </button>-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
