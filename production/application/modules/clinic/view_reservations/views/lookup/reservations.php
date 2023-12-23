<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('registrations:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( reservation_number ){
				if( reservation_number != "" ){
					try{
						<?php if( isset($is_modal) ): ?>
						lookup_ajax_modal.hide();
						form_ajax_modal.show("<?php base_url( "registrations/create" ) ?>?reservation_number=" + reservation_number, function(){
										
							}, form_ajax_modal);
						<?php else: ?>
						$( '#lookup-ajax-modal' ).remove();
						window.location = "<?php base_url( "registrations/create" ) ?>?reservation_number=" + reservation_number;
						<?php endif ?>
					} catch(e){}
				}
			}
			//]]></script>
            <?php echo Modules::run( "reservations/lookup", "registrations" ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('registrations:lookup_helper') ?>
        </div>
    </div>
</div>

