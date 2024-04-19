<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_memo")) ?>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lihat Memo</h4>
        </div>
        <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label col-md-3">Memo</label>
                        <div class="col-md-9">
                        	<textarea id="Memo" name="Memo" class="form-control" readonly="readonly"><?php echo @$item->Memo ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row form-group">
            	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<?php echo form_close()?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {
								
			});

	})( jQuery );
//]]>
</script>