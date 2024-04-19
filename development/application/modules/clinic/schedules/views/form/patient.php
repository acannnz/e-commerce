<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="page-subtitle">
    <h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo lang('registrations:patient_label') ?></h3>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:reservation_number_label') ?></label>
    <div class="col-lg-6">
        <input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:new_patient_label') ?></label>
    <div class="col-lg-3">
      <input type="checkbox" id="new_patient" name="f[PasienBaru]" value="1">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
    <div class="col-lg-6">
        <div class="input-group">
            <input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" required>
            <span class="input-group-btn">
                <a href="<?php $lookup_patient ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                <a href="javascript:;" id="clear_patient" class="btn btn-default" ><i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:patient_name_label') ?></label>
    <div class="col-lg-9">
        <input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$patient->NamaPasien ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:gender_label') ?></label>
    <div class="col-lg-1">
        <input type="text" id="JenisKelamin" name="p[JenisKelamin]" value="<?php echo @$patient->JenisKelamin ?>" placeholder="" class="form-control" required>
    </div>
    <label class="col-lg-1 control-label text-center"><?php echo lang('registrations:dob_label') ?></label>
    <div class="col-lg-2">
        <input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$patient->TglLahir ?>" placeholder="" class="form-control" required>
    </div>
    <label class="col-lg-1 control-label text-center"><?php echo lang('registrations:age_label') ?></label>
    <div class="col-lg-1">
        <input type="text" id="JenisKelamin" name="p[JenisKelamin]" value="<?php echo @$patient->age_years ?>" placeholder="" class="form-control" required>
    </div>
    <label class="col-lg-1 control-label"><?php echo lang('registrations:year_label') ?></label>
    <div class="col-lg-1">
        <input type="text" id="JenisKelamin" name="p[JenisKelamin]" value="<?php echo @$patient->age_months ?>" placeholder="" class="form-control" required>
    </div>
    <label class="col-lg-1 control-label"><?php echo lang('registrations:month_label') ?></label>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:address_label') ?></label>
    <div class="col-lg-9">
        <textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control"><?php echo @$patient->Alamat ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:phone_label') ?></label>
    <div class="col-lg-3">
        <input type="text" id="Phone" name="p[Phone]" value="<?php echo @$patient->Phone ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:type_patient_label') ?></label>
    <div class="col-lg-3">
        <select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
            <?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
            <option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
            <?php endforeach; endif;?>
        </select>
    </div>
    <label class="col-md-2 control-label text-center"><?php echo lang('registrations:card_number_label') ?></label>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control" required>
            <span class="input-group-btn">
                <a href="<?php $lookup_member_number ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                <a href="javascript:;" id="member_number" class="btn btn-default" ><i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('registrations:company_label') ?></label>
    <div class="col-lg-9">
        <div class="input-group">
            <input type="hidden" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>" >
            <input type="text" id="Perusahaan" name="c[Perusahaan]" value="<?php echo @$company->Perusahaan ?>" placeholder="" class="form-control" required>
            <span class="input-group-btn">
                <a href="<?php $lookup_company ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                <a href="javascript:;" id="clear_patient" class="btn btn-default" ><i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>
</div>