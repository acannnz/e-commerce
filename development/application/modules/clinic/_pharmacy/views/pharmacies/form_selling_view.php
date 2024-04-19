<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("name" => "form_pharmacy") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Lihat Penjualan Obat</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'No Bukti' ?> <span class="text-danger"></span></label>
					<div class="col-lg-8">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
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
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:company_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('pharmacy:card_number_label') ?></label>
					<div class="col-md-8">
						<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card">
					</div>
				</div>
				
		 
			</div>
			
			<div class="col-md-6">
				<!-- <div class="page-subtitle">
					<h3 class="text-primary">Detail Pembelian</h3>
				</div> -->
				<div class="form-group">
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
				</div>
				<br>
				<div class="form-group">
					<h1 class="text-left"> GRAND TOTAL  <span class="pull-right text-danger" id="grand_total"><?php echo number_format( $item->Total, 2, '.', ',' ) ?></span></h1>
					<h1 class="text-danger text-right"><?php echo $item->Retur ? "Transaksi Ini Sudah Di Retur!" : NULL ?></h1>
				</div>
			</div>
		</div>
		<?php echo modules::run("pharmacy/pharmacies/details/view", @$item ) ?>
		<div class="form-group">
			<div class="col-lg-12 text-right">
				<?php if( @$item->IncludeJasa == 0 && @$item->ObatBebas == 1 ): ?>
				<a href="<?php echo @$pay_link ?>" data-toggle="ajax-modal" class="btn btn-success" <?php echo $item->Retur ? "disabled" : "" ?>><i class="fa fa-money"></i> Bayar</a>
				<?php endif;?>
				<?php /*?><a href="<?php echo @$return_link ?>" data-toggle="ajax-modal" class="btn btn-danger" <?php echo $item->Retur ? "disabled" : "" ?>><i class="fa fa-refresh"></i> Retur</a><?php */?>
				<!-- <a href="<?php echo base_url("pharmacy/stroke_card/selling/{$item->NoBukti}") ?>" target="_blank" id="print-notes" class="btn btn-info" <?php echo $item->Retur ? "disabled" : "" ?>><i class="fa fa-print"></i> Print</a> -->
				<a href="<?php echo @$create_link ?>" class="btn btn-info"><i class="fa fa-file"></i> Buat Baru</a>
			</div>
		</div>
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