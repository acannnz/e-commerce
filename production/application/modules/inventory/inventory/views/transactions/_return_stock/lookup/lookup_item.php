<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php /*?><div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Barang </h4>
        </div><?php */?>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					//console.log(_response);
					
					var _form 	  = $( "form[name=\"form_create_return\"]" );
					var id_lokasi = $("#input_section_id").val();
					
					console.log(id_lokasi);
					
					var id_barang = _response.Barang_ID;
					
					$.post("<?php echo base_url("inventory/transactions/return_stock/cek_stock/")  ?>" + id_lokasi + '/' + id_barang ,function(response){
						if(response.status == 'success'){																																
							try {
														
								var dt = new Date();
								var time = "<?php echo date("Y-m-d")?> "+ dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
								
								var add_data = {
										"Kode_Barang" : _response.Kode_Barang || 0,
										"Nama_Barang" : _response.Nama_Barang || 0,
										"Konversi" :  _response.Konversi ||1,
										"Nama_Kategori" : _response.Nama_Kategori || "",
										"Kode_Satuan" : _response.Kode_Satuan || "",
										"Qty_Retur" : 1,
										"Harga_Retur" : _response.Harga_Beli || 0,
										"Jumlah_Total": _response.Harga_Beli || 0,
										"Barang_ID" : _response.Barang_ID || 0,
										//"Disc" : ''
									};
									//console.log(add_data);
								
								$("#dt_trans_return_stock").DataTable().row.add( add_data ).draw(true);
								$("#ajaxModal").modal('hide');
							
							} catch (e){console.log();}
						}else{
							alert(response.message + '('+response.Nama_Barang + ')');
						}
					});
				}
			}
			//]]></script>
            <?php echo $this->load->view( "references/item/modal/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    <?php /*?></div>
	<!-- /.modal-content -->
</div><?php */?>
<!-- /.modal-dialog -->

