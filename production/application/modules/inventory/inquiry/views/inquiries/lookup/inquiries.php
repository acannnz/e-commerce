<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Amprahan </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					
						
						$("#NoBuktiAmprah").val( _response.NoBukti );
						$("#Lokasi_Asal").val( _response.Lokasi_Asal );
						$("#Lokasi_Tujuan").val( _response.Lokasi_Tujuan );
						$("#TanggalAmprah").val( _response.Tanggal.substr(0,11) );
						$("#SectionAsal").val( _response.SectionAsalID );
						$("#SectionAsalName").val( _response.SectionAsalName );
						$("#KeteranganAmprah").val( _response.Keterangan);

						console.log(_response.detail);
						
						$("#dt_details").DataTable().clear().draw(true);
						$("#dt_details").DataTable().rows.add( _response.detail ).draw(true);
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "inquiry/pharmacy/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

