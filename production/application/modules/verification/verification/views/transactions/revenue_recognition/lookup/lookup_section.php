<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php /*?><div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Barang </h4>
        </div><?php */?>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
				var KodeNoBukti = _response.Kode;
					$.post("<?php echo base_url("verification/transactions/revenue_recognition/gen_id_dynamic") ?>"+'/'+ KodeNoBukti, function(response){
								try {
									$("#NoBukti").val( response.kode);
									$("#NamaSection").val( _response.Name);
									$("#SectionID").val( _response.Id);
									$("#ajaxModal").modal('hide');
								} catch (e){console.log();}
					})
				}
			}
			//]]></script>
            <?php echo $this->load->view( "references/section/lookup/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    <?php /*?></div>
	<!-- /.modal-content -->
</div><?php */?>
<!-- /.modal-dialog -->

