<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('reports:polyclinic_registration_modal_heading') ?></h4>
        </div>
        <div class="modal-body">
            <?php echo $form_child ?>
        </div>
        <div class="modal-footer"> 
            <div class="col-md-12 text-right">
            	<a href="javascript:;" class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>
            </div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

