<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:delete_title')?></h4>
        </div>        
        <?php echo form_open_multipart( current_url(), array("id" => "form_settings_field_confirm", "name" => "form_settings_field_confirm")); ?>
        <div class="modal-body">
            <p><?php echo lang('global:delete_confirm')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->id ?>">  
        </div>
        <div class="modal-footer"> 
        	<button type="submit" class="btn btn-danger"><?php echo lang('buttons:delete')?></button>        
    		<a href="<?php echo base_url( "settings/fields/update/{$department->id}/{$item->id}" ) ?>" data-toggle="ajax-modal" class="btn btn-default"><?php echo lang('button:cancel')?></a>
        </div>
        <?php echo form_close() ?>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

