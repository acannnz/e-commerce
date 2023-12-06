<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( current_url(), array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('cash_flow:report_heading'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-12">
							<div class="radio">
								<input type="radio" id="cash_flow_excel" name="f[export_to]" value="cash_flow_excel" checked>
								<label for="cash_flow_excel"><?php echo lang('cash_flow:cash_flow_report') ?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<div class="radio">
								<input type="radio" id="cash_flow_detail_excel" name="f[export_to]" value="cash_flow_detail_excel">
								<label for="cash_flow_detail_excel"><?php echo lang('cash_flow:cash_flow_detail_report') ?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<div class="radio">
								<input type="radio" id="cash_flow_transaction_excel" name="f[export_to]" value="cash_flow_transaction_excel">
								<label for="cash_flow_transaction_excel"><?php echo lang('cash_flow:cash_flow_transaction_report') ?></label>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label"><?php echo lang('cash_flow:priod_label')?></label>
						<input id="period" name="f[period]" type="text" class="form-control datepicker" data-date-format="YYYY-MM" value="<?php echo date("Y-m"); ?>" autocomplete="off"/>
					</div>   
					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-block"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
(function (e) {	
	$(document).ready(function(e) {

			$('body').on('focus',".datepicker", function(){
				$(this).datetimepicker({
						format: "YYYY-MM-DD", 
						widgetPositioning: {
							horizontal: 'auto', // horizontal: 'auto', 'left', 'right'
							vertical: 'auto' // vertical: 'auto', 'top', 'bottom'
						},
					});
			});				
    });
}) (jQuery);
</script>