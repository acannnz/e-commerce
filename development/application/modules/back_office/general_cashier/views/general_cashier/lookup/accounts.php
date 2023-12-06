<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('accounts:account_lookup_title') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					var table = "#<?php echo $table ?>";
					var evidence_number = "<?php echo @$general_cashier->evidence_number ?>";
					var rowIndex = "<?php echo $trId ?>" ;
					
					try{
						
						if (evidence_number != "" )
						{
							var post_data = {
												"evidence_number" : evidence_number,
												"account_id" : _response.id,
												"normal_pos" : _response.normal_pos
											} 
													
							$.post("<?php echo $post_action ?>", {"detail_id" : rowIndex, "f": post_data}, function( response, status, xhr ){
								if( "error" == response.status ){
									return false
								}
								
								$( table ).DataTable().ajax.reload();
								
							});
						
						} else {
							
							if ( rowIndex != "" )
							{
								var data = $( table ).DataTable().row( rowIndex ).data();
										
								data.id = _response.id;
								data.account_id = _response.id;
								data.account_number = _response.account_number;
								data.account_name =  _response.account_name;
								
								$( table ).DataTable().row( rowIndex ).data( data ).draw( true );
								
							} else {
								
								$( table ).DataTable().row.add(
									{
										"id" : 0,
										"description" : "",
										"value" : '',
										"value_money" : '',
										"account_id" : _response.id,
										"account_number": _response.account_number,
										"account_name" : _response.account_name,
										"normal_pos" : _response.normal_pos,
										"integration_source" : _response.integration_source
									}
								).draw( false );
								
							}
						}

					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "general_ledger/accounts/lookup", true, "GC" ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

