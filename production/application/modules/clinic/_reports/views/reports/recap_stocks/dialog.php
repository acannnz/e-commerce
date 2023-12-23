<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open(@$url_export, array("target" => "_blank") ); ?>
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('reports:recap_stock_label') ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Tanggal'?></label>
					<div class="col-md-3">
						<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-01"); ?>" autocomplete="off" />
					</div>
					<label class="col-md-3 control-label text-center"><?php echo 'S/D'?></label>
					<div class="col-md-3">
					   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-t"); ?>" autocomplete="off" />
					</div>
				</div>  
				<div class="form-group">
						<label class="col-md-3 control-label"><?php echo 'Farmasi' ?></label>
						<div class="col-md-9">
							<select id="Lokasi_ID" name="f[Lokasi_ID]" class="form-control">
								<?php foreach($option_section as $row): ?>
								<option value="<?php echo $row->Lokasi_ID ?>"><?php echo $row->SectionName ?></option>
								<?php endforeach; ?>
							</select>
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
					<div class="col-md-6 col-md-offset-3">
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