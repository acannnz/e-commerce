<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-4">
     	<div class="form-group">
			<label class="col-md-4">Gapah</label>
			<div class="col-md-8">
				<input type="text" id="Gapah" name="f[Gapah]" value="<?php echo @$item->Gapah ?>" class="form-control">
			</div>
		</div>
     	<div class="form-group">
			<label class="col-md-4">Umur Anak</label>
			<div class="col-md-8">
				<div class="input-group">
					<input type="text" id="UmurAnakTerakhir" name="f[UmurAnakTerakhir]" value="<?php echo @$item->UmurAnakTerakhir ?>" class="form-control">
					<div class="input-group-addon">Tahun</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Umur Kehamilan</label>
			<div class="col-md-8">
				<input type="text" id="UmurKehamilan" name="f[UmurKehamilan]" value="<?php echo @$item->UmurKehamilan ?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">HPHT</label>
			<div class="col-md-8">
				<input type="text" id="HariPertamaHaidTerakhir" name="f[HariPertamaHaidTerakhir]" value="<?php echo @$item->HariPertamaHaidTerakhir ?>" class="form-control datepicker">
			</div>
		</div>
    </div>
	<div class="col-md-4">
     	<div class="form-group">
			<label class="col-md-4">Tekanan Darah</label>
			<div class="col-md-8">
				<div class="input-group">
					<input type="text" id="TekananDarah" name="f[TekananDarah]" value="<?php echo @$item->TekananDarah ?>" class="form-control">
					<div class="input-group-addon">mmHg</div>
				</div>
			</div>
		</div>
     	<div class="form-group">
			<label class="col-md-4">Berat Badan</label>
			<div class="col-md-8">
				<div class="input-group">
					<input type="text" id="BeratBadan" name="f[BeratBadan]" value="<?php echo @$item->BeratBadan ?>" class="form-control">
					<div class="input-group-addon">KG</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Lingkar Lengan</label>
			<div class="col-md-8">
				<div class="input-group">
					<input type="text" id="LingkarLengan" name="f[LingkarLengan]" value="<?php echo @$item->LingkarLengan ?>" class="form-control">
					<div class="input-group-addon">CM</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Lab</label>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-6">
						<div class="checkbox">
							<input type="checkbox" id="HB" name="f[HB]" value="1" <?php echo @$item->HB == 1 ? 'checked' : '' ?>><label for="HB">HB</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<input type="checkbox" id="PPIA" name="f[PPIA]" value="1" <?php echo @$item->PPIA == 1 ? 'checked' : '' ?>><label for="PPIA">PPIA</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<input type="checkbox" id="HBSAG" name="f[HBSAG]" value="1" <?php echo @$item->HBSAG == 1 ? 'checked' : '' ?>><label for="HBSAG">HBSAG</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<input type="checkbox" id="SPILIS" name="f[SPILIS]" value="1" <?php echo @$item->SPILIS == 1 ? 'checked' : '' ?>><label for="SPILIS">SPILIS</label>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<div class="col-md-4">
     	<div class="form-group">
			<label class="col-md-4">Kujngn. Trisemester</label>
			<div class="col-md-8">
				<input type="text" id="KunjunganTrisemester" name="f[KunjunganTrisemester]" value="<?php echo @$item->KunjunganTrisemester ?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Riwayat Persalinan</label>
			<div class="col-md-8">
				<select id="RiwayatPersalinan" name="f[RiwayatPersalinan]" class="form-control">
					<option value="SPT" <?php echo @$item->RiwayatPersalinan == 'SPT' ? 'selected' : '' ?>>SPT</option>
					<option value="SC" <?php echo @$item->RiwayatPersalinan == 'SC' ? 'selected' : '' ?>>SC</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Resti Tinggi</label>
			<div class="col-md-8">
				<input type="text" id="ResikoKehamilan" name="f[ResikoKehamilan]" value="<?php echo @$item->ResikoKehamilan ?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Komp. Persalinan</label>
			<div class="col-md-8">
				<input type="text" id="KomplikasiPersalinan" name="f[KomplikasiPersalinan]" value="<?php echo @$item->KomplikasiPersalinan ?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4">Imunisasi TD</label>
			<div class="col-md-8">
				<div class="checkbox">
					<input type="checkbox" id="ImunisasiTD" name="f[ImunisasiTD]" value="1" <?php echo @$item->ImunisasiTD == 1 ? 'checked' : '' ?>><label for="ImunisasiTD">Sudah</label>
				</div>
			</div>
		</div>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		$( document ).ready(function(e) {
  

		});

	})( jQuery );
//]]>
</script>