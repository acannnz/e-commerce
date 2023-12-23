<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Diskon </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse( response )
				//var btn_save = $(".btn_save").val();
				
				if( _response ){
					
					try {
						var add_data = {
								"IDDiscount" : _response.IDDiscount,
								"NamaDiscount" : _response.NamaDiscount,
								"IDDokter" :  $('#DokterID').val(),
								"NamaDokter" : $('#NamaDokter').val(),
								"IDJasa" : "",
								"NamaJasa" : "",
								"Kelas" : "",
								"Persen" : 0,
								"NilaiDiskon" : 0,
								"Keterangan" : "-",
								"DiskonKomponen" : _response.DiskonKomponen,
								"DiskonGroupJasa" : _response.DiskonGroupJasa,
								"DiskonTotal" : _response.DiskonTotal
							};
							
						if(_response.DiskonKomponen == 1 || _response.DiskonGroupJasa == 1)
						{
							$.get(
									'<?php echo base_url('cashier/general_payments/discount/get_service_by_doctor')?>', 
									{DokterID: $('#DokterID').val(), NoReg: $('#NoReg').val(), IDDiscount: _response.IDDiscount},
									function(data){
										var row = data.collection;
										add_data.IDJasa = row[0].JasaID;
										add_data.NamaJasa= row[0].JasaName;
										add_data.Keterangan = row[0].Keterangan;
										add_data.Kelas = row[0].NamaKelas;
										add_data.KomponenID = row[0].KomponenID;
										add_data.Tarif = row[0].Tarif;
										$("#dt_discounts").DataTable().row.add( add_data ).draw();
									}
								);
						} else {
							$("#dt_discounts").DataTable().row.add( add_data ).draw();
						}												
						
						$('.btn_save').prop("disabled", false);
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/discounts/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

