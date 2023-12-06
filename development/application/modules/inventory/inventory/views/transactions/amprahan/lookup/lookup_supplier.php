<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php /*?><div class="modal-dialog">
    <div class="modal-content"><?php */?>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				
				if( _response ){
					//console.log(_response);
					
					try {
						  $("#Kode_Supplier").val( _response.Kode || 0 );
						  $("#Nama_Supplier").val( _response.Nama || 0 );
						  $("#Supplier_ID").val( _response.Id || 0 );
						  $("#ajaxModal").modal('hide');
						  
					} catch (e){console.log();}

				}
			}
			//]]></script>
            <?php echo $this->load->view( "references/item_supplier/lookup/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    <?php /*?></div>
	<!-- /.modal-content -->
</div><?php */?>
<!-- /.modal-dialog -->

