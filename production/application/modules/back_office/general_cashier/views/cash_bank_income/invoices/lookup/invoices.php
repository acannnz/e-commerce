<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('cash_bank_income:invoice_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
				
					try{
						
						data = {
							"No_Invoice" : _response.No_Invoice,
							"Tgl_Invoice" : _response.Tgl_Invoice,
							"Sisa" : _response.Sisa,
							"Kredit" : _response.Sisa,
							"Debit" : 0,
							"Saldo" : 0,
							"Nama_Customer" : _response.Nama_Customer,
							"JenisPiutang_ID" : _response.JenisPiutang_ID,
							"Akun_ID" : _response.Akun_ID
						};
						
						$( "#dt_invoices" ).DataTable().row.add( data ).draw();

					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			} 
			//]]></script>
            <?php  echo $datatable_view ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('customer:customer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

