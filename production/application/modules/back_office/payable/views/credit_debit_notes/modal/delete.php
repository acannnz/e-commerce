<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('credit_debit_notes:delete_title')?></h4>
        </div>        
        
			<?php echo form_open( $delete_url, array("id" => 'form_cancel', "name" => 'form_cancel') ); ?>
            <div class="modal-body">
                <p><?php echo lang('credit_debit_notes:delete_credit_debit_note')?></p>            
                <input type="hidden" id="confirm" name="confirm" value="<?php echo $item->No_Voucher ?>">
                <div class="form-group">
                	<label class="col-md-3"><?php echo lang("credit_debit_notes:delete_reasons_label")?> <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                    	<textarea id="cancel_description" name="cancel_description" class="form-control" autocomplete="off" required="required" ></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="btn btn-danger" ><?php echo lang('buttons:process')?></a>
                <button  class="btn btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></button>        
            </div>
            <?php echo form_close() ?>
        
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		
		$( document ).ready(function(e) {
							
				$("form[name=\"form_cancel\"]").on("submit", function(e){
					e.preventDefault();						
					
					var data_post = {
							"nota" : {},
							"voucher" : {},
							"factur" : {},
						};
					
					data_post.nota = {
						"No_Voucher" : $("#confirm").val(),
						"Supplier_ID" : $("#Supplier_ID").val(),
						"Nilai" : $("#Nilai").val(),
						"Sisa" : $("#Nilai").val(),
						"Akun_ID" : $("#Akun_ID").val(),
						"Keterangan" : $("#cancel_description").val(),
						"Tgl_Voucher"  : $("#Tgl_Voucher").val(),
						"Tgl_Tempo"  : $("#Tgl_Tempo").val(),
						"Tgl_Update" : $("#Tgl_Voucher").val(),
					};
					
					var voucher_data = $( "#dt_vouchers" ).DataTable().rows().data();
					
					voucher_data.each(function(value, index){
						// Get Voucher from WebStroge
						data_post.voucher[ index ] = webStroge.sessionGetItem( value.No_Voucher + "_Header" ); 
						// Get Voucher detail (Factur) from WebStroge
						// (No_Voucher,No_Bukti,Tgl_transaksi,JTransaksi_ID,NilaiAsal,Debit,Kredit,Keterangan,sectionID)
						data_post.factur[ value.No_Voucher ] = {}
						$.each(webStroge.sessionGetItem( value.No_Voucher + "_Detail" ), function(_index, _value){
							data_post.factur[ value.No_Voucher ][ _index ] = {
									"No_Voucher" : _value.No_Voucher,
									"No_Faktur" : _value.No_Faktur,
									"Sisa" : _value.Sisa,
									"Debit" : _value.Debit,
									"Kredit" : _value.Kredit,
								}
						}); 
					});
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON( response );

						if( response.status == "error"){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						setTimeout(function(){
													
							document.location.href = "<?php echo $redirect_url; ?>";
							
							}, 300 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 