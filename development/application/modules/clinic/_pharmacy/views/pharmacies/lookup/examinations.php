<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Pemeriksaan</h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					
						
						$("#NoReg").val( _response.NoReg );
						$("#NRM").val( _response.NRM);
						$("#NamaPasien").val( _response.NamaPasien );
						$("#Alamat").val( _response.Alamat );
						$("#JenisKelamin").val( _response.Gender );
						$("#TglLahir").val( _response.TglLahir.substr(0,10) );
						$("#UmurThn").val( _response.UmurThn );
						$("#UmurBln").val( _response.UmurBln );
						$("#KTP").val( _response.PasienKTP );
						$("#JenisKerjasamaID").val( _response.JenisKerjasamaID );
						$("#CustomerKerjasamaID").val( _response.CustomerKerjasamaID );
						$("#KodePerusahaan").val( _response.KodePerusahaan );
						$("#Nama_Customer").val( _response.Nama_Customer );
						$("#NoKartu").val( _response.NoAnggota );
						$("#SectionID").val( _response.SectionID );
						$("#DokterID").val( _response.DokterID );
						$("#DocterName").val( _response.Nama_Supplier );
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "pharmacy/examinations/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

