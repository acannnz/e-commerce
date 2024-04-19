<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( @$url_export, array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">  
			<h3 class="panel-title"><?php echo 'Laporan Rekam Medis Obat Pasien' ?></h3>
		</div>
		<div class="panel-body">
			<div class="col-md-12">
				<!-- <div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Tanggal'?></label>
					<div class="col-md-3">
						<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" autocomplete="off" />
					</div>
					<label class="col-md-3 control-label text-center"><?php echo 'S/D'?></label>
					<div class="col-md-3">
					   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" autocomplete="off" />
					</div>
				</div>    -->
				<div class="form-group">
					<label class="col-lg-3 control-label">No Registrasi<span class="text-danger"></span></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" class="form-control" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_registration ?>" data-toggle="lookup-ajax-modal" class="btn btn-default <?php echo empty($item->NoReg) ? '' : 'disable' ?>"><i class="fa fa-search"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Nama Pasien<span class="text-danger"></span></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="[fNamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Tgl Reg <span class="text-danger"></span></label>
					<div class="col-lg-9">
						<input type="text" id="TglReg" name="f[TglReg]" value="<?php echo substr(@$item->JamReg, 0, 19) ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Dokter<span class="text-danger"></span></label>
					<div class="col-lg-9">
						<input type="text" id="NamaDokter" name="f[NamaDokter]" value="<?php echo @$item->NamaDokter ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
				<label class="col-md-3 control-label"><?php echo 'Export Ke'?></label>
					<div class="col-md-3">
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