<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('general_cashier:lookup_invoice_title') ?></h4>
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
									"invoice_id" : _response.id,
									"invoice_number" : _response.invoice_number,
									"customer_id" : _response.customer_id,
									"customer_name" : _response.customer_name,
									"original_value" : _response.remain,
									"original_value_money" : Number(_response.remain).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"),
									"value" : 0,
									"value_money" :  0.00,
									"description" : _response.description,
								};
									
							
							$( table ).DataTable().row.add( data ).draw( true );												
						} else {
							var data = $( table ).DataTable().row( rowIndex ).data();
							data.invoice_id = _response.id;
							data.invoice_number = _response.invoice_number;
							data.customer_id =  _response.customer_id;
							data.customer_name =  _response.customer_name;
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
            <?php  echo Modules::run( "receivable/invoices/lookup_for_general_cashier", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

