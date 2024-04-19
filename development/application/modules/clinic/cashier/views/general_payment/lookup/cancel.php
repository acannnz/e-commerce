<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('general_payment:cancel_heading') ?></h4>
        </div>
		<?php echo form_open( current_url(), array("name" => "form_cancel", "id"=>"form_cancel") ); ?>
        <div class="modal-body">
            <input type="hidden" id="NoBukti" value="<?php echo $item->cashier->NoBukti ?>" />
            <div class="form-group">
                <label class="col-lg-3 control-label">Username <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input  type="text" id="Approve_User" name="" autocomplete="off" placeholder="" class="form-control" required="required">
                </div>
        	</div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Password <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="password" id="Approve_Pswd" name="" placeholder="" class="form-control" required="required">
                </div>
        	</div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Alasan Batal <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <textarea id="Approve_Reason" name="" class="form-control" required="required"></textarea>
                </div>
        	</div>
        </div>
        <div class="modal-footer">
			<div class="form-group">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-danger btn-block"><?php echo lang("buttons:process") ?></button>
                </div>
                <div class="col-lg-6">
                    <button class="btn btn-default btn-block" data-dismiss="modal"><?php echo lang("buttons:close") ?></button>
                </div>
        	</div>
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
				
				var data_post = {
					'f' : {
						"NoBukti" : $("#NoBukti").val(),	
						"Approve_User" : $("#Approve_User").val(),	
						"Approve_Pswd" : $("#Approve_Pswd").val(),	
						"Approve_Reason" : $("#Approve_Reason").val(),	
					}
				} 
				
				$.post( $(this).attr("action"), data_post, function( response, status, xhr ){
					
					var response = $.parseJSON( response );
					if( "error" == response.status ){
						
						if ( response.state == 1 )
						{
							$.each( response.message, function( index, value ){
								$.alert_error( value );	
							});
	
						} else {
							$.alert_error( response.message );
						}
						
						return false;
					}
					
					$.alert_success( response.message );
					
					var NoBukti = response.NoBukti;
					setTimeout(function(){
						document.location.href = "<?php echo base_url("cashier/general-payment/edit") ?>/" + NoBukti;
						}, 3000 );
				});	
			});
		});
})( jQuery );
//]]>
</script>
