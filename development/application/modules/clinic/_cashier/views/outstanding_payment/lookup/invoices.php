<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Invoice</h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {			
							
						$("#NoInvoice").val( _response.NoBukti );
						$("#TanggalInvoice").val( _response.Jam.substr(0,19) );
						$("#NoReg").val( _response.NoReg );
						$("#NRM").val( _response.NRM );
						$("#NamaPasien").val( _response.NamaPasien );
						$("#JenisKerjasama").val( _response.JenisKerjasama);
						
						$("#NilaiAwal").html( parseFloat(_response.NilaiOutStanding).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
						$("#NilaiAkumulaiPembayaran").html( parseFloat(_response.NilaiAkumulaiPembayaran).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
						
						var obligation = parseFloat(_response.NilaiOutStanding) - parseFloat(_response.NilaiAkumulaiPembayaran);
						$("#Obligation").html( parseFloat(obligation).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
						$("#Remain").html( parseFloat(0).toFixed(2) );
						$("#NilaiPembayaran").val( parseFloat(obligation).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
						$("#NilaiPembayaran").focus();
							
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "cashier/outstanding-payment/lookup_invoice_datatable", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('outstanding_payment:invoice_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

