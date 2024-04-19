<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( @$url_export, array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">  
			<h3 class="panel-title"><?php echo 'Laporan Registrasi Pasien' ?></h3>
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
					<label class="col-md-3 control-label"><?php echo 'Tipe Pasien' ?></label>
					<div class="col-md-3">
						<select id="tipe_pasien" name="f[tipe_pasien]" class="form-control">
							<option value="">-- Semua --</option>
							<?php foreach($tipe_pasien as $row):?>
							<option value="<?php echo $row->JenisKerjasamaID?>"><?php echo $row->JenisKerjasama ?></option>
							<?php endforeach;?>
						</select>
					</div>
					<label class="col-md-3 control-label text-center"><?php echo 'Section' ?></label>
					<div class="col-md-3">
						<select id="section" name="f[section]" class="form-control">
							<option value="">-- Semua --</option>
							<?php foreach($section as $row):?>
							<option value="<?php echo $row->SectionID?>"><?php echo $row->SectionName ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Desa' ?></label>
					<div class="col-md-3">
						<select id="desa" name="f[desa]" class="form-control">
							<option value="">-- Semua --</option>
							<?php foreach ($desa as $row):?>
							<option value="<?php echo $row->DesaID ?>"><?php echo $row->DesaNama ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<label class="col-md-3 control-label text-center"><?php echo 'Kelas' ?></label>
					<div class="col-md-3">
						<select id="kelas" name="f[kelas]" class="form-control">
							<option value="">-- Semua --</option>
							<?php foreach ($kelas as $row):?>
							<option value="<?php echo $row->KelasID ?>"><?php echo $row->NamaKelas ?></option>
							<?php endforeach; ?>
						</select> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo 'Jenis Kelamin' ?></label>
					<div class="col-md-3">
						<select id="jeniskelamin" name="f[jeniskelamin]" class="form-control">
							<option value="">-- Semua --</option>
							<option value="M">Laki - Laki</option>
							<option value="F">Perempuan</option>
						</select>
					</div>
					<label class="col-md-3 control-label text-center"><?php echo 'Export Ke'?></label>
					<div class="col-md-3">
						<select id="export_to" name="export_to" class="form-control">
							<option value="pdf">PDF</option>
							<option value="excel">EXCEL</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Umur</label>
					<div class="col-md-3">
						<select id="patient_age" name="f[patient_age]" class="form-control">
							<option value="">-- Semua --</option>
							<option value="1">< 15 Tahun</option>
							<option value="2">15 - 24 Tahun</option>
							<option value="3">25 - 34 Tahun</option>
							<option value="4">35 - 44 Tahun</option>
							<option value="5">45 - 54 Tahun</option>
							<option value="6">55 - 64 Tahun</option>
							<option value="7">> 65 Tahun</option>
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