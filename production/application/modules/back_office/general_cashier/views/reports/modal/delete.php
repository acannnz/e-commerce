<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('credit_debit_notes:delete_title')?></h4>
        </div>        
        
        <?php if ($this->item->close_book) : ?>

            <div class="modal-body">
                <p><?php echo lang('credit_debit_notes:cannot_delete_close_data')?></p>            
            </div>
            <div class="modal-footer"> 
                <button  class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></button>        
            </div>

		<?php else : ?>
        
			<?php echo form_open(current_url()); ?>
            <div class="modal-body">
                <p><?php echo lang('credit_debit_notes:delete_credit_debit_note')?></p>            
                <input type="hidden" name="confirm" value="<?php echo $item->id ?>">
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="btn btn-danger" ><?php echo lang('buttons:delete')?></a>
                <button  class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></button>        
            </div>
            <?php echo form_close() ?>
        
        <?php endif; ?>
        
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->