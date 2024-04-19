<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('general_cashier:lookup_voucher_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					var table = "#<?php echo $table ?>";
					var rowIndex = "<?php echo $rowIndex ?>" ;
					
					try{
						
						if ( rowIndex == "" )
						{
							var data = {
									"voucher_id" : _response.id,
									"voucher_number" : _response.voucher_number,
									"supplier_id" : _response.supplier_id,
									"supplier_name" : _response.supplier_name,
									"original_value" : _response.remain,
									"original_value_money" : Number(_response.remain).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"),
									"value" : 0,
									"value_money" :  0.00,
									"description" : _response.description,
								};
									
							
							$( table ).DataTable().row.add( data ).draw( true );												
						} else {
							var data = $( table ).DataTable().row( rowIndex ).data();
							data.voucher_id = _response.id;
							data.voucher_number = _response.voucher_number;
							data.supplier_id =  _response.supplier_id;
							data.supplier_name =  _response.supplier_name;
							data.original_value =  _response.remain;
							data.original_value_money = Number(_response.remain).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
							data.value =  0;
							data.value_money =  0.00;
							data.description =  _response.description;
							
							$( table ).DataTable().row( rowIndex ).data( data ).draw( true );						
						}
													
					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "payable/vouchers/lookup_for_general_cashier", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

