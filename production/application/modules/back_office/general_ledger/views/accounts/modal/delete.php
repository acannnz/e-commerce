<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:delete_title')?></h4>
        </div>        
        <?php if (!$is_trans) :?>
        <?php echo form_open(current_url(), array("id" => "form-account", "name" => "form-account")); ?>
        <div class="modal-body">
            <p><?php echo lang('global:delete_confirm')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->Akun_ID ?>">
        </div>
        <div class="modal-footer"> 
        	<button type="submit" class="btn btn-danger"><?php echo lang('buttons:delete')?></button>
            <a href="javascript:;" class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>        
    	</div>
        <?php echo form_close() ?>
        
		<?php else : ?>
            <div class="modal-body">
                <p><?php echo lang('accounts:cannot_delete')?></p>            
            </div>
            <div class="modal-footer"> 
                <a href="javascript:;" class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>        
            </div>        
        <?php endif; ?>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
(function($){

	$(document).ready(function(e) {
		$("#form-account").on("submit", function(e){
			e.preventDefault();
			
			/*if ( !confirm("Apakah Anda yakin akan menghapus data ini ?"))
			{
				return false;
			}*/		

			$.post($(this).attr("action"), $(this).serialize(), function( response, status, xhr ){
							
				var response = $.parseJSON(response);
				if( "error" == response.status ){
					$.alert_error(response.message);
					return false
				}
				
				$.alert_success( response.message );
				
				var tree = $("#account_tree").jstree().get_selected(true);
				$("#account_tree").jstree().delete_node( tree[0]['id'] );
				
				//$("#account_tree").jstree(true).refresh();

				$("#form-ajax-modal").remove();				
				$("body").removeClass("modal-open");				
			});

		});
		
    });
})(jQuery);
</script>