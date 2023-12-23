<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('closing:page')?></h4>
        </div>        
        
        <div class="modal-body">
            <p id="closing_confirm"><?php echo lang('closing:closing_confirm')?></p>            
            <div class="form-group">
                <label class="col-md-3"><?php echo lang("closing:username_label")?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <input type="text" id="username" name="username" class="form-control" autocomplete="off" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3"><?php echo lang("closing:password_label")?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <input type="password" id="password" name="password" class="form-control" required="required"/>
                </div>
            </div>
        </div>
        <div class="modal-footer"> 
            <button id="submit-closing" type="submit" class="btn btn-danger" ><?php echo lang('buttons:process')?></a>
            <button  class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></button>        
        </div>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		
		$( document ).ready(function(e) {
				
				closing_period = $("#closing_date").val();
				closing_confirm = $("#closing_confirm");
				closing_confirm_str = closing_confirm.html();
				
				closing_confirm.html( closing_confirm_str.replace(/%s/gi, closing_period) ); 
				
				
				$("#submit-closing").on("click", function(e){
					e.preventDefault();						
								
					var data_post = $("#form_closing").serializeArray();
						data_post.push({
									"name" : "approver[username]",
									"value" : $("#username").val()
								});
						data_post.push({
									"name" : "approver[password]", 
									"value" : $("#password").val()
								});

					$.post($("#form_closing").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON( response );

						if( response.status == "error"){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url('general-ledger/closing'); ?>" ;
							
							}, 300 );
						
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 