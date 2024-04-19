<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( base_url("reports/recap_transactions/export"), array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">  
			<h3 class="panel-title"><?php echo 'Laporan Transaki Obat' ?></h3>
		</div>
		<div class="panel-body">
			<div class="col-md-12">
				<input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo @$item->SectionID ?>">
				<div class="row">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:date_label')?></label>
						<div class="col-md-3">
							<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
						</div>
						<label class="col-md-3 control-label text-center"><?php echo lang('reports:till_label')?></label>
						<div class="col-md-3">
						   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
						</div>
					</div>   
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo 'User' ?></label>
						<div class="col-md-9">
							<select id="user_id" name="f[user_id]" class="form-control">
								<option value="0">-- Semua --</option>
								<?php foreach($option_user as $row): ?>
								<option value="<?php echo $row->User_ID ?>"><?php echo $row->Nama_Asli ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo 'Shift' ?></label>
						<div class="col-md-9">
							<select id="shift_id" name="f[shift_id]" class="form-control">
								<option value="">-- Semua --</option>
								<?php foreach($option_shift as $row): ?>
								<option value="<?php echo $row->IDShift ?>"><?php echo $row->Deskripsi ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
						<div class="col-md-9">
							<select id="export_to" name="export_to" class="form-control">
								<option value="pdf">PDF</option>
								<option value="excel">EXCEL</option>
							</select>
						</div>
					</div>
					<div class="form-group text-right">
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