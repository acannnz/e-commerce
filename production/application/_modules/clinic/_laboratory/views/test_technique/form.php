<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_helpers_technique", "name" => "form_helpers_technique") ); ?>
<div class="row">
	<div class="col-md-12">
        <div class="form-group">
            <label class="col-lg-3 control-label">Teknik Pemeriksaan<span class="text-danger">*</span></label>
            <div class="col-lg-9">
            	<input type="text" name="f[TeknikPemeriksaan]" id="TeknikPemeriksaan" value="<?php echo @$item->TeknikPemeriksaan ?>" class="form-control"/>
            </div>
         </div>
         
         <div class="form-group">
            <label class="col-lg-3 control-label">Opsi</label>
            <div class="col-lg-3">
            	<div class="checkbox">
                  <input type="checkbox" <?php echo (@$item->Aktif == 1)?"checked":""; ?> id="Aktif" name="f[Aktif]" value="1">
				  <label for="Aktif">Aktif</label>
                </div>
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