<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('reservations:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
                    var _response = JSON.parse(response);
					//console.log(_response);
                    if( _response ){
                                var _form = $( "form[name=\"form_reservations\"]" );
								
                                
                                _form.find( "select[name=\"f[UntukSectionID]\"]" ).val( _response.SectionID );
                                _form.find( "input[name=\"f[UntukDokterID]\"]" ).val( _response.DokterID );
								_form.find( "input[name=\"p[NamaDokter]\"]" ).val( _response.Nama_Supplier );
								
								_form.find( "#UntukTanggal" ).val( _response.Tanggal.substr(0,10) );
								_form.find( "#UntukHari" ).val(_response.Hari);
								_form.find( "#WaktuID" ).val(_response.WaktuID);
								_form.find( "#NoUrut" ).val(_response.NoAntrianTerakhir + 1);
								
                                $( '#lookup-ajax-modal' ).remove();
								$("body").removeClass("modal-open");
                    }
			}
			//]]></script>
            <?php echo Modules::run( "schedules/lookup_doctor_schedule", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('reservations:lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
