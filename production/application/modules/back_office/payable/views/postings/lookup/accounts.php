<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog">
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
					
					var factur_number = "<?php echo @$factur->factur_number ?>";
					var rowIndex = "<?php echo $trId ?>" ;
					
					try{
						
						if (factur_number != "" )
						{
							var post_data = {
												"factur_number" : factur_number,
												"account_id" : _response.id,
												"normal_pos" : _response.normal_pos
											} 
													
							$.post("<?php echo $post_action ?>", {"f": post_data}, function( response, status, xhr ){
								if( "error" == response.status ){
									return false
								}
								
								$("#dt_factur_details").DataTable().ajax.reload();
								
							});
						
						} else {
							
							if ( rowIndex != "" )
							{
								var data = $( "#dt_factur_details" ).DataTable().row( rowIndex ).data();
										
								data.account_id = _response.id;
								data.account_number = _response.account_number;
								data.account_name =  _response.account_name;
								
								$("#dt_factur_details").DataTable().row( rowIndex ).data( data ).draw( false );
								
							} else {
								
								$("#dt_factur_details").DataTable().row.add(
									{
										"id" : 0,
										"description" : "",
										"value" : 0.00,
										"account_id" : _response.id,
										"account_number": _response.account_number,
										"account_name" : _response.account_name,
										"normal_pos" : _response.normal_pos
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
            <?php  echo Modules::run( "general_ledger/accounts/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

