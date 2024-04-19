<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Merchan </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {			
							
						$("#IDBank").val( _response.ID );
						$("#NamaBank").val( _response.NamaBank );
						$("#AddCharge").val( _response.AddCharge_Kredit );
						
						var total = 0;
						$(".pay").each(function(index, element) {
							sub_total = mask_number.currency_remove( element.value );
							total = parseFloat(total) + sub_total;
						});
												
						$("#sub_total_pay").html( mask_number.currency_add(total) );						
						var addCharge = mask_number.currency_remove( $("#NilaiPembayaranCC").val() ) * parseFloat( $("#AddCharge").val() ) / 100;
						total = total + addCharge;
						
						$("#add_charge_pay").html( mask_number.currency_add(addCharge) );
						$("#grand_total_pay").html( mask_number.currency_add(total) );
							
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/merchan/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('drug_payment:merchan_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

