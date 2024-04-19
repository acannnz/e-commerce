<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('suppliers:supplier_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					/*
						{ data: "No_Voucher", className: "text-center", },
						{ data: "Tgl_Voucher", className: "text-center",  },
						{ data: "Nilai", className: "text-right" },
						{ data: "Debit", className: "text-right",  },
						{ data: "Kredit", className: "text-right", },
						{ data: "Saldo", className: "text-right",  },
						{ 
							data: "Keterangan", 
					*/
					
					try{
						
						data = {
							"No_Voucher" : _response.No_Voucher,
							"Tgl_Voucher" : _response.Tgl_Voucher,
							"Nilai" : _response.Sisa,
							"Debit" : 0,
							"Kredit" : 0,
							"Saldo" : _response.Sisa,
							"Keterangan" : _response.Keterangan,
							"JenisHutang_ID" : _response.JenisHutang_ID
						};
						
						$( "#dt_vouchers" ).DataTable().row.add( data ).draw();

					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "payable/vouchers/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('supplier:supplier_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

