<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Data Supplier </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

						$("#PenanggungNRM").val( _response.NRM );
						$("#PenanggungNama").val( _response.NamaPasien );
						$("#PenanggungAlamat").val( _response.Alamat );
						$("#PenanggungTelp").val( _response.Phone );
						$("#PenanggungNoKTP").val( _response.NoIdentitas );
						$("#PenanggungNoKartu").val( _response.NoAnggota );
					
					} catch (e){console.log(e);}

					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/patients/lookup_insurers", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

