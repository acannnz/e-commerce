<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( base_url("{$nameroutes}/export"), array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">  
			<div class="panel-bars">
				<ul class="btn-bars">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
							<i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
						</a>
						
					</li>
				</ul>
			</div>
			<h3 class="panel-title"><?php echo 'Laporan Nilai Persediaan' ?></h3>
		</div>
		<div class="panel-body table-responsive">
			<div class="col-md-12">
				<div class="row">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:date_label')?></label>
						<div class="col-md-9">
						   <input id="date" name="f[date]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
						</div>
					</div>   
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:section_label')?></label>
						<div class="col-md-9">
							<select id="location" name="f[location]" class="form-control">
								<?php foreach($option_section as $row):?>
								<option value="<?php echo $row->Lokasi_ID?>" ><?php echo $row->SectionName?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo 'Kelompok' ?></label>
						<div class="col-md-9">
							<select id="group" name="f[group]" class="form-control">
								<option value="OBAT">OBAT</option>
								<option value="UMUM">UMUM</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
						<div class="col-md-9">
							<select id="export_to" name="export_to" class="form-control">
								<option value="excel">EXCEL</option>
								<?php /*?><option value="pdf">PDF</option><?php */?>
							</select>
						</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
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