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
						var product_object = {
							"Barang_ID" : _response.Barang_ID,
							"Kode_Barang" : _response.Kode_Barang,
							"Nama_Barang" : _response.Nama_Barang,
							"Satuan" : _response.Satuan,
							"JmlObat" : 1,
							"Disc" : 0.00,
							"Total" : _response.Harga,
							"Stok" :  _response.Qty_Stok,
							"TglED" : "",
							"Dosis" : "",
							"Dosis2" : "",
							"NamaResepObat" : _response.Nama_Barang,
							"Keterangan" : "UMUM",
							"HNA" : _response.HNA,
							"HPP" : _response.HPP,
							"Harga" : _response.Harga,
							"HargaOrig" : _response.HargaOrig,
							"HargaPersediaan" : _response.HargaPersediaan,
							"KelompokJenis" : _response.KelompokJenis
						};
						
						$("#product_object").data("product", product_object);
						
						$("#Nama_Barang").val( _response.Nama_Barang );
						$("#JmlObat").val(1);
						if(_response.Qty_Stok < 15)
						{
							$("#Stok").css("background","red");
						}
						else{
							$("#Stok").css("background","transparent");
						}
						$("#Stok").val( _response.Qty_Stok );
						$("#Harga").val( mask_number.currency_add( _response.Harga ) );
						$("#Disc").val( 0 );
						
						//console.log($("#product_object").data("product"));
											
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

