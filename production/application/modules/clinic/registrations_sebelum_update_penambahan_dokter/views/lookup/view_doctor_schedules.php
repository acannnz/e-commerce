<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Jadwal Dokter </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					
						
						//var NoUrut = _response.NoAntrianTerakhir <= 4 ? 4 : _response.NoAntrianTerakhir ;
						var params = {
								"SectionID" : _response.SectionID,
								"DokterID" : _response.DokterID,
								"WaktuID" : _response.WaktuID,
								"SectionName" : _response.SectionName,
								"Nama_Supplier" : _response.Nama_Supplier,
								"Keterangan" : _response.Keterangan,
								"NoAntri" : _response.NoUrut,
							};
						$("#btn_save").addClass('disabled', true);			
						$.post("<?php echo base_url('registrations/get_queue') ?>", params, function( response, status, xhr ){							
							if( "error" == response.status ){
								$.alert_error(response.message);
								$("#btn_save").removeClass('disabled');		
								return false
							}							
							var add_data = {
								"SectionID" : _response.SectionID,
								"DokterID" : _response.DokterID,
								"WaktuID" : _response.WaktuID,
								"SectionName" : _response.SectionName,
								"Nama_Supplier" : _response.Nama_Supplier,
								"Keterangan" : _response.Keterangan,
								"NoAntri" : response.queue,
							};
							
							$("#dt_registration_section").DataTable().row.add( add_data ).draw(true);
							
							$( '#lookup-ajax-modal' ).remove();
							$("body").removeClass("modal-open").removeAttr("style");
							$("#btn_save").removeClass('disabled');		
						});	
						
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "registrations/lookup_doctor_schedule", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

