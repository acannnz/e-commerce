<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Jasa </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {
												
						var dt = new Date();
						var time = "<?php echo date("Y-m-d")?> "+ dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
						
						var add_data = {
								"JasaID" : _response.JasaID,
								"JasaName" : _response.JasaName,
								"Qty" :  1,
								"Tarif" : _response.Harga_Baru,
								"DokterID" : $("#DokterID").val() || "",
								"Nama_Supplier" : $("#DocterName").val() || "",
								"User_id" : _response.user_id,
								"Jam" : time,
								"HargaOrig" : _response.Harga_Baru,
								"ListHargaID" : _response.ListHargaID,
								//"Disc" : ''
							};
						
						$("#dt_services").DataTable().row.add( add_data ).draw(true);
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/services/lookup_service_charges", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

