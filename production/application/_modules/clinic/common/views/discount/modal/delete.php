<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:delete_title')?></h4>
        </div>        
        <?php echo form_open(current_url()); ?>
        <div class="modal-body">
            <p><?php echo lang('global:delete_confirm')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->id ?>">
            <input type="hidden" name="r_url" value="<?php echo base_url( 'common/suppliers' ) ?>">    
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