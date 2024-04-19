<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<!-- MODAL -->
<?php if(count($collection_note_patient) > 0 ): ?>
<div id="modalNotePasien" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Penting Pasian</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="" style="color:red">
						<h2><?php echo $item->Keterangan ?></h2>
					</div>
				</div>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Riwayat Alergi Pasien</h5>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="" style="color:red">
                        <h2><?php echo $item->RiwayatAlergi ?></h2>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
	$("#modalNotePasien").modal('show');
});
//<![CDATA[
(function( $ ){
			//LIST PASIEN 3 HARI YANG LALU
			
		
		// $( document ).ready(function(e) {
		// 		$( "#dt_pasien_history" ).dt_pasien_history();
				
		// 	});
	})( jQuery );
//]]>
</script>