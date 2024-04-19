<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
        <div class="modal-body">
			<script type="text/javascript">//<![CDATA[
            function lookupbox_row_selected( response ){
                var _response = JSON.parse(response);
                if( _response ){
					
					 $("#supplier").val( _response.Id );
					 $("#supplier_name").val( _response.Kode +' - '+ _response.Nama );

					$('#lookup-ajax-modal').remove();
					$("body").removeClass("modal-open").removeAttr("style");
					
                }
                
            }
            //]]></script>
            <?php echo $this->load->view( "references/item_supplier/lookup/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('registrations:lookup_helper') ?>
        </div>

