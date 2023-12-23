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
					//console.log(_customer);
                    if( _response ){
						
						try{
                                
						$( "#DokterID" ).val( _response.Kode_Supplier );
						$( "#Nama_Supplier" ).val( _response.Nama_Supplier );
						$( "#Specialist" ).val( _response.SpesialisName );
						
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open");
						} catch(e){console.log(e)}
                    }
			}
			//]]></script>
            <?php echo Modules::run( "common/suppliers/lookup", true, $type ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('reservations:lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
