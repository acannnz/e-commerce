<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Data Barang </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( _response ){
				if( _response ){
					
					$.each(_response, function(i, v){
						var check = $("#dt_details").DataTable().rows( function ( idx, data, node ) {
								if( data.Barang_ID == v.Barang_ID )
								{ 								
									return true;
								}										
							} ).data();
							
							if ( ! check.any() )
							{
								$( "#dt_details" ).DataTable().row.add( v ).draw();
							} 
					
					});
					

					//$( "#dt_details" ).DataTable().clear().draw();
					//$( "#dt_details" ).DataTable().rows.add( _response ).draw();
					
					
					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
				}

			}
			//]]></script>
            <?php echo Modules::run( "inquiry/products/lookup_product_section_opname", true ) ?>
        </div>
        <?php /*?><div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div><?php */?>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

