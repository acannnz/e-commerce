<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('cash_bank_expense:voucher_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
				
					try{
						
						data = {
							"No_Voucher" : _response.No_Voucher,
							"Tgl_Voucher" : _response.Tgl_Voucher,
							"Sisa" : _response.Sisa,
							"Debit" : _response.Sisa,
							"Kredit" : 0,
							"Saldo" : 0,
							"Nama_Supplier" : _response.Nama_Supplier,
							"JenisHutang_ID" : _response.JenisHutang_ID,
							"Akun_ID" : _response.Akun_ID
						};
						
						$( "#dt_vouchers" ).DataTable().row.add( data ).draw();

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

