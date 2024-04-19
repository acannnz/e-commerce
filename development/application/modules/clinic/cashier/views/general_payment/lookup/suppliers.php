<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Data Supplier </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				
				var IdIndex = "<?php echo $iddiscount ?>";
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					
						var dt_row = $("#dt_discounts").DataTable().row(IdIndex).data();
						
						dt_row.IDDokter = _response.Kode_Supplier;
						dt_row.NamaDokter = _response.Nama_Supplier;
						if(dt_row.DiskonKomponen == 1 || dt_row.DiskonGroupJasa == 1)
						{
							$.get(
									'<?php echo base_url('cashier/general_payments/discount/get_service_by_doctor')?>', 
									{DokterID: _response.Kode_Supplier, NoReg: $('#NoReg').val(), IDDiscount: dt_row.IDDiscount},
									function(data){
										var row = data.collection;
										dt_row.IDJasa = row[0].JasaID;
										dt_row.NamaJasa= row[0].JasaName;
										dt_row.Keterangan = row[0].Keterangan;
										dt_row.Kelas = row[0].NamaKelas;
										dt_row.KomponenID = row[0].KomponenID;
										dt_row.Tarif = row[0].Tarif;
										$("#dt_discounts").DataTable().row( IdIndex ).data( dt_row ).draw();
									}
								);
						}							
						
						$("#dt_discounts").DataTable().row( IdIndex ).data( dt_row ).draw();
						
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/suppliers/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

