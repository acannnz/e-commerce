<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php if( isset( $success ) ): ?>
		<div class="modal-header bg-warning"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('registrations:proceed_confirm_title')?></h4>
        </div>        
        <?php echo form_open(current_url()); ?>
        <div class="modal-body">
            <p><?php echo lang('registrations:proceed_confirm_message')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->registration_number ?>">
            <input type="hidden" name="r_url" value="<?php echo base_url("examinations/proceed/{$item->registration_number}") ?>">    
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6 col-sm-12">
            	<?php if( 1 == $item->state ): ?><button type="submit" class="btn btn-block btn-warning"><?php echo lang('buttons:yes')?></button>
                <?php else: ?><button type="submit" class="btn btn-block btn-warning"><?php echo lang('buttons:yes')?></button>
                <?php endif ?>
            </div>
            <div class="col-md-6 col-sm-12">
            	<a href="javascript:;" class="btn btn-block btn-default" data-dismiss="modal"><?php echo lang('buttons:no')?></a>
            </div>
    	</div>
        <?php echo form_close() ?>
    	<?php else: ?>
        <?php echo Modules::run("system/alert"); ?>
        <?php endif ?>
    </div>
</div>

