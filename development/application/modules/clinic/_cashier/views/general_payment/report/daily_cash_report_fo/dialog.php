<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( @$url_export, array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">  
			<h3 class="panel-title"><?php echo 'Laporan Harian Kas FO' ?></h3>
		</div>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Tanggal'?></label>
					<div class="col-md-3">
						<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" autocomplete="off" />
					</div>
					<label class="col-md-3 control-label text-center"><?php echo 'S/D'?></label>
					<div class="col-md-3">
					   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" autocomplete="off" />
					</div>
				</div>  
				<div class="form-group">
						<label class="col-md-3 control-label"><?php echo 'User' ?></label>
						<div class="col-md-9">
							<select id="user_id" name="f[user_id]" class="form-control">
								<option value="0">-- Semua --</option>
								<?php foreach($option_user as $row): ?>
								<option value="<?php echo $row->User_ID ?>" <?php echo (@$User_ID == $row->User_ID) ? 'selected':null ?>><?php echo $row->Nama_Asli ?></option>
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
								<option value="<?php echo $row->IDShift ?>" <?php echo (@$Shift == $row->IDShift) ? 'selected':null ?>><?php echo $row->Deskripsi ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div> 
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Export Ke'?></label>
					<div class="col-md-9">
						<select id="export_to" name="export_to" class="form-control">
							<option value="pdf">PDF</option>
							<option value="excel">EXCEL</option>
						</select>
					</div>
				</div>
				<div class="form-group text-right">
					<div class="col-md-12">
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