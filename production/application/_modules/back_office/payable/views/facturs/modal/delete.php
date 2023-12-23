<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('facturs:cancel_title')?></h4>
        </div>        
        
			<?php echo form_open( $cancel_url, array("id" => 'form_cancel', "name" => 'form_cancel') ); ?>
            <div class="modal-body">
                <p><?php echo lang('facturs:cancel_factur')?></p>            
                <input type="hidden" name="confirm" value="<?php echo $item->No_Faktur ?>">
                <div class="form-group">
                	<label class="col-md-3"><?php echo lang("facturs:username_label")?> <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                    	<input type="text" id="username" name="username" class="form-control" autocomplete="off" required="required" />
                    </div>
                </div>
                <div class="form-group">
                	<label class="col-md-3"><?php echo lang("facturs:password_label")?> <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                    	<input type="password" id="password" name="password" class="form-control" required="required"/>
                    </div>
                </div>
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
					
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();
					
					var today = d.getFullYear() + '/' +
						(month < 10 ? '0' : '') + month + '/' +
						(day < 10 ? '0' : '') + day;
						
				
					$.post($(this).attr("action"), $(this).serializeArray(), function( response, status, xhr ){

						var response = $.parseJSON( response );

						if( response.status == "error"){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						var No_Faktur = response.No_Faktur;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo $redirect_url; ?>";
							
							}, 300 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 