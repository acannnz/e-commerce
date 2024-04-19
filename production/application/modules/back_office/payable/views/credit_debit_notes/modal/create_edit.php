<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog  modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h3 class="modal-title"><?php echo lang('credit_debit_notes:modal_heading') ?></h3>
        </div>
        <div class="modal-body">
            <?php echo $form_child ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

