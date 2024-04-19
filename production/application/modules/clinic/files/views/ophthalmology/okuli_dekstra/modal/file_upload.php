<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title text-primary"><?php echo lang('chart_file:upload_title')?></h4>
        </div>        
        <div class="modal-body">
            <?php echo @$form_child ?>            
        </div>
        <div class="modal-footer">
        	<div class="row">
    			<div class="col-md-12">
        			<a href="javascript:;" title="<?php echo lang("buttons:cancel") ?>" data-dismiss="modal" class="btn btn-block btn-danger"><?php echo lang("buttons:cancel") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>



