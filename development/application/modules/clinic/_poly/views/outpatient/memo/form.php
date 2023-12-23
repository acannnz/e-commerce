<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_memo")) ?>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Form Memo</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label">Memo</label>
                        <textarea id="Memo" name="Memo" class="form-control" required><?php echo @$item->Memo ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
				</div>
				<div class="col-md-6">
					<button type="submit" id="submit_memo" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
				</div>
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
								
				$("form[id=\"form_memo\"]").on("submit", function(e){
					e.preventDefault();	
					
					$("#submit_memo").prop("disabled", true);
					
					var data_post = { };
						data_post['f'] = {
							NOReg : "<?php echo $item->NoReg ?>",
							Tanggal :  "<?php echo date("Y-m-d")?>",
							Jam :"<?php echo date("Y-m-d H:i:s")?>",
							Memo : $("#Memo").val(),
							SectionID : "<?php echo $item->SectionID ?>",
						};
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							$("#submit_memo").prop("disabled", false);
							return false
						}
						
						$.alert_success( response.message );
						
						data_post['f']['NoUrut'] = response.NoUrut;
						data_post['f']['SectionName'] = "<?php echo $item->SectionName ?>";
						data_post['f']['Username'] = "<?php echo $user->Username ?>";
						$("#dt_memo").DataTable().row.add( data_post['f'] ).draw();
						
						// Close Form
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						
												
					})	
				});

			});

	})( jQuery );
//]]>
</script>