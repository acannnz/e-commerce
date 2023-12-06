<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( $cancel_closing_url, array("id" => "form_closing_cancel", "name" => "form_closing_cancel") ); ?>
<div class="col-md-6">
	<?php foreach( $currency_rate as $index => $row ): ?>
	<div class="form-group">
    	<label class="col-md-4"><?php echo $row->Currency_Name ?> <span class="text-danger">*</span></label>
        <div class="col-md-8">
        	<input type="hidden" name="<?php echo sprintf("f[%s][%s]", $index, "Currency_ID") ?>" value="<?php echo $row->Currency_ID ?>" />
        	<input type="text" name="<?php echo sprintf("f[%s][%s]", $index, "Rate") ?>" value="<?php echo number_format(@$row->Rate, 2, '.', ',') ?>"  class="form-control" readonly="readonly" />
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="col-lg-3 control-label"><?php echo lang('closing:date_label') ?> <span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" id="cancel_date" name="cancel_date" placeholder="" class="form-control datepicker" value="<?php echo $last_period_closing ?>" data-date-min-date="<?php echo date("Y-m", strtotime("{$last_period_closing} first day of previous month")) ?>" data-date-format="YYYY-MM" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label"><?php echo lang('closing:last_closing_label') ?> <span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <a href="javascript:;" class="btn btn-danger" ><?php echo $last_period_closing ?></a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <a href="<?php echo $cancel_closing_url ?>" class="btn btn-primary" data-toggle="form-ajax-modal"><?php echo lang( 'buttons:process' ) ?></a>
            <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        </div>
    </div>
<?php echo form_close() ?>

