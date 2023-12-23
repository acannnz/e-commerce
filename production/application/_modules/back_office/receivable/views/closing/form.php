<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( $closing_url, array("id" => "form_closing", "name" => "form_closing") ); ?>
<div class="col-md-6">
	<?php foreach( $currency_rate as $index => $row ): ?>
	<div class="form-group">
    	<label class="col-md-4"><?php echo $row->Currency_Name ?> <span class="text-danger">*</span></label>
        <div class="col-md-8">
        	<input type="hidden" name="<?php echo sprintf("f[%s][%s]", $index, "Currency_ID") ?>" value="<?php echo $row->Currency_ID ?>" />
        	<input type="text" name="<?php echo sprintf("f[%s][%s]", $index, "Rate") ?>" value="<?php echo number_format(@$row->Rate, 2, '.', ',') ?>"  class="form-control" required="required" />
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="col-lg-3 control-label"><?php echo lang('closing:date_label') ?> <span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" id="closing_date" name="closing_date" placeholder="" class="form-control datepicker" value="<?php echo  date("Y-m", strtotime( "{$last_closing} first day of next month" )) ?>" data-date-format="YYYY-MM" data-date-min-date="<?php echo $last_closing ?>" required>
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
            <a href="<?php echo $closing_url ?>" class="btn btn-primary" data-toggle="form-ajax-modal"><?php echo lang( 'buttons:process' ) ?></a>
            <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
            <?php /*?><button account_level="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
        </div>
    </div>
</div>
<?php echo form_close() ?>
