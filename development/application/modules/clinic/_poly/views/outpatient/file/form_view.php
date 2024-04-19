<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><i class="fa fa-file"></i> <?= $item->NamaFile?></h4>
        </div>
        <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-12">
					<object>
						<embed id="pdfID" type="text/html" width="100%" height="500" src="<?= base64_decode($item->Gambar) ?>" />
					</object>
                </div>
            </div>
        </div>
    </div>
</div>