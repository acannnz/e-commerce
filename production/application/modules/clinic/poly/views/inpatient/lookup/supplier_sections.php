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
					
					var index = '<?php echo @$index ?>';
					
					try {				
						
						if ( index = '') index =  $('#dt_registration_section tr:last').attr('id');

						data = $("#dt_registration_section").DataTable().row(index).data();

						data.DokterID = _response.Kode_Supplier;
						data.Nama_Supplier = _response.Nama_Supplier;
						
						$("#dt_registration_section").DataTable().row( index ).data( data ).draw(true);
										
					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/suppliers/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

