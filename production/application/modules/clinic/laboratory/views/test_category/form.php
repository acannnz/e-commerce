<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_helpers", "name" => "form_helpers") ); ?>
<div class="row">
	<div class="col-md-12">
		 <div class="form-group">
			<label class="col-lg-3 control-label">ID Kategori Kategori<span class="text-danger">*</span></label>
			<div class="col-lg-9">
				<input type="text" name="f[KategoriTestID]" id="KategoriTestID" value="<?php echo @$item->KategoriTestID ?>" class="form-control" readonly />
			</div>
		 </div>
         <div class="form-group">
            <label class="col-lg-3 control-label">Nama Kategori Test<span class="text-danger">*</span></label>
            <div class="col-lg-9">
            	<input type="text" name="f[KategoriTestNama]" id="KategoriTestNama" value="<?php echo @$item->KategoriTestNama ?>" class="form-control" />
            </div>
         </div>
         <div class="form-group">
            <label class="col-lg-3 control-label">No Urut Hasil<span class="text-danger">*</span></label>
            <div class="col-lg-9">
            	<input type="number" name="f[NoUrut]" id="NoUrut" value="<?php echo @$item->NoUrut ?>" class="form-control" autocomplete="off"/>
            </div>
         </div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
				<button type="button" class="btn btn-warning" class="close" data-dismiss="modal"><?php echo lang( 'buttons:close' ) ?></button>
			</div>
		</div>
	</div>
</div>        
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	$(document).ready(function() {
		  $("input[type='checkbox']").on('change',function(){
			  if ($('#PasienBaru').is(":checked"))
			  {
				$('#reg').hide();
			  }else{
				$('#reg').show();
			  }
		  });
		  
		  
		  
		  $("#clear_patient").on('click',function(){
		  	$("#NRM").val("");
			$("#NamaPasien").val("");
			$("#Phone").val("");
			$("#email").val("");
			$("#Alamat").val("");
			$("#JenisKerasamaID").val(0);
		  });
	})
})( jQuery );
//]]>
</script>