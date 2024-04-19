<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-body">
	<script type="text/javascript">//<![CDATA[
	function lookupbox_row_selected( response ){
		var _response = JSON.parse(response)
		if( _response ){

					try {
						
						var add_data = {
								"Kode_Barang" : _response.Kode_Barang,
								"Nama_Barang" : _response.Nama_Barang,
								"Konversi" :  _response.Konversi || 1,
								"Kode_Satuan" : _response.Kode_Satuan || "",
								"Qty" : 1,
								"Barang_ID" : _response.Barang_ID || 0,
								"Harga" : _response.Harga_Beli,
								"HRataRata" : _response.HRataRata
							};
						
						$("#dt_trans_mutation_return").DataTable().row.add( add_data ).draw(true);
						$("#ajaxModal").modal('hide');
					
					} catch (e){console.log();}
		}
	}
	//]]></script>
	<?php echo $this->load->view( "references/item/modal/lookup", true ) ?>
</div>
<div class="modal-footer">
	<?php echo lang('patients:referrer_lookup_helper') ?>
</div>

