<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php /*?><div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Barang </h4>
        </div><?php */?>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				
				if( _response ){
					//console.log(_response);
					
					try {
						$("#Supplier_Name").val( _response.Kode + ' - ' +_response.Nama || '' );
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

