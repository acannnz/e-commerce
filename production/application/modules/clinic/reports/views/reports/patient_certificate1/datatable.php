<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->posted);exit;

?>

<?php echo form_open( base_url("{$nameroutes}/reports/export_patient_certificate/" . @$item->NoReg), array("id" => "form_patient_certificate", "target" => "_blank") ) ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Surat Keterangan Pasien' ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="page-subtitle col-md-offset-1 col-md-11">
					<h3>Jenis Surat</h3>
					<p>Informasi Jenis Surat</p>
				</div>
				<div class="form-group">
					<div class="col-md-offset-1 col-md-11">
						<div class="radio radio-inline">
							<input type="radio" name="f[report_type]" id="surat_keterangan_sehat" value="1" class="report_type" data-url="<?php echo base_url("{$nameroutes}/reports/patient_certificate/export_patient_certificate") ?>" value="surat_keterangan_sehat" checked />
							<label for="surat_keterangan_sehat">
								<b><?php echo 'Surat Keterangan Sehat' ?></b>
							</label>
						</div>
					</div>
				</div>    
				<div class="form-group">
					<div class="col-md-offset-1 col-md-11">
						<div class="radio radio-inline">
							<input type="radio" name="f[report_type]" id="surat_keterangan_sakit" value="2" class="report_type" data-url="<?php echo base_url("{$nameroutes}/reports/patient_certificate/export_patient_certificate") ?>" value="surat_keterangan_sakit" />
							<label for="surat_keterangan_sakit">
								<b><?php echo 'Surat Keterangan Sakit' ?></b>
							</label>
						</div>
					</div>
				</div>    
				<div class="form-group">
					<div class="col-md-offset-1 col-md-11">
						<div class="radio radio-inline">
							<input type="radio" name="f[report_type]" id="surat_keterangan_tidak_buta_warna" value="3" class="report_type" data-url="<?php echo base_url("{$nameroutes}/reports/patient_certificate/export_patient_cerftificate") ?>" value="surat_keterangan_tidak_buta_warna" />
							<label for="surat_keterangan_tidak_buta_warna">
								<b><?php echo 'Surat Keterangan Tidak Buta Warna' ?></b>
							</label>
						</div>
					</div>
				</div>    
			</div>
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3>Data Pasien</h3>
					<p>Informasi Data Pasien</p>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Tanggal</label>
					<div class="col-md-3">
						<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
					</div>
					<label class="col-md-3 control-label text-center">S/D</label>
					<div class="col-md-3">
						<input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">No. Registrasi<span class="text-danger"></span></label>
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
						<input type="text" id="NamaPasien" name="NamaPasien" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Tgl Reg <span class="text-danger"></span></label>
					<div class="col-lg-9">
						<input type="text" id="TglReg" name="TglReg" value="<?php echo substr(@$item->JamReg, 0, 19) ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Dokter<span class="text-danger"></span></label>
					<div class="col-lg-9">
						<input type="text" id="NamaDokter" value="<?php echo @$item->NamaDokter ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Hari<span class="text-danger"></span></label>
					<div class="col-lg-9">
						<input id="Hari" name="f[Hari]" placeholder="" class="form-control patient" requered>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Keterangan Surat<span class="text-danger"></span></label>
					<div class="col-lg-9">
						<textarea id="Keterangan_Surat" name="f[Keterangan_Surat]" placeholder="" class="form-control patient" requered></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"></label>
					<div class="col-md-9">
						<!-- <a href="<?php echo base_url("reports/reports/export_patient_certificate/"). str_replace("/","-",@$item->NoReg). "/surat_keterangan_sehat" ?>" id="btn_pdf" target="_blank" class="btn btn-primary col-md-12"><b><i class="fa fa-print"></i> <?= "Cetak Surat" ?></b></a> -->
						<button type="submit" class="btn btn-primary col-md-12"><b><i class="fa fa-print"></i> Cetak Surat</b></button>
						<!-- <a href="javascript:;" id="btn-excel" class="btn btn-success col-md-6"><b><i class="fa fa-file-excel-o"></i> <?php echo lang("reports:button_excel") ?></b></a> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {

			$("input[name='f[report_type]']").on("change", function() {
				var tipe = $(this).attr("id")
				$("#btn_pdf").attr('href','<?php echo base_url("reports/reports/export_patient_certificate/"). str_replace("/","-",@$item->NoReg) ?>/' + tipe )
			});	

		});
	})( jQuery );
//]]>
</script>