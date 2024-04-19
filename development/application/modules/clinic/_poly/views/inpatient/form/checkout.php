<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row">	
	<div class="col-md-6">
		<div class="form-group">
        	<label class="control-label col-md-3"><?php echo lang('poly:doctor_label') ?> <span class="text-danger">*</span></label>
			<div class="col-md-9 input-group">
				<input type="hidden" id="DokterRawatID" name="f[DokterRawatID]" value="<?php echo @$item->DokterRawatID ?>" class="clear_doctor">
				<input type="text" id="NamaDokterRawatID" value="<?php echo @$item->NamaDokterRawatID ?>" placeholder="" class="form-control clear_doctor">
				<span class="input-group-btn">
					<a href="<?php echo @$lookup_doctor_checkout ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
					<a href="javascript:;" id="clear_doctor" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
				</span>
			</div>
		</div>	
		<div class="form-group">
        	<label class="control-label col-md-3"><?php echo lang('poly:final_diagnosis_label') ?> <span class="text-danger">*</span></label>
			<div class="col-md-9 input-group">
				<input type="hidden" id="DiagnosaAkhirID" name="f[DiagnosaAkhirID]" value="<?php echo @$item->DiagnosaAkhirID ?>" class="clear_icd">
				<input type="text" id="NamaDiagnosaAkhirID" value="<?php echo  @$item->DiagnosaAkhirID .' - '. @$item->NamaDiagnosaAkhirID ?>" placeholder="" class="form-control clear_icd">
				<span class="input-group-btn">
					<a href="<?php echo @$lookup_final_diagnosis ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
					<a href="javascript:;" id="clear_icd" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
				</span>
			</div>
		</div>	
		<div class="form-group">
        	<label class="control-label col-md-3"><?php echo lang('poly:explanation_label') ?> <span class="text-danger">*</span></label>
			<div class="col-md-9">
				<textarea id="KeteranganDiagnosa" class="form-control" rows="3"><?php echo @$item->KeteranganDiagnosa ?></textarea>
			</div>
		</div>	
	</div>
	<div class="col-md-6">
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxKeluar_Pulang" name="f[checkout]" value="1" <?php echo @$item->PxKeluar_Pulang == 1 ? "Checked" : NULL ?> class="" ><label for="PxKeluar_Pulang">Pulang</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxKeluar_PlgPaksa" name="f[checkout]" value="1" <?php echo @$item->PxKeluar_PlgPaksa == 1 ? "Checked" : NULL ?> class="" ><label for="PxKeluar_PlgPaksa">Pulang Paksa</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxKeluar_Dirujuk" name="f[checkout]" value="1" <?php echo @$item->PxKeluar_Dirujuk == 1 ? "Checked" : NULL ?> class=""><label for="PxKeluar_Dirujuk">Dirujuk</label>
                </div>
            </div>
        </div>  
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxMeninggal" name="f[checkout]" value="1" <?php echo @$item->PxMeninggal == 1 ? "Checked" : NULL ?> class=""><label for="PxMeninggal">Meninggal</label>
                </div>
            </div>
			<div id="MeninggalOption" style="display:none">
				<div class="col-md-3">
					<div class="radio">
						<input type="radio" id="MeninggalSblm48" name="MeninggalOption" value="1" <?php echo @$item->MeninggalSblm48 == 1 ? "Checked" : NULL ?> class=""><label for="MeninggalSblm48"> < 48 Jam</label>
					</div>
					<div class="radio">
						<input type="radio" id="MeninggalStl48" name="MeninggalOption" value="1" <?php echo @$item->MeninggalStl48 == 1 ? "Checked" : NULL ?> class=""><label for="MeninggalStl48"> > 48 Jam</label>
					</div>
				</div>
				<div class="col-md-2">
					<input type="text" id="MeninggalTgl" name="f[MeninggalTgl]" value="<?php echo  @$item->Meninggal ? @$item->MeninggalTgl : NULL ?>" class="form-control datepicker" placeholder="Tanggal">                
				</div>
				<div class="col-md-2">
					<input type="text" id="Meninggal_Jam" name="f[Meninggal_Jam]" value="<?php echo  @$item->Meninggal ? @$item->Meninggal_Jam : NULL ?>" class="form-control timepicker" placeholder="Pukul">                
				</div>
			</div>
        </div>      
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
				
		$( document ).ready(function(e) {				
			$('.btn-clear').on('click', function(){
				var _class = '.'+ $(this).attr('id');
				$(_class).val('');
			});
		
			$('input[name="f[checkout]"]').on('change', function(){
				if($(this).attr('id') == 'PxMeninggal'){
					$('#MeninggalOption').show();
					$('#MeninggalSblm48').prop("checked", true);
					$('#MeninggalTgl').val('');
					$('#Meninggal_Jam').val('');
					
				} else {
					$('#MeninggalOption').hide();
					$('input[name="MeninggalOption"]').prop("checked", false);
					$('#MeninggalTgl').val('');
					$('#Meninggal_Jam').val('');
				}
			});
			
			var check_state = '';
			$('input[name="f[checkout]"]').on('click', function(){
				if($(this).attr('id') == check_state){	
					$(this).prop("checked", false );
					check_state = '';
					
					if($(this).attr('id') == 'PxMeninggal'){
						$('#MeninggalOption').hide();
						$('input[name="MeninggalOption"]').prop("checked", false);
						$('#MeninggalTgl').val('');
						$('#Meninggal_Jam').val('');
					}
				
				} else {
					check_state = $(this).attr('id');
				}
			});
			
			if($('#PxMeninggal').is(':checked')){
				$('#MeninggalOption').show();
			}
		});

	})( jQuery );
//]]>
</script>