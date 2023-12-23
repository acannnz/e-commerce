<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:type_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="text" id="Nama_Type" name="f[Nama_Type]" value="<?php echo @$item->Nama_Type ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:account_label') ?> <span class="text-danger">*</span></label>
    <input type="hidden" id="Akun_ID" name="f[Akun_ID]" value="<?php echo @$item->Akun_ID ?>" class="form-control" />
    <div class="col-md-5 ">
        <div class="input-group">
            <input type="text" id="Akun_No" name="Akun_No" value="<?php echo @$item->Akun_No .'-'. @$item->Akun_Name ?>" class="form-control" readonly="readonly" />
            <div class="input-group-btn">
                <a href="<?php echo $lookup_accounts ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""><i class="fa fa-gear"></i></a>
            </div>
        </div>
	</div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:default_label')?></label>
    <div class="col-lg-6">
        <label class="switch">
            <input type="hidden" value="0" name="f[Default_Type_Piutang]" />
            <input type="checkbox" <?php if(@$item->Default_Type_Piutang == 1){ echo "checked=\"checked\""; } ?> name="f[Default_Type_Piutang]" value="1">
            <span></span>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>