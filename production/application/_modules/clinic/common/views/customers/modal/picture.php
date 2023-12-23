<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog <?php echo (isset($modal_type)) ? $modal_type : "modal-picture" ?>">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('customers:picture_heading') ?></h4>
        </div>
        <div class="modal-body">
            <?php echo @$form_child ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

