<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Konfirmasi Retur Transaksi</h4>
        </div>        
        <?php echo form_open(current_url()); ?>
        <div class="modal-body">
            <b>Apakah Anda yakin ingin Retur Transaksi ini?</b>            
            <input type="hidden" name="confirm" value="<?php echo $item->NoBukti ?>">
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6 col-sm-12">
            	<button type="submit" class="btn btn-block btn-danger">Konfirmasi</button>
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