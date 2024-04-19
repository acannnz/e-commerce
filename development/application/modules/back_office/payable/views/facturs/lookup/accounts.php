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
					
					var rowIndex = "<?php echo $trId ?>" ;
					
					try{
											
						if ( rowIndex != "" )
						{
							var data = $( "#dt_factur_details" ).DataTable().row( rowIndex ).data();
									
							data.Akun_ID = _response.Akun_ID;
							data.Akun_No = _response.Akun_No;
							data.Akun_Name =  _response.Akun_Name;
							
							$("#dt_factur_details").DataTable().row( rowIndex ).data( data ).draw();
							_datatable_actions.calculate_balance();
							
						} else {
							
							$("#dt_factur_details").DataTable().row.add(
								{
									"No_Faktur" : 0,
									"Keterangan" : "",
									"Qty" : 1,
									"Harga_Transaksi" : '',
									"Akun_ID" : _response.Akun_ID,
									"Akun_No": _response.Akun_No,
									"Akun_Name" : _response.Akun_Name,
									"SectionName" : "",
								}
							).draw();
						}

					}catch(e){ console.log(e)}

					$( '#lookup-ajax-modal' ).remove();
					
					$("body").removeClass("modal-open");
					
				}
			}
			//]]></script>
            <?php  echo Modules::run( "general_ledger/accounts/lookup", true, 'NONE' ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('accounts:account_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

