<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php /*?><div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Purchase Order </h4>
        </div><?php */?>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function row_selected( response ){
				var _response = JSON.parse(response)
				
				if( _response ){
					var id_order = _response.Order_ID;
					try{
						$.post("<?php echo base_url("inventory/transactions/receipt_item/query_select_detail_po/")  ?>" + id_order ,function(data, type, row){							
							
							var dummy = data.data;
							
							//console.log(dummy);
							
							for( var i = 0, len = dummy.length; i<len; i++){
								dummy[i].Kode_Barang
								dummy[i].Qty_Penerimaan = Number(dummy[i].Qty_Order) - Number(dummy[i].Qty_Terima) || 0;
								dummy[i].sub_total = Number(dummy[i].Qty_Penerimaan) * Number(dummy[i].Harga_Order) || 0;
								//console.log(dummy[0].Kode_Barang);
							
							var add_data = {
								"Barang_ID"			: dummy[i].Barang_id,
								"Kode_Barang" 		: dummy[i].Kode_Barang,
								"Nama_Barang" 		: dummy[i].Nama_Barang,
								"Kode_Satuan" 		: dummy[i].Kode_satuan,
								"Qty_PO" 			: dummy[i].Qty_Order || 0,
								"Qty_Telah_Terima"  : dummy[i].Qty_Terima || 0,
								"Qty_Penerimaan"  	: dummy[i].Qty_Penerimaan || 0,
								"Harga_Beli" 		: dummy[i].Harga_Order || 0,
								"Diskon_1" 			: 0,
								"Diskon_Rp" 		: 0,
								"sub_total" 		: dummy[i].sub_total || 0,
								"Exp_Date"			: "",
								"NoBatch" 			: "",
								"Konversi"			: dummy[i].Konversi || 0,
								"Kode_Pajak"		: dummy[i].Kode_Pajak || 0,
								"Rate_Pajak"		: dummy[i].Rate_Pajak || 0
								//"Disc" : ''
							};
							//console.log(add_data);
								$("#no_po").val( dummy[i].No_Order);
								$("#payment_method").val( dummy[i].Type_Pembayaran );
								$("#payment_term").val( dummy[i].Term_Pembayaran);
								$("#discount_type").val( dummy[i].Type_Diskon);
								$("#Nilai_DP").val( dummy[i].Nilai_DP);
								$("#Keterangan").val( dummy[i].Keterangan);
								$("#Posting_KG").val(0);
								$("#Posting_GL").val(0);
								$("#Currency_ID").val(1);
								$("#Order_ID").val( dummy[i].Order_ID);
								$("#No_Retur_Penjualan").val('');
								$("#Sumber_Penerimaan").val();
								$("#Lokasi_ID").val(1368);
								$("#Pembelian_Asset").val(0);
								$("#NoFakturPajak").val('');
								$("#TglFakturPajak").val(null);
								
								$("#dt_trans_receipt_item").DataTable().row.add( add_data ).draw(true);	

							}
						$("#ajaxModal").modal('hide');
						$("#dt_trans_receipt_item").DataTable().draw(true)
						})
					}catch(e){console.log(e)}
				}
			}
													  
			//]]></script>
            <?php echo $this->load->view( "transactions/receipt_item/datatable/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    <?php /*?></div>
	<!-- /.modal-content -->
</div><?php */?>
<!-- /.modal-dialog -->

