<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( base_url("pharmacy/reports/stock-opname/export"), array("target" => "_blank") ); ?>
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('reports:stock_opname_label') ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">	
				<input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo @$item->SectionID ?>">
				<input type="hidden" id="SectionName" name="f[SectionName]" value="<?php echo @$item->SectionName ?>">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('reports:date_label')?></label>
					<div class="col-md-3">
						<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
					</div>
					<label class="col-md-1 control-label text-center"><?php echo lang('reports:till_label')?></label>
					<div class="col-md-3">
					   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-t"); ?>" />
					</div>
				</div>   
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Opsi'?></label>
					<div class="col-md-4">
						<div class="checkbox">
							<input type="checkbox" id="show_zero_difference" name="f[show_zero_difference]" value="1" class="check-searchable">
							<label for="show_zero_difference">Tampilkan selisih opname bernilai 0</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
					<div class="col-md-3">
						<select id="export_to" name="export_to" class="form-control">
							<option value="pdf">PDF</option>
							<option value="excel">EXCEL</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6 col-md-offset-3 ">
						<button type="submit" class="btn btn-primary"><b><i class="fa fa-print"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
						<button type="reset" class="btn btn-default"><?php echo lang( 'buttons:reset' ) ?></button>
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