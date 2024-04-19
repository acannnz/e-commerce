<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('customers:customer_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					/*
						{ data: "No_Invoice", className: "text-center", },
						{ data: "Tgl_Invoice", className: "text-center",  },
						{ data: "Nilai", className: "text-right" },
						{ data: "Debit", className: "text-right",  },
						{ data: "Kredit", className: "text-right", },
						{ data: "Saldo", className: "text-right",  },
						{ 
							data: "Keterangan", 
					*/
					
					try{
						
						data = {
							"No_Invoice" : _response.No_Invoice,
							"Tgl_Invoice" : _response.Tgl_Invoice,
							"Nilai" : _response.Sisa,
							"Debit" : 0,
							"Kredit" : 0,
							"Saldo" : _response.Sisa,
							"Keterangan" : _response.Keterangan,
							"JenisPiutang_ID" : _response.JenisPiutang_ID
						};
						
						$( "#dt_invoices" ).DataTable().row.add( data ).draw();

					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "receivable/invoices/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('customer:customer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

