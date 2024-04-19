<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( base_url("registrations/reports/registration-patient-types/export"), array("target" => "_blank") ); ?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('reports:date_label')?></label>
			<div class="col-md-3">
				<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
			</div>
			<label class="col-md-3 control-label text-center"><?php echo lang('reports:till_label')?></label>
			<div class="col-md-3">
			   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-t"); ?>" />
			</div>
		</div>   
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
			<div class="col-md-9">
				<select id="export_to" name="export_to" class="form-control">
					<option value="pdf">PDF</option>
					<?php /*?><option value="excel">EXCEL</option><?php */?>
				</select>
			</div>
		</div>
		<div class="form-group text-right">
			<button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
			<button type="reset" class="btn btn-default"><?php echo lang( 'buttons:reset' ) ?></button>
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