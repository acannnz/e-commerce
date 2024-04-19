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
						
						var index = '<?php echo $index ?>';
						
						data = index != ''
								? $("#dt_accounts").DataTable().row( index ).data()
								: {
									"Keterangan" : '',
									"Kredit" : 0,
									"Debit" : 0,
									"SectionName" : '',
									"SectionID" : ''
								};
											
						data.Akun_ID = _response.Akun_ID;
						data.Akun_No = _response.Akun_No;
						data.Akun_Name= _response.Akun_Name;
						
						index != ''
						? $("#dt_accounts").DataTable().row( index ).data( data ).draw()
						: $("#dt_accounts").DataTable().row.add( data ).draw();
							
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

