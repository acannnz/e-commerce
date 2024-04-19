<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-body">
	<script type="text/javascript">//<![CDATA[
	function row_selected( response ){
		var _response = JSON.parse(response);
		
		if( _response ){
			try{
				
				var _form = $("#form_goods_receipt");
				
				_form.find("input[name=\"f[Order_ID]\"]").val( _response.Order_ID );
				_form.find("select[name=\"f[Lokasi_ID]\"]").val( _response.Gudang_ID );
				_form.find("input[name=\"f[No_Order]\"]").val( _response.No_Order );
				_form.find("input[name=\"f[Supplier_ID]\"]").val( _response.Supplier_ID );
				_form.find("input[name=\"f[Supplier_Name]\"]").val( _response.Kode_Supplier +" - "+ _response.Nama_Supplier);
				_form.find("input[name=\"f[Tgl_JatuhTempo]\"]").val( _response.Tgl_JatuhTempo.substr(0, 10) );
				
				$("#dt_trans_goods_receipt").DataTable().clear().draw();	
					
				$("#ajaxModal").modal('hide');
				
				$.post("<?php echo base_url("inventory/transactions/order/get_detail_collection")  ?>/"+ _response.Order_ID ,function(collection, type, row){							
						console.log(collection);
						$.each( collection, function(index, value){
							
							var add_data = {
								"Barang_ID"	: value.Barang_ID,
								"Kode_Barang" : value.Kode_Barang,
								"Nama_Barang" : value.Nama_Barang,
								"Kode_Satuan" : value.Kode_Satuan,
								"Qty_PO" : value.Qty_Order || 0,
								"Qty_Telah_Terima" : value.Qty_Tlh_Dibeli || 0,
								"Qty_Penerimaan" : value.Qty_Order - value.Qty_Tlh_Dibeli || 0,
								"Harga_Beli" : value.Harga_Order || 0,
								"Diskon_1" : 0,
								"Diskon_Rp" : 0,
								"sub_total" : value.Jumlah_Total || 0,
								"Exp_Date" : "",
								"JenisBarangID" : value.JenisBarangID,
								"Konversi" : value.Konversi
							};
	
							$("#dt_trans_goods_receipt").DataTable().row.add( add_data ).draw();	
	
						});
					})
					
			}catch(e){console.log(e)}
		}
	};
											  
	//]]></script>
	<?php echo $this->load->view( "transactions/goods_receipt/datatable/lookup_purchase_order", true ) ?>
</div>
<div class="modal-footer">
	<?php echo lang('patients:referrer_lookup_helper') ?>
</div>


