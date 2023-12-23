<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:cancel_title')?></h4>
        </div>        
        <?php echo form_open(current_url(), ['id' => 'form_cancel']); ?>
        <div class="modal-body">
            <p><?php echo lang('global:cancel_confirm')?></p>            
            <input type="hidden" name="confirm" value="<?php echo $item->NoReg ?>">
            <input type="hidden" name="r_url" value="<?php echo base_url( 'registrations' ) ?>">    
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6 col-sm-12">
            	<button type="submit" class="btn btn-block btn-danger"><?php echo lang('buttons:yes')?></button>
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
<script type="text/javascript">
	var formCancel = {
			post: function(fn = null){					
				$.post('<?php echo current_url() ?>', $('#form_cancel').serializeArray(), function( response, status, xhr ){							
					if( "error" == response.status ){
						$.alert_error(response.message);
						return false;
					}
					//refresh antrian di TV DISPLAY
					socket.send('queue_refresh');
					$.alert_success(response.message);
					setTimeout(function(){													
						document.location.href = "<?php echo base_url("registrations"); ?>";
					}, 300 );
						
					if($.isFunction(fn))
					{
						fn($('#form_cancel').serializeArray(), formCancel.afterPost);
					}
				});	
			},
			afterPost: function(){
				$.alert_success("Proses Batal Registrasi berhasil dilakukan...");
				//refresh antrian di TV DISPLAY
				socket.send('queue_refresh');
				setTimeout(function(){													
					document.location.href = "<?php echo base_url("registrations"); ?>";
				}, 300 );
			}
		}
		
	$('#form_cancel').on("submit", function(e){
		e.preventDefault();
		
		var jenisKerjasama = $('#JenisKerjasamaID').val();
						
		if(typeof bpjsBridgingRegistration !== 'undefined' && jenisKerjasama == 9 ){
			console.log(123);
			formCancel.post( bpjsRemoveRegistration.post);
		} else {
			formCancel.post();
		}					
	});
</script>