<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-body">
	<script type="text/javascript">//<![CDATA[
	function lookupbox_row_selected( _response ){
		if( _response ){

			try {					
				$.each( _response, function(index, value){
					
					var add_data = {	
						"Barang_ID" : value.Barang_ID,
						"Kode_Barang" : value.Kode_Barang,
						"Nama_Barang" : value.Nama_Barang,
						"Konversi" :  value.Konversi || 1,
						"Kode_Satuan" : value.Kode_Satuan || "",
						"Stock_Akhir" : value.Qty_Stok,
						"Qty_Opname" : 0,
						"Selisih" : Math.abs( parseFloat(value.Qty_Stok) - 0 ),
						"Harga" : value.Harga_Beli,
						"Kategori" : value.Nama_Kategori,
						"Harga_Rata" : value.HRataRata,
						"Tgl_Expired" : value.Exp_Date,
						"Keterangan" : "",
					};
					$("#dt_detail_opname").DataTable().row.add( add_data ).draw();
				});
				
				$("#ajaxModal").modal('hide');
			
			} catch (e){console.log(e);}
		}
	}
	//]]></script>
	<?php echo $this->load->view( "references/item/modal/lookup_multiple" ) ?>
</div>
<div class="modal-footer">
	<?php echo lang('patients:referrer_lookup_helper') ?>
</div>

