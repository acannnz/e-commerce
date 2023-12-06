<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:delete_title')?></h4>
        </div>        
        <?php echo form_open(current_url(), ['id' => 'form_delete_service']); ?>
        <div class="modal-body">
            <p><?php echo lang('global:delete_confirm')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->NoBukti ?>">
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6 col-sm-12">
            	<button type="submit" class="btn btn-block btn-danger"><?php echo lang('buttons:delete')?></button>
            </div>
            <div class="col-md-6 col-sm-12">
            	<a href="javascript:;" id="btn-delete-close" class="btn btn-block btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>
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
				$('#form_delete_service').on('submit', function(e){
					e.preventDefault();
					
					$.post($(this).prop('action'), $(this).serialize(), function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}							
												
						get_inpatient_examination();
						
						$.alert_success( response.message );
						$('#btn-delete-close').trigger('click');
						$('#btn-close-form').trigger('click');
					});
				});
				
				function get_inpatient_examination()
				{
					$.post('<?php echo $get_inpatient_examination; ?>', function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}							
						if( !response.collection ){
							$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
							return false
						}
						
						$('#dt_examination_inpatient').DataTable().clear().draw();
						$('#dt_examination_inpatient').DataTable().rows.add(response.collection).draw();
					});
				}
				
			});

	})( jQuery );
//]]>
</script>