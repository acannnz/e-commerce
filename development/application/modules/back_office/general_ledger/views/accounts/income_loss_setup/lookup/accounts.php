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

					var _form_account_id = $( "input[id=\"Akun_ID\"]" );
					var _form_account_number = $( "input[id=\"Akun_No\"]" );
					var _form_account_name = $( "input[id=\"Akun_Name\"]" );
					
					try{
						
						_form_account_id.val( _response.Akun_ID );
						_form_account_number.val( _response.Akun_No );
						_form_account_name.val( _response.Akun_Name );
						
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

