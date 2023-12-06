<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Produk </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

					var add_data = {
							"Barang_ID" : _response.Barang_ID,
							"Kode_Barang" : _response.Kode_Barang,
							"Nama_Barang" : _response.Nama_Barang,
							"Satuan" : _response.Satuan,
							"Qty" : 1,
							"Harga_Satuan" : _response.Harga_Jual,
							"Disc_Persen" : 0.00,
							"Jumlah" : _response.Harga_Jual,
							"Stok" :  _response.Qty_Stok,
							"Dosis" : "",
						};
					
					$("#dt_products").DataTable().row.add( add_data ).draw(true);
					
					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "pharmacy/products/lookup_product_section", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

