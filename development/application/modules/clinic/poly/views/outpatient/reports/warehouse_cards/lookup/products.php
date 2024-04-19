<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($resource);exit;
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('pharmacy:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
			<script type="text/javascript">//<![CDATA[
            function lookupbox_row_selected( response ){
                var _response = JSON.parse(response);
                if( _response ){
					
					$("#Barang_ID").val( _response.Barang_ID );
					$("#Kode_Barang").val( _response.Kode_Barang + " - " + _response.Nama_Barang );

					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
					
                }
                
            }
            //]]></script>
            <?php echo Modules::run( "poly/products/lookup_product_section", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('registrations:lookup_helper') ?>
        </div>
    </div>
</div>

