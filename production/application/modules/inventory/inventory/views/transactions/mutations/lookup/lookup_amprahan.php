<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-body">
    <script type="text/javascript">//<![CDATA[
    function row_selected( response ){
        var _response = JSON.parse(response)
        
        if( _response ){
            try{
				
				<?php /*?>window.location.href = '<?php echo base_url("inventory/transactions/mutations/create") ?>/'+_response.NoBukti;<?php */?>
				
				$("#NoBuktiAmprah").val( _response.NoBukti );
				$("#Lokasi_Tujuan").val( _response.Lokasi_Tujuan );
				$("#SectionAsalName").val( _response.SectionAsalName );
				$("#Tanggal_Amprah").val( _response.Tanggal );
				$("#KeteranganAmprah").val( _response.Keterangan );
				
				$("#ajaxModal").modal('hide');
				
				var params = {'id' : _response.NoBukti};
                $.get(
					"<?php echo base_url("inventory/transactions/mutations/get_amprahan_detail") ?>",  
					params,
					function(data, type, row){		
										
						var collection = data;
						
						$("#dt_mutation_details").DataTable().clear().draw();
						$("#dt_mutation_details").DataTable().rows.add( collection ).draw();
                    	
                	}
				);

            }catch(e){console.log(e)}
        }
    }
                                              
    //]]></script>
    <?php echo $this->load->view( "transactions/amprahan/lookup/lookup_amprahan", true ) ?>
</div>
<div class="modal-footer">
    <?php echo lang('patients:referrer_lookup_helper') ?>
</div>

