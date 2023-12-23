<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?= form_open( base_url("inventory/reports/mutation_stocks/export"), array("target" => "_blank") ); ?>
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-info">
		<div class="panel-heading">	
			<h3 class="panel-title"><?= 'laporan Mutasi Barang'?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="form-group">
					<label class="col-md-3 control-label">Dari Lokasi</label>
					<div class="col-md-7">
					<input type="text" class="form-control" value="<?= $section->SectionName; ?>" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Ke Lokasi</label>
					<div class="col-md-7">
						<?php 
							$dropdown_section[''] = 'Semua Lokasi';
							echo form_dropdown('f[to_location]', $dropdown_section, '',['class'=>'form-control select', 'id'=>'to_location']);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= lang('reports:date_label')?></label>
					<div class="col-md-3">
						<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?= date("Y-m-d"); ?>" />
					</div>
					<label class="col-md-1 control-label text-center"><?= lang('reports:till_label')?></label>
					<div class="col-md-3">
					   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?= date("Y-m-t"); ?>" />
					</div>
				</div>   
				<div class="form-group">
					<label class="col-md-3 control-label"><?= lang('reports:export_to_label')?></label>
					<div class="col-md-3">
						<select id="export_to" name="export_to" class="form-control">
							<option value="pdf">PDF</option>
							<option value="excel">EXCEL</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6 col-md-offset-3">
						<button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?= lang( 'buttons:export' ) ?></b></button>
						<button type="reset" class="btn btn-default"><?= lang( 'buttons:reset' ) ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= form_close()?>
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