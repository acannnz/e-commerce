<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//inititate
$data = array("iddiscount"=>$iddiscount, "noreg"=>$noreg);
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Discount Jasa </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				
				var IdIndex = "<?php echo $idindex ?>";
				var _response = JSON.parse(response)
				if( _response ){
					try {					
						var data = $("#dt_discounts").DataTable().row(IdIndex).data();
						
						data.IDJasa = _response.JasaID;
						data.NamaJasa= _response.JasaName;
						data.Keterangan = _response.Keterangan;
						data.Kelas = _response.NamaKelas;
						data.KomponenID = _response.KomponenID;
						data.Tarif = _response.Tarif;
						
						$("#dt_discounts").DataTable().row( IdIndex ).data( data ).draw(true);
						
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/discounts/lookup_discount_jasa", $data ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

