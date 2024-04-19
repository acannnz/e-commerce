<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('vouchers:factur_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response);
				if( _response ){
					
					// cek apakah faktur sudah dipilih
					check = $("#dt_voucher_details").DataTable().rows( function ( idx, data, node ) {
							return data.No_Faktur === _response.No_Faktur ?	true : false;
						} ).data();
					
					if ( check.any() )
					{	
						message = "<?php echo lang("vouchers:factur_already_selected")?>";
						$.alert_error( message.replace(/%s/g, _response.No_Faktur) );
						return;
					}
					
					try{

						var rowIndex = "<?php echo $trId ?>" ;
						data = {
							"No_Bukti" : _response.No_Faktur,
							"Tgl_transaksi" : _response.Tgl_Faktur,
							"Keterangan" : _response.Keterangan,
							"Debit" : 0,
							"Kredit" : _response.Nilai_Faktur,
							"JenisHutang_ID" : _response.JenisHutang_ID
						};
							
						if ( rowIndex != "" )
						{							
							$("#dt_voucher_details").DataTable().row( rowIndex ).data( data ).draw();
						} else {
							$("#dt_voucher_details").DataTable().row.add( data ).draw();
						}
						
					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open");
					
				}
			}

			function lookupbox_multiple_selected( response ){
				if( response ){
					
					var number = '';
					$.each( response, function(index, value){ // cek apakah faktur sudah dipilih
						check = $("#dt_voucher_details").DataTable().rows( function ( idx, data, node ) {
								return data.No_Faktur === value.No_Faktur ?	true : false;
							} ).data();
						
						if ( check.any() )
						{
							check.each(function (value, index) {
								number += ' '+ value.No_Faktur;
							});
						}
					});
					
					if ( number != '' ) // jika ada no faktur, maka tampil pesan
					{
						message = "<?php echo lang("vouchers:factur_already_selected")?>";
						$.alert_error( message.replace(/%s/g, number) );
						return
					}
					
					try{
						
						data_table = [];
						$.each(response, function (index, value) {
							data = {
									"No_Bukti" : value.No_Faktur,
									"Tgl_transaksi" : value.Tgl_Faktur,
									"Keterangan" : value.Keterangan,
									"Debit" : 0,
									"Kredit" : value.Nilai_Faktur,
									"JenisHutang_ID" : value.JenisHutang_ID
								};
								
							data_table[index] = data;
						});
				
						$("#dt_voucher_details").DataTable().clear().draw();
						$("#dt_voucher_details").DataTable().rows.add( data_table ).draw();
							
					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open");
					
				}			
			}
			
			//]]></script>
            <?php  echo Modules::run( "payable/factur/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
(function( $ ){

		$( document ).ready(function(e) {

			if ( $("#Supplier_ID").val() == '' || $("#Supplier_ID").val() == 0 )
			{

				$( '#lookup-ajax-modal' ).remove();
				$("body").removeClass("modal-open");

				$.alert_error("<?php echo lang("vouchers:supplier_not_selected")?>");
				ajax_modal.show("<?php echo @$lookup_suppliers ?>")
			}

		});
	})( jQuery );
//]]>
</script>

</script>