<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('reservations:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
                    var _response = JSON.parse(response);
					var indexRow = '<?php echo $indexRow ?>';
					//console.log(_customer);
                    if( _response ){
						
						try{
                                						
						var data = $("#dt_schedules").DataTable().row( indexRow ).data();
						
						data.DokterPenggantiID = _response.Kode_Supplier;
						data.Nama_Supplier = "<span class=\"label label-danger label-xs\" style=\"cursor: pointer;\"><i class=\"fa fa-times\"></i></span> "+_response.Nama_Supplier;
						
						$("#dt_schedules").DataTable().row( indexRow ).data( data ).draw( true );
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						} catch(e){console.log(e)}
                    }
			}
			//]]></script>
            <?php echo Modules::run( "common/suppliers/lookup", true, $type ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('reservations:lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
