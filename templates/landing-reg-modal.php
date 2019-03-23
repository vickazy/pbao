<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/23/2019
 * Time: 7:15 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="frmModalReg" method="post">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-center">Pendaftaran</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="frm-result"></div>
                    <div class="form-horizontal">
                        <!--                    <h5 class="heading first">Informasi Akun</h5>-->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label" for="unama"><i class="fa fa-address-card"></i></label>
                                    <input class="form-control" id="unama" name="unama" placeholder="Nama lengkap"
                                           type="text" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label" for="uemail"><i class="fa fa-at"></i></label>
                                    <input class="form-control" id="uemail" name="uemail" placeholder="Email"
                                           type="email"
                                           required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="uwa"><i
                                                class="fab fa-whatsapp"></i></label>
                                    <input class="form-control" id="uwa" name="uwa" placeholder="Nomor whatsapp"
                                           type="text" maxlength="15" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label" for="upass"><i class="fa fa-lock"></i></label>
                                    <input class="form-control" id="upass" name="upass" placeholder="Kata sandi"
                                           type="password" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="upass2"><i class="fa fa-lock"></i></label>
                                    <input class="form-control" id="upass2" name="upass2"
                                           placeholder="Konfirmasi kata sandi" type="password" required>
                                </div>
                            </div>
                        </div>
                        <!--                    <h5 class="heading">Informasi Personal</h5>-->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label" for="udate"><i class="fa fa-calendar"></i></label>
                                    <input data-provide="datepicker" class="form-control datepicker" id="udate"
                                           name="udate"
                                           placeholder="Tanggal lahir" type="text" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="ujk"><i class="fa fa-transgender"></i></label>
                                    <select class="form-control selectpicker" id="ujk" name="ujk" required>
                                        <option value="1">Laki-laki</option>
                                        <option value="2">Perempuan</option>
                                    </select>
                                    <!--<input class="form-control" id="ujk" name="ujk" placeholder="Jenis kelamin" type="text"-->
                                    <!--required>-->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label" for="uaddr"><i
                                                class="fa fa-map-marker-alt"></i></label>
                                    <input class="form-control" id="uaddr" name="uaddr" placeholder="Domisili"
                                           type="text"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label" for="uwhy"><i class="fa fa-comment"></i></label>
                                    <textarea class="form-control" id="uwhy" name="uwhy" placeholder="Alasan ingin ikut"
                                              rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-xl btn-primary">Daftar</button>
                    <button type="button" class="btn btn-xl btn-default btn-can-reg" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
