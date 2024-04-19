<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('item_usage:list_item') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( _response ){
                    //var _response = JSON.parse(response);
					console.log(_response);
                    if( _response ){
						
						try{
								
							$("#dt_item_usages").DataTable().rows.add( _response ).draw(true);
							
							$( '#lookup-ajax-modal' ).remove();
							$("body").removeClass("modal-open").removeAttr("style");
						} catch(e){console.log(e)}
                    }
			}
			//]]></script>
            <?php echo Modules::run( "poly/products/lookup_product_section_usage", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('item_usage:lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
