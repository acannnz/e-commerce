<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo 'Batal Amprah'?></h4>
        </div>        
        <?php echo form_open(current_url(), ['id' => 'form_cancel_request']); ?>
        <div class="modal-body">
            <p><?php echo 'Apakah Anda yakin ingin membatalkan Amprah ini ?'?></p>            
            <input type="hidden" name="confirm" value="1" >
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6 col-sm-12">
            	<button type="submit" class="btn btn-block btn-danger"><?php echo lang('buttons:delete')?></button>
            </div>
            <div class="col-md-6 col-sm-12">
            	<a href="javascript:;" class="btn btn-block btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>
            </div>
        </div>
        <?php echo form_close() ?>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
			$('#form_cancel_request').on('submit', function(e){
				e.preventDefault();
					
				$.post($(this).attr("action"), $(this).serialize(), function( response, status, xhr ){
					if( "error" == response.status ){
						$.alert_error( response.message );
						return false;
					}						
					$.alert_success( response.message );						
					setTimeout(function(){													
						document.location.href = "<?php echo base_url("inquiry/request-list/{$type}"); ?>";
					}, 300 );
				});	
			});	
		});	
})( jQuery );
//]]>
</script>