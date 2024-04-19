<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('postings:cancel_page')?></h4>
        </div>        
        
        <div class="modal-body">
			<div class="row">
            <p><?php echo lang('postings:cancel_posting_confirm')?></p>            
            <div class="form-group">
                <label class="col-md-3"><?php echo lang("postings:username_label")?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <input type="text" id="username" name="username" class="form-control" autocomplete="off" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3"><?php echo lang("postings:password_label")?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <input type="password" id="password" name="password" class="form-control" required="required"/>
                </div>
            </div>
			</div>
        </div>
        <div class="modal-footer"> 
            <div class="row">
				<div class="form-group">
					<div class="col-md-6">
						<button  class="btn btn-default btn-block" data-dismiss="modal"><?php echo lang('buttons:close')?></button>        
					</div>
					<div class="col-md-6">
						<button id="submit-posting" type="submit" class="btn btn-danger btn-block" ><?php echo lang('buttons:process')?></button>
					</div>
				</div>
			</div>     
        </div>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		
		$( document ).ready(function(e) {
							
				$("#submit-posting").on("click", function(e){
					e.preventDefault();						
								
					var data_post = $("#form_posting_cancel").serializeArray();
						data_post.push({
									"name" : "approver[username]",
									"value" : $("#username").val()
								});
						data_post.push({
									"name" : "approver[password]", 
									"value" : $("#password").val()
								});

					$.post($("#form_posting_cancel").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON( response );

						if( response.status == "error"){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						$("#dt-postings").DataTable().ajax.reload();
						$("#form-ajax-modal").remove();
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 