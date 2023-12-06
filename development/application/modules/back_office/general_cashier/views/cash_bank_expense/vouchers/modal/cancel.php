<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('cash_bank_income:invoice_cancel_title')?></h4>
        </div>        
        
			<?php echo form_open( $cancel_url, array("id" => 'form_cancel', "name" => 'form_cancel') ); ?>
            <div class="modal-body">
                <p><?php echo lang('cash_bank_income:cancel_confirm')?></p>            
                <input type="hidden" name="confirm" value="<?php echo $item->No_Bukti ?>">
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="btn btn-danger" ><?php echo lang('buttons:process')?></a>
                <button  class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></button>        
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
							
				$("form[name=\"form_cancel\"]").on("submit", function(e){
					e.preventDefault();						
				
					$.post($(this).attr("action"), $(this).serializeArray(), function( response, status, xhr ){

						var response = $.parseJSON( response );

						if( response.status == "error"){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						setTimeout(function(){
													
							document.location.href = "<?php echo $redirect_url; ?>";
							
							}, 300 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 