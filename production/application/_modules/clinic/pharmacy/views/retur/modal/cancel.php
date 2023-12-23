<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:cancel_title')?></h4>
        </div>        
        <?php echo form_open(current_url()); ?>
        <div class="modal-body">
            <p><?php echo lang('global:cancel_confirm')?></p>            
            <div class="form-group">
            	<label class="col-md-2">Alasan</label>
                <div class="col-md-10">
                	<textarea name="AlasanBatal" class="form-control" required="required" ><?php echo $item->AlasanBatal ?></textarea>
                </div>
            </div>
            <input type="hidden" name="confirm" value="<?php echo $item->NoBukti ?>">
            <input type="hidden" name="r_url" value="<?php echo base_url( "pharmacy/item-usage/view/$item->NoBukti" ) ?>">    
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6 col-sm-12">
            	<button type="submit" class="btn btn-block btn-danger"><?php echo lang('buttons:cancel')?></button>
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