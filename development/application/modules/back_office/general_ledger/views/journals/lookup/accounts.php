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
					
					try{
						
						<?php if (!empty($No_Bukti)) : ?>
						
						var post_data = {
											"No_Bukti" :"<?php echo $No_Bukti ?>",
											"detail_id" : <?php echo $trId ?>,
											"id" : _response.id
										} 
												
						$.post("<?php echo base_url("general_ledger/journals/item_update")."?No_Bukti={$No_Bukti}" ?>", {"f": post_data}, function( response, status, xhr ){
							if( "error" == response.status ){
								return false
							}
							
							$("#dt_journal_details").DataTable().ajax.reload();
							
						});
						
						<?php else : ?>
						
						var data = $( "#dt_journal_details" ).DataTable().row(<?php echo $trId ?>).data();
	
						data.Akun_ID = _response.Akun_ID;						
						data.Akun_No = _response.Akun_No;
						data.Akun_Name = _response.Akun_Name;
											
						$( "#dt_journal_details" ).DataTable()
												.row('<?php echo $trId ?>')
												.data(data)
												.draw(false);

						<?php endif; ?>

						}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "general_ledger/accounts/lookup", true, "NONE" ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

