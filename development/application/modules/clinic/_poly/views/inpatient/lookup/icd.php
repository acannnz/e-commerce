<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup ICD </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

					var add_data = {
							"KodeICD" : _response.KodeICD,
							"Descriptions" : _response.Descriptions,
							"Ditanggung" :  1,
						};
					
					$("#dt_diagnosis").DataTable().row.add( add_data ).draw(true);
					
					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <?php echo Modules::run( "common/icd/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

