<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Resep </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					
						$("#NoResep").val( _response.NoResep );
						$("#WaktuResep").val( _response.Jam.substr(0,19) );
						$("#NoReg").val( _response.NoRegistrasi );
						$("#NRM").val( _response.NRM);
						$("#NamaPasien").val( _response.NamaPasien );
						$("#Alamat").val( _response.Alamat );
						$("#JenisKelamin").val( _response.Gender );
						$("#TglLahir").val( _response.TglLahir.substr(0,10) );
						$("#Umur").val( _response.UmurThn +' Tahun '+ _response.UmurBln +' Bulan' );
						$("#UmurThn").val( _response.UmurThn );
						$("#UmurBln").val( _response.UmurBln );
						$("#KTP").val( _response.KTP );
						$("#JenisKerjasamaID").val( _response.JenisKerjasamaID );
						$("#Nama_Customer").val( _response.Nama_Customer );
						$("#NoKartu").val( _response.NoKartu );
						$("#SectionID").val( _response.SectionID );
						$("#DokterID").val( _response.DokterID );
						$("#DocterName").val( _response.Nama_Supplier );
						if ( _response.Cyto == 1 ) $("#Cyto").prop("checked", true);
						if ( _response.IncludeJasa == 1 ) $("#IncludeJasa").prop("checked", true);
						
						var resep_detail = [{}];
						
						$.each( _response.resep_detail, function( index, value ){
							add_data = {
								"Barang_ID" : value.Barang_ID,
								"Kode_Barang" : value.Kode_Barang || "RACIKAN",
								"Nama_Barang" : value.Nama_Barang || value.NamaResepObat,
								"Satuan" : value.Satuan,
								"JmlObat" : value.Qty,
								"Harga" : parseFloat(value.Harga_Satuan).toFixed(2),
								"Disc" : 0.00,
								"BiayaResep" : 0.00,
								"Total" : parseFloat( Number(value.Qty) * parseFloat(value.Harga_Satuan) ).toFixed(2),
								"Stok" :  value.Stok,
								"TglED" : "",
								"Dosis" : value.Dosis,
								"Dosis2" : "",
								"NamaResepObat" : value.NamaResepObat || value.Nama_Barang,
								"Keterangan" : (value.Nama_Barang == value.NamaResepObat) && value.Satuan != 'RACIKAN'
												? "UMUM" : value.NamaResepObat,
								"HNA" : value.HNA,
								"HPP" : value.HPP,
								"Harga" : value.Harga,
								"HargaOrig" : value.HargaOrig,
								"HargaPersediaan" : value.HargaPersediaan,
								"KelompokJenis" : value.KelompokJenis,
							};
							
							add_data['HExt'] = mask_number.currency_ceil(add_data['Total']) - parseFloat(add_data['Total']);
							
							resep_detail[index] = add_data;
						});
						console.log(resep_detail);
												
						$("#dt_details").DataTable().clear().draw(true);
						$("#dt_details").DataTable().rows.add( resep_detail ).draw(true);
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "pharmacy/prescriptions/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

