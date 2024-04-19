<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Data Registrasi </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

							$("#NRM").val( _response.NRM );
							$("#NoReg").val( _response.NoReg );
							$("#NamaPasien").val( _response.NamaPasien );
							$("#NamaAlias").val( _response.NamaAlias );
							$("#TglReg").val( _response.TglReg );
							$("#Alamat").val( _response.Alamat );
							//$("#TglLahir").val( _response.TglLahir.substr(0, 10) );

							$("#PenanggungAlamat").val( _response.PenanggungAlamat );

							$( '#lookup-ajax-modal' ).remove();
							$("body").removeClass("modal-open").removeAttr("style");
					
							window.location.href = "<?php echo base_url() ?>reports/reports/patient_certificate/"+ _response.NoReg + "";
		
					} catch (e){console.log(e);}			
				}
			}
			//]]></script>
            <?php echo $view_datatable ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

