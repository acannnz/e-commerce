<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_prescriptions")) ?>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Form Uploda File</h4>
        </div>
        <div class="modal-body">
            <div class="row">
				<div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label">No Bukti</label>
						<input type="text" id="NoBukti" name="NoBukti" value="<?php echo @$item['NoBukti'] ?>" class="form-control" readonly/>
					</div>
				</div>
				<div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label">NRM</label>
						<input type="text" id="NRM" name="NRM" value="<?php echo @$item['NRM'] ?>" class="form-control" readonly/>
					</div>
				</div>
				<div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label">Nama File</label>
						<input type="text" id="NamaFile" name="NamaFile" class="form-control"/>
					</div>
				</div>
				<div class="col-md-12">
                    <div class="form-group">
                    	<label class="control-label">FIle</label>
						<input type="file" id="file" name="file" class="form-control" readonly/>
					</div>
				</div>
			</div>
        </div>
		<span hidden id="Jumlah"></span>
        <div class="modal-footer">
        	<div class="row">
				<div class="col-md-6">
					<a type="button" id="close" class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</a>
				</div>
				<div class="col-md-6">
					<a id="submit_file" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</a>
				</div>
				<object>
			</div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<?php echo form_close()?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/bootstrap-typeahead/bootstrap-typeahead.css">
<script type="text/javascript" src="<?php echo base_url();?>/themes/default/assets/js/plugins/bootstrap-typeahead/bootstrap-typeahead.js"></script>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
			
		$( document ).ready(function(e) {

			$("#submit_file").click( function(){

				var fileInput = document.getElementById('file');
				var allowedExtensions = /(\.pdf)$/i;

				if (fileInput.value === "") {
					$.alert_error('Belum Terdapat File PDF');
					return false
				}

				var filePath = fileInput.value;
				
				if ($('#NoBukti').val() === "") {
					$.alert_error('Belum Terdapat Pasien');
					return false
				} else if (!allowedExtensions.exec(filePath)) {
					$.alert_error('Format File Harus PDF');
					fileInput.value = '';
					return false;
				} else if (fileInput.files[0].size > 1300000) 
				{
					$.alert_error('Maaf. File Terlalu Besar ! Maksimal Upload 1,3 MB');
					fileInput.value = '';
					return false;
				}

				if (fileInput.files && fileInput.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						
						var data_post = {
							'Gambar': btoa(e.target.result),
							'NamaFile': $('#NamaFile').val(),
						}
						$.post('<?= current_url() ?>', data_post, function(response, status, xhr) {
							if ("error" == response.status) {
								$.alert_error(response.status);
								return false
							}

							$( "#close" ).trigger('click');
							$("#dt_files").DataTable().ajax.reload();
							$.alert_success('File Berhasil Disimpan');
							
						});
					};
					
					reader.readAsDataURL(fileInput.files[0]);
				}
			});

			});

	})( jQuery );
//]]>
</script>