<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:delete_title')?></h4>
        </div>        
        <?php echo form_open( $delete_url, array("id" => "form_beginning_balances")); ?>
        <div class="modal-body">
            <p><?php echo lang('global:delete_confirm')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->No_Invoice ?>">
        </div>
        <div class="modal-footer"> 
        	<a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>
            <button type="submit" class="btn btn-danger"><?php echo lang('buttons:delete')?></button>        
    	</div>
        <?php echo form_close() ?>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {
			
				$("form[id=\"form_beginning_balances\"]").on("submit", function(e){
					e.preventDefault();	

					$.post( $(this).attr("action"), $(this).serialize(), function( response, status, xhr ){

						var response = $.parseJSON(response);
	
						if( "error" == response.status ){
							$.alert_error(response.message);
						} else {
							$.alert_success( response.message );						
						}
						
						$( "#ajax-modal" ).remove();
						$("body").removeClass("modal-open");

						$( "#dt-table-details" ).DataTable().ajax.reload();
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>