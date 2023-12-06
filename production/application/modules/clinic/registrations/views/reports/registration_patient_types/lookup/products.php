<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($resource);exit;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('registrations:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
			<script type="text/javascript">//<![CDATA[
            function lookupbox_row_selected( response ){
                var _product = JSON.parse(response);
                if( _product ){
					
					var _form = $( "form[name=\"form_report_sales\"]" );
					
					_form.find( "input[name=\"f[product_code]\"]" ).val( _product.code);
					_form.find( "input[name=\"f[product_name]\"]" ).val( _product.name);

					$( '#lookup-ajax-modal' ).remove();
					
                }
                
            }
            //]]></script>
            <?php echo Modules::run( "products/products/lookup") ?>        	
        </div>
        <div class="modal-footer">
        	<?php echo lang('registrations:lookup_helper') ?>
        </div>
    </div>
</div>

