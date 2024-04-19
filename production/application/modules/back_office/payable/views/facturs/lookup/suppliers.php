<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('suppliers:supplier_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try{
					
						$("#Supplier_ID").val( _response.Supplier_ID );
						$("#Kode_Supplier").val( _response.Kode_Supplier );
						$("#Nama_Supplier").val( _response.Nama_Supplier );
							

					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "common/suppliers/lookup_back_office", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('supplier:supplier_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

