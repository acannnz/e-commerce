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
							
						$("#MerchanID").val( _response.ID );
						$("#MerchanName").val( _response.ID +" - "+ _response.NamaBank  );

						$("#AkunMerchanID").val( _response.Akun_ID );
						$("#AkunMerchanName").val( _response.Akun_ID +" - "+ _response.Akun_Name  );
							
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

