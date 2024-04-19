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
            <div class="row form-group">
                <div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label col-md-3">No Resep</label>
                        <div class="col-md-9">
                        	<input type="text" id="NoUrut" name="NoUrut" class="form-control" value="<?php echo @$item->NoUrut?>"  readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Memo</label>
                        <div class="col-md-9">
                        	<textarea id="Memo" name="Memo" class="form-control" required="required"><?php echo @$item->Memo ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row form-group">
            	<button type="submit" id="submit_memo" class="btn btn-primary"><i class="fa fa-file"></i> Simpan</button>
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
								
				$("form[id=\"form_memo\"]").on("submit", function(e){
					e.preventDefault();	
					
					$("#submit_memo").prop("disabled", true);
					
					var d = new Date();
					var data_post = { };
						data_post['f'] = {
							"NOReg" : $("#NoReg").val(),
							"NoUrut" : $("#NoUrut").val(),
							"Tanggal" :  "<?php echo date("Y-m-d")?>",
							"Jam" :"<?php echo date("Y-m-d")?> "+ d.getHours() +':'+ d.getMinutes() +':'+ d.getSeconds(),
							"Memo" : $("#Memo").val(),
							"SectionID" : "SEC005",
						};
						
					console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							$("#submit_memo").prop("disabled", false);
							return false
						}
						
						$.alert_success( response.message );
						
						// Memasukan Data header reserp ke view tabel resep
						// Tambahkan nama supplier (nama dokter)
						data_post['f']['SectionName'] = "UGD";
						data_post['f']['Username'] = "<?php echo $user->Username ?>";
						$("#dt_memo").DataTable().row.add( data_post['f'] ).draw( true );
						
						// Close Form
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						
												
					})	
				});

			});

	})( jQuery );
//]]>
</script>