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

						/*var add_data = {
							"Barang_ID" : _response.Barang_ID,
							"Kode_Barang" : _response.Kode_Barang,
							"Nama_Barang" : _response.Nama_Barang,
							"Satuan" : _response.Satuan,
							"Qty" : 1,
							"Harga_Satuan" : _response.Harga,
							"Disc_Persen" : 0.00,
							"Jumlah" : _response.Harga,
							"Stok" :  _response.Qty_Stok,
							"Dosis" : "",
						};
					
						$("#dt_products").DataTable().row.add( add_data ).draw(true);*/
						//JIKA STOK LEBIH KECIL DARI
						if( _response.Qty_Stok < <?php echo config_item('StokObatMinimum') ?>)
						{
							$("#Stok").css("background","#e74e40");
						}
						else{
							$("#Stok").css("background","transparent");
						}

						var product_object = {
							"Barang_ID" : _response.Barang_ID,
							"Kode_Barang" : _response.Kode_Barang,
							"Nama_Barang" : _response.Nama_Barang,
							"Satuan" : _response.Satuan,
							"Qty" : 1,
							"Harga_Satuan" : parseFloat(_response.Harga).toFixed(2),
							"Disc_Persen" : 0.00,
							"Jumlah" : parseFloat(_response.Harga).toFixed(2),
							"Stok" :  _response.Qty_Stok,
							"Dosis" : "",
							"NamaResepObat" : _response.Nama_Barang,
						};
						
						$("#product_object").data("product", product_object);
						
						$("#Nama_Barang").val( _response.Nama_Barang );
						$("#JmlObat").val(1);
						$("#Stok").val( _response.Qty_Stok );
						$("#Harga").val( parseFloat(_response.Harga).toFixed(2) );
						$("#Disc").val( 0 );
						
						$( '#lookup-ajax-modal' ).remove();
						// $("body").removeClass("modal-open").removeAttr("style");
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();
						$('body').removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "poly/products/lookup_product_section", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
