<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (@$is_edit)
{
	$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->Tanggal);
	$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->Jam ); 
	
	$item->Tanggal = $date->format('Y-m-d');
	$item->Jam = $time->format('H:i:s');
}

?>

<?php echo form_open( current_url(), array("name" => "form_poly") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="form-group">
        	<input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo config_item('section_id'); ?>" />
        	<input type="hidden" id="PasienKTP" name="f[PasienKTP]" value="<?php echo @$registration->PasienKTP ?>" />
            <label class="col-lg-3 control-label"><?php echo lang('poly:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:registration_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" id="RegNo" name="f[RegNo]" value="<?php echo @$item->RegNo ?>" placeholder="" class="form-control"  readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:mr_number_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:patient_name_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$patient->NamaPasien ?>" placeholder="" class="form-control patient" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:address_label') ?></label>
            <div class="col-lg-9">
                <textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo @$patient->Alamat ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:gender_label') ?></label>
            <div class="col-lg-2">
            	<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" disabled>
                	<option value="F" <?php echo @$patient->JenisKelamin == "F"  ? "selected" : NULL  ?>>Perempuan</option>
                	<option value="M" <?php echo @$patient->JenisKelamin == "M"  ? "selected" : NULL  ?>>Laki-laki</option>
                </select>
            </div>
		</div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:dob_label') ?></label>
            <div class="col-lg-3">
                <input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$patient->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
            </div>
            <label class="col-lg-1 control-label text-center"><?php echo lang('poly:age_label') ?></label>
            <div class="col-lg-1">
                <input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
            </div>
            <label class="col-lg-1 control-label"><?php echo lang('poly:year_label') ?></label>
            <div class="col-lg-1">
                <input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
                <input type="hidden" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>">
            </div>
            <label class="col-lg-1 control-label"><?php echo lang('poly:month_label') ?></label>
        </div>    
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:type_patient_label') ?></label>
            <div class="col-lg-3">
            	<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control" disabled="disabled">
                	<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
                	<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:company_code_label') ?></label>
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
                    <input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KdKelas ?>"  class="cooperation">
                    <input type="text" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>" placeholder="" class="form-control cooperation" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:company_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation" disabled="disabled">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:card_number_label') ?></label>
            <div class="col-md-6">
                <input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card" readonly>
            </div>
            <label class="col-lg-2 control-label text-center"><?php echo lang('poly:group_label') ?></label>
            <div class="col-lg-1">
            	<select id="Klp" name="p[Klp]" class="form-control patient" disabled>
                	<option value="E" <?php echo @$patient->Klp == "E"  ? "selected" : NULL  ?>>E</option>
                	<option value="S" <?php echo @$patient->Klp == "S"  ? "selected" : NULL  ?>>S</option>
                	<option value="C1" <?php echo @$patient->Klp == "C1"  ? "selected" : NULL  ?>>C1</option>
                	<option value="C2" <?php echo @$patient->Klp == "C2"  ? "selected" : NULL  ?>>C2</option>
                	<option value="C3" <?php echo @$patient->Klp == "C3"  ? "selected" : NULL  ?>>C3</option>
                	<option value="C4" <?php echo @$patient->Klp == "C4"  ? "selected" : NULL  ?>>C4</option>
                	<option value="C5" <?php echo @$patient->Klp == "C5"  ? "selected" : NULL  ?>>C5</option>
                	<option value="C5" <?php echo @$patient->Klp == "C5"  ? "selected" : NULL  ?>>C6</option>
                </select>
            </div>
        </div>
	</div>
    
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('poly:doctor_label') ?></label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$poly->DokterID ?>" class="doctor_sender">
                    <input type="text" id="DocterName" value="<?php echo @$poly->Nama_Supplier ?>" placeholder="" class="form-control">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Symptom</label>
            <div class="col-lg-9">
                <textarea id="Symptom" name="f[Symptom]" placeholder="" class="form-control"><?php echo @$item->Symptom ?></textarea>
            </div>
        </div>
<?php /*?>        <div class="form-group">
            <label class="col-lg-3 control-label">Kecelakaan</label>
            <div class="col-lg-9">
            	<select id="Kecelakaan" name="f[Kecelakaan]" class="form-control">
                	<option value="None" <?php echo @$item->Kecelakaan == "None"  ? "selected" : NULL  ?>>None</option>
                	<option value="Lalu Lintas" <?php echo @$item->Kecelakaan == "Lalu Lintas"  ? "selected" : NULL  ?>>Lalu Lintas</option>
                	<option value="Kerja" <?php echo @$item->Kecelakaan == "Kerja"  ? "selected" : NULL  ?>>Kerja</option>
                	<option value="Rumah Tangga" <?php echo @$item->Kecelakaan == "Rumah Tangga"  ? "selected" : NULL  ?>>Rumah Tangga</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-3">
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <input type="hidden" name="f[KasusPertama]" value="0" >
                    <input type="checkbox" id="KasusPertama" name="f[KasusPertama]" value="1" <?php echo @$item->KasusPertama == 1 ? "Checked" : NULL ?> class=""><label for="KasusPertama">Kasus Pertama</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <input type="hidden" name="f[DOA]" value="0" >
                    <input type="checkbox" id="DOA" name="f[DOA]" value="1" <?php echo @$item->DOA == 1 ? "Checked" : NULL ?> class=""><label for="DOA">DOA</label>
                </div>
            </div>
        </div>        
        <div class="form-group">
            <div class="col-md-3">
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <input type="hidden" name="f[Dentist]" value="0" >
                    <input type="checkbox" id="Dentist" name="f[Dentist]" value="1" <?php echo @$item->Dentist == 1 ? "Checked" : NULL ?> class=""><label for="Dentist">Dentist</label>
                </div>
            </div>
            <div class="col-md-1">
                <div class="checkbox">
                    <input type="hidden" name="f[DC]" value="0" >
                    <input type="checkbox" id="DC" name="f[DC]" value="1" <?php echo @$item->DC == 1 ? "Checked" : NULL ?> class="" ><label for="DC">DC</label>
                </div>
            </div>
            <div class="col-md-1">
				<input type="text" id="DC_Hari" name="f[DC_Hari]" value="<?php echo @$item->DC_Hari ?>" placeholder="" class="form-control">            
            </div>
            <label class="control-label col-md-1">Hari</label>
        </div><?php */?>        
        <div class="form-group">
            <label class="col-lg-3 control-label">Therapi</label>
            <div class="col-lg-9">
                <textarea id="Therapi" name="f[Therapi]" placeholder="" class="form-control"><?php echo @$item->Therapi ?></textarea>
            </div>
        </div>
    </div>
</div>
<div class="row form-group">	
    <div class="panel panel-default">
        <div class="panel-body">
            <ul id="tab-poly" class="nav nav-tabs nav-justified">
                <li class="active"><a href="#poly-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> Diagnosa</a></li>
                <li><a href="#poly-tab2" data-toggle="tab"><i class="fa fa-stethoscope"></i> Jasa</a></li>
                <li><a href="#poly-tab3" data-toggle="tab"><i class="fa fa-file-o"></i> Perawat</a></li>
                <li><a href="#poly-tab4" data-toggle="tab"><i class="fa fa-medkit"></i> Resep</a></li>
                <!--<li><a href="#poly-tab5" data-toggle="tab"><i class="fa fa-files-o"></i> Penunjang</a></li>-->
                <li><a href="#poly-tab6" data-toggle="tab"><i class="fa fa-medkit"></i> BHP</a></li>
                <li><a href="#poly-tab7" data-toggle="tab"><i class="fa fa-files-o"></i> Memo</a></li>
                <li><a href="#poly-tab8" data-toggle="tab"><i class="fa fa-wheelchair"></i> CheckOut</a></li>
            </ul>
            <div class="tab-content">
                <div id="poly-tab1" class="tab-pane tab-pane-padding active">
                	<?php echo modules::run("{$nameroutes}/polies/diagnosis/edit", @$item ) ?>
                </div>
                <div id="poly-tab2" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/service_polies/edit", @$item ) ?>
                </div>
                <div id="poly-tab3" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/nurse/edit", @$item ) ?>
                </div>
                <div id="poly-tab4" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/prescriptions/edit", @$item ) ?>
                </div>
                <div id="poly-tab5" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/helpers/edit", @$item ) ?>
                </div>
                <div id="poly-tab6" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/consumables/edit", @$item ) ?>
                </div>
                <div id="poly-tab7" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/memo/edit", @$item ) ?>
                </div>
                <div id="poly-tab8" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("{$nameroutes}/polies/checkout/edit", @$item ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
        <button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><b><i class="fa fa-sign-out"></i><?php echo lang( 'buttons:back' ) ?></b></button>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {			
			
				function getAge(dateString) {
				  var now = new Date();
				  var today = new Date(now.getYear(),now.getMonth(),now.getDate());
				
				  var yearNow = now.getYear();
				  var monthNow = now.getMonth();
				  var dateNow = now.getDate();
					// yyyy-mm-dd
				  var dob = new Date(dateString.substring(0,4), //yyyy
									 dateString.substring(5,7)-1, //mm               
									 dateString.substring(8,10)    //dd            
									 );
					
					
				  var yearDob = dob.getYear();
				  var monthDob = dob.getMonth();
				  var dateDob = dob.getDate();
				  var age = {};
				  var ageString = "";
				  var yearString = "";
				  var monthString = "";
				  var dayString = "";
				
				
				  yearAge = yearNow - yearDob;
				
				  if (monthNow >= monthDob)
					var monthAge = monthNow - monthDob;
				  else {
					yearAge--;
					var monthAge = 12 + monthNow -monthDob;
				  }
				
				  if (dateNow >= dateDob)
					var dateAge = dateNow - dateDob;
				  else {
					monthAge--;
					var dateAge = 31 + dateNow - dateDob;
				
					if (monthAge < 0) {
					  monthAge = 11;
					  yearAge--;
					}
				  }
				
				  age = {
					  years: yearAge,
					  months: monthAge,
					  days: dateAge
					  };
				  
				  return age;
				 
				}

			});

	})( jQuery );
//]]>
</script>