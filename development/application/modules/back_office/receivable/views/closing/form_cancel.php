<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( $closing_cancel_url, array("id" => "form_closing_cancel", "name" => "form_closing_cancel") ); ?>
<div class="col-md-6">
    <div class="form-group">
        <label class="col-lg-3 control-label"><?php echo lang('closing:date_label') ?> <span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" id="cancel_date" name="cancel_date" placeholder="" class="form-control datepicker" value="<?php echo $last_closing ?>" data-date-format="YYYY-MM" data-date-min-date="<?php echo date("Y-m", strtotime( "{$last_closing} first day of previous month" )); ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label"><?php echo lang('closing:last_closing_label') ?> <span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <a href="javascript:;" class="btn btn-danger" ><?php echo $last_closing ?></a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <a href="<?php echo $closing_cancel_url ?>" class="btn btn-primary" data-toggle="form-ajax-modal"><?php echo lang( 'buttons:process' ) ?></a>
            <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
            <?php /*?><button account_level="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
        </div>
    </div>
</div>
<?php echo form_close() ?>
