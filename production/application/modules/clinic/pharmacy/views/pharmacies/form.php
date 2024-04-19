<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (@$is_edit)
{
	$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->TglReg);
	$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->JamReg ); 
	
	$item->TglReg = $date->format('Y-m-d');
	$item->JamReg = $time->format('H:i:s');
}

?>

<?php echo form_open( current_url(), array("name" => "form_pharmacy") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:registration_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9">
                <div class="input-group">
	                <input type="text" id="NoRegistrasi" name="f[NoRegistrasi]" value="<?php echo @$item->NoRegistrasi ?>" placeholder="" class="form-control"  readonly="readonly">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_registration ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="registration" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:mr_number_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:patient_name_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$patient->NamaPasien ?>" placeholder="" class="form-control patient" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:address_label') ?></label>
            <div class="col-lg-9">
                <textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo @$patient->Alamat ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:gender_label') ?></label>
            <div class="col-lg-2">
            	<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" disabled>
                	<option value="F" <?php echo @$patient->JenisKelamin == "F"  ? "selected" : NULL  ?>>Perempuan</option>
                	<option value="M" <?php echo @$patient->JenisKelamin == "M"  ? "selected" : NULL  ?>>Laki-laki</option>
                </select>
            </div>
		</div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:dob_label') ?></label>
            <div class="col-lg-3">
                <input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$patient->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
            </div>
            <label class="col-lg-1 control-label text-center"><?php echo lang('pharmacy:age_label') ?></label>
            <div class="col-lg-1">
                <input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
            </div>
            <label class="col-lg-1 control-label"><?php echo lang('pharmacy:year_label') ?></label>
            <div class="col-lg-1">
                <input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
                <input type="hidden" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>">
            </div>
            <label class="col-lg-1 control-label"><?php echo lang('pharmacy:month_label') ?></label>
        </div>    
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:type_patient_label') ?></label>
            <div class="col-lg-3">
            	<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control" disabled="disabled">
                	<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
                	<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:company_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation" disabled="disabled">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('pharmacy:card_number_label') ?></label>
            <div class="col-md-6">
                <input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card" readonly>
            </div>
        </div>
	</div>
    
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('pharmacy:doctor_label') ?></label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$pharmacy->DokterID ?>" class="doctor_sender">
                    <input type="text" id="DocterName" value="<?php echo @$pharmacy->Nama_Supplier ?>" placeholder="" class="form-control">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>   
        <div class="form-group">
            <label class="col-lg-3 control-label">Section Asal</label>
            <div class="col-lg-9">
            	<select id="SectionID" name="f[SectionID]" class="form-control patient">
                	<option value=""></option>
                	<?php if(!empty($option_section)): foreach($option_section as $row):?>
                	<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->SectionID ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
		</div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Resep</label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="text" id="NoResep" value="<?php echo @$resep->NoResep ?>" placeholder="" class="form-control prescription">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_prescription ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="prescription" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Tanggal/Jam</label>
            <div class="col-lg-6">
                <input type="text" id="WaktuResep"  value="<?php echo @$resep->Tanggal." ". @$resep->jam ?>" placeholder="" class="form-control cooperation" disabled="disabled">
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <input type="checkbox" id="Cyto" name="f[Cyto]" value="1" <?php echo @$resep->Cyto == 1 ? "Checked" : NULL ?> class="" disabled="disabled"><label for="Cyto">Cyto</label>
                </div>
            </div>
        </div>
        <?php /*?><div class="form-group">
            <div class="col-md-3">
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <input type="checkbox" id="Paket" name="f[Paket]" value="1" <?php echo @$item->Paket == 1 ? "Checked" : NULL ?> class=""><label for="Paket">Paket</label>
                </div>
            </div>
        </div>        
        <div class="form-group">
            <label class="col-lg-3 control-label">Paket Obat</label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="hidden" id="PaketObat" name="f[PaketObat]" value="<?php echo @$pharmacy->PaketObat ?>" class="package">
                    <input type="text" id="PaketName" value="<?php echo @$pharmacy->PaketName ?>" placeholder="" class="form-control package">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_package ?>" data-toggle="lookup-ajax-modal" class="btn btn-default package" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="clear_package" class="btn btn-default"><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div><?php */?>
        <div class="page-subtitle">
            <h3 class="text-primary">Detail Pembelian</h3>
        </div>
        <div class="form-group">
            <div class="col-md-offset-3 col-md-3">
                <div class="checkbox">
                    <input type="checkbox" id="CheckTambahRacikan" value="1" class=""><label for="CheckTambahRacikan"> Tambah Racikan</label>
                </div>
            </div>
		</div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Nama Racikan</label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="text" id="NamaResepObat" placeholder="" class="form-control detail_form" readonly>
                    <span class="input-group-btn">
	                    <a href="javascript:;" id="BtnTambahRacikan" class="btn btn-default disabled"><i class="fa fa-plus"> Tambah Racikan</i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Nama Obat</label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="hidden" id="product_object" data-product="{}" class="detail_form">
                    <input type="text" id="Nama_Barang" placeholder="" class="form-control detail_form">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_products ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="detail_form" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Qty</label>
            <div class="col-lg-3">
                <input type="number" id="JmlObat" name="d[JmlObat]" placeholder="" class="form-control detail_form">
            </div>
            <label class="col-lg-3 control-label text-center">Stok</label>
            <div class="col-lg-3">
                <input type="number" id="Stok" name="d[Stok]" placeholder="" class="form-control detail_form" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Harga Jual</label>
            <div class="col-lg-3">
                <input type="text" id="Harga" name="d[Harga]" placeholder="" class="form-control detail_form">
            </div>
            <label class="col-lg-3 control-label text-center">Diskon</label>
            <div class="col-lg-3">
                <div class="input-group">
                    <input type="number" id="Disc" name="d[Disc]" placeholder="" class="form-control detail_form">
                    <span class="input-group-btn">
	                    <a href="javascript:;" class="btn btn-default" >%</a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Dosis</label>
            <div class="col-lg-3">
            	<select id="Dosis" name="d[Dosis]" class="form-control detail_form select2">
                	<option value=""></option>
                	<?php if(!empty($option_dosis)): foreach($option_dosis as $row):?>
                	<option value="<?php echo $row->Dosis ?>" <?php echo $row->Dosis == @$item->Dosis ? "selected" : NULL  ?>><?php echo $row->Dosis ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
            <label class="col-lg-3 control-label text-center">Aturan</label>
            <div class="col-lg-3">
                <input type="text" id="Dosis2" name="d[Dosis2]" placeholder="" class="form-control detail_form">
            </div>
        </div>
        <div class="form-group">
        	<a href="javascript:;" id="add_product" class="btn btn-primary btn-block">Masukan Obat</a>
        </div>
    </div>
</div>
<?php echo modules::run("pharmacy/pharmacies/details/index", @$item ) ?>
<div class="form-group">
    <div class="col-lg-12 text-right">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	

		$( document ).ready(function(e) {			
				$("#CheckTambahRacikan").on("change", function(e){
					if ( $(this).is(':checked') )
					{
						$("#NamaResepObat").prop("readonly", false);
						$("#BtnTambahRacikan").removeClass("disabled");
					} else {
						$("#NamaResepObat").val('');
						$("#NamaResepObat").prop("readonly", true);
						$("#BtnTambahRacikan").addClass("disabled");
					}
				});

				$("#Paket").on("change", function(e){
					if ( $(this).is(':checked') )
					{
						$(".package").prop("disabled");
						$("a.package").removeClass("disabled");
					} else {
						$(".package").val('');
						$(".package").prop("readonly", true);
						$("a.package").addClass("disabled");
					}
				});
												
						
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