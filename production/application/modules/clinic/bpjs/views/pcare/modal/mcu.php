<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
.panel-group .panel .panel-body {
    display: block !important;
}
</style>

<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Data Pemeriksaan Lab</h4>
            </div>
            <form class="form-horizontal form-disable-enter" id="insertLab" novalidate="novalidate">
				<div class="modal-body" style="height:80%; overflow-y:auto">
                    <input type="hidden" id="KdMCU" name="KdMCU" value="0">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Kode PPK</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="labKdPPK" name="labKdPPK" disabled="">
                                <span class="input-group-btn" style="width:70%; max-width:50px;">
                                    <input class="form-control" id="labNmPPK" type="text" disabled="disabled">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="divInsertLab">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Tanggal Pelayanan</label>
                            <div class="col-md-3">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker valid" id="labTglPelayanan" placeholder="yyyy-MM-dd" maxlength="10">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseTekanan" aria-expanded="false" class="collapsed">
                                            Tekanan Darah (Tensi)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTekanan" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label">Sistole</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control valid" id="TDSis" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="TDSis" value="0" maxlength="3">
                                            </div>
                                            <label class="col-sm-1 control-label">mmHg</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label">Diastole</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control valid" id="TDDias" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="TDDias" value="0" maxlength="3">
                                            </div>
                                            <label class="col-sm-1 control-label">mmHg</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseGula" class="collapsed" aria-expanded="false">
                                            Gula Darah
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseGula" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Gula Darah Sewaktu</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control valid" id="gulaDarahSewaktu" name="gulaDarahSewaktu" value="0" maxlength="10" onfocusout="HasilPemeriksaangulaDarahS();">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr/dl</label>
                                            <label class="col-sm-1 control-label" id="hasilgulaDarahSewaktu"></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Gula Darah Puasa</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control valid" id="gulaDarahPuasa" name="gulaDarahPuasa" value="0" maxlength="10" onfocusout="HasilPemeriksaangulaDarahP();">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr/dl</label>
                                            <label class="col-sm-1 control-label" id="hasilgulaDarahPuasa"></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Gula Darah Post Prandial</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control valid" id="gulaDarahPostPrandial" name="gulaDarahPostPrandial" value="0" maxlength="10" onfocusout="HasilPemeriksaangulaDarahPP();">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr/dl</label>
                                            <label class="col-sm-1 control-label" id="hasilgulaDarahPostPrandial"></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">HbA1c</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control valid" id="hbA1c" name="hbA1c" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr/dl</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseRadiologi" class="collapsed" aria-expanded="false">
                                            Radiologi
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseRadiologi" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label">Foto</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control" id="RFoto" name="RFoto"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseDarah" class="collapsed" aria-expanded="false">
                                            Darah Rutin
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseDarah" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Hemoglobin</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="darahRutinHemo" name="darahRutinHemo" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">gr %</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Leukosit</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="darahRutinLeu" name="darahRutinLeu" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">/mm3</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Eritrosit</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="darahRutinErit" name="darahRutinErit" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">juta/m3</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Laju Endap Darah</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="darahRutinLaju" name="darahRutinLaju" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mm/jam</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Hematokrit</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="darahRutinHema" name="darahRutinHema" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mm/jam</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Trombosit</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="darahRutinTrom" name="darahRutinTrom" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mm/jam</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseLemak" class="collapsed" aria-expanded="false">
                                            Lemak Darah
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseLemak" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">HDL</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="lemakDarahHDL" name="lemakDarahHDL" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">LDL</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="lemakDarahLDL" name="lemakDarahLDL" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Cholesterol Total</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="lemakDarahChol" name="lemakDarahChol" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Trigliserid</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="lemakDarahTrigli" name="lemakDarahTrigli" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseHati" class="collapsed" aria-expanded="false">
                                            Fungsi Hati
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseHati" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">SGOT</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiHatiSGOT" name="fungsiHatiSGOT" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">u/l</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">SGPT</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiHatiSGPT" name="fungsiHatiSGPT" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">u/l</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Gamma GT</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiHatiGamma" name="fungsiHatiGamma" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">u/l</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Protein Kualitatif</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiHatiProtKual" name="fungsiHatiProtKual" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mg/24jam</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Albumin</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiHatiAlbumin" name="fungsiHatiAlbumin" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">u/l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseGinjal" class="collapsed" aria-expanded="false">
                                            Fungsi Ginjal
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseGinjal" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Creatinin</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiGinjalCrea" name="fungsiGinjalCrea" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Ureum</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiGinjalUreum" name="fungsiGinjalUreum" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Asam Urat</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fungsiGinjalAsam" name="fungsiGinjalAsam" value="0" maxlength="10">
                                            </div>
                                            <label class="col-sm-1 control-label">mgr %</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseJantung" class="collapsed" aria-expanded="false">
                                            Fungsi Jantung / EKG
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseJantung" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label">ABI</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control valid" id="fungsiJantungABI" name="fungsiJantungABI"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label">EKG</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control valid" id="fungsiJantungEKG" name="fungsiJantungEKG"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label">Echo</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control valid" id="fungsiJantungEcho" name="fungsiJantungEcho"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseMata" class="collapsed" aria-expanded="false">
                                            Mata
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseMata" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Funduskopi</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control valid" id="funduskopi" name="funduskopi"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseLain" class="collapsed" aria-expanded="false">
                                            Pemeriksaan Lain
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseLain" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Lain-lain</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control valid" id="lain" name="lain"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseKesimpulan" class="collapsed" aria-expanded="false">
                                            Kesimpulan
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseKesimpulan" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Saran Dokter</label>
                                            <div class="col-sm-4">
                                                <textarea rows="2" maxlength="5000" class="form-control valid" id="saranDokter" name="saranDokter"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_simpanLab">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>