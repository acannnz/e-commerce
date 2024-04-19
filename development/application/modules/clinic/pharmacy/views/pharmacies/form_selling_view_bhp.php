<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("name" => "form_pharmacy") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Lihat Detail BHP</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'No Bukti' ?> <span class="text-danger"></span></label>
					<div class="col-lg-8">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBuktiPOP ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:doctor_label') ?></label>
					<div class="col-lg-8">
						<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ? $item->DokterID : "xx"  ?>" class="doctor_sender">
						<input type="text" id="DocterName" value="<?php echo @$item->Nama_Supplier ? $item->Nama_Supplier : "NONE" ?>" placeholder="" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:patient_name_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->Keterangan ?>" placeholder="" class="form-control patient">
					</div>
				</div> 
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:type_patient_label') ?></label>
					<div class="col-lg-8">
						<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
							<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
							<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->KerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<!-- <div class="page-subtitle">
					<h3 class="text-primary">Detail Pembelian</h3>
				</div> -->
				<!-- <div class="form-group">
					<label class="col-md-3">Sub Total</label>
					<div class="col-md-9">
						<input type="text" id="sub_total" name="sub_total" class="form-control" value="<?php echo number_format( $item->Total - $item->BiayaRacik - $item->BiayaResep, 2, '.', ',' ) ?>" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Biaya Racik</label>
					<div class="col-md-9">
						<input type="text" id="total_racik" name="total_racik" class="form-control" value="<?php echo number_format( $item->BiayaRacik, 2, '.', ',' ) ?>" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3">Biaya Resep</label>
					<div class="col-md-9">
						<input type="text" id="total_resep" name="total_resep" class="form-control" value="<?php echo number_format( $item->BiayaResep, 2, '.', ',' ) ?>" readonly/>
					</div>
				</div> -->
				<br>
				<div class="form-group">
					<h1 class="text-left"> GRAND TOTAL  <span class="pull-right text-danger" id="JumlahTransaksi"><?php echo number_format( $item->JumlahTransaksi, 2, '.', ',' ) ?></span></h1>
					<h1 class="text-danger text-right"><?php echo $item->Batal ? "Transaksi Ini Sudah Di Retur!" : NULL ?></h1>
				</div>
			</div>
		</div>
		<?php echo modules::run("pharmacy/pharmacies/details_bhp/view_bhp", @$item ) ?>
		
	</div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {			

			});

	})( jQuery );
//]]>
</script>