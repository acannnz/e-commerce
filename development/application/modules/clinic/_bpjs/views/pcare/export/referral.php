<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name;?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container">
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="margin:0 !important;">
                <div class="col-xs-6" style="margin:0 !important;">
                	<img src="<?php echo base_url( "resource/images/logos" )."/bpjs-kesehatan.jpg" ?>" height="55" />
                </div>
                <div class="col-xs-5" style="margin:0 !important;">
					 <table class="table reports-table" style="margin-left:30px !important;">
						<tbody>
							<tr>
								<td width="40%"><p style="font-size:13px;font-weight:bold;"><?php echo "Kedeputian Wilayah " ?></p></td>
								<td><?php echo @$referral->ppk->kc->kdKR->nmKR ?></td>
							</tr>
							<tr>
								<td><p style="font-size:10px;">&nbsp;</p></td>
								<td></td>
							</tr>
							<tr>
								<td><p style="font-size:13px;font-weight:bold;"><?php echo "Kantor Cabang " ?></p></td>
								<td><?php echo @$referral->ppk->kc->nmKC ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>
			<div class="row" style="margin:0 !important;">
				<div class="col-xs-11 text-center" style="margin:10px 0 !important;">
					<span style="font-size:16px; font-weight:bold"><?php echo "Surat Rujukan FKTP" ?></span>
				</div>
			</div>
			<div class="row" style="border:2px solid #000000;margin: 15px 0 0 20px; padding-bottom:20px">
				<div class="col-xs-11">
					<div class="row" style="border:2px solid #000000;margin: 10px 0 0 20px;padding:10px 0 0 0px;">
						<div class="col-xs-5">
							 <table class="table reports-table">
								<tbody>
									<tr>
										<td width="40%"><p style="font-size:11px;"><?php echo "No. Rujukan" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$referral->noRujukan ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "FKTP" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo sprintf("%s (%s)", @$referral->ppk->nmPPK, @$referral->ppk->kdPPK) ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Kabupaten / Kota" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo sprintf("%s (%s)", @$referral->ppk->kc->dati->nmDati, @$referral->ppk->kc->dati->kdDati) ?></p></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-1"></div>
						<div class="col-xs-5" style="padding-top:20px">
							<barcode code="<?= @$referral->noRujukan ?>" type="C39" height="1" />
						</div>
					</div>
					<div class="row" style="margin: 10px 0 0 20px;padding:10px 0 0 0px;">
						<div class="col-xs-5">
							<table class="table reports-table">
								<tbody>
									<tr>
										<td width="40%"><p style="font-size:11px;"><?php echo "Kepada Yth. TS Dokter" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$referral->poli->nmPoli ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Di" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$referral->providerRujukLanjut ?></p></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-6">
						</div>
					</div>
					<div class="row" style="margin: 10px 0 0 20px;padding:10px 0 0 0px;">
						<div class="col-xs-5">
							 <table class="table reports-table">
								<tbody>
									<tr>
										<td colspan="3"><p style="font-size:11px;"><?php echo "Mohon pemeriksaan dan penangan lebih lanjut pasien :" ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td> 
									</tr>
									<tr>
										<td width="40%" ><p style="font-size:11px;"><?php echo "Nama" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$referral->nmPst ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "No. Kartu BPJS" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$referral->nokaPst ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Diagnosa" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;">
												<?php echo sprintf("%s (%s)", @$referral->diag1->nmDiag, @$referral->diag1->kdDiag) ?>
												<?php echo !empty($referral->diag2) ? sprintf(", %s (%s)", @$referral->diag2->nmDiag, @$referral->diag2->kdDiag) : NULL ?>
												<?php echo !empty($referral->diag3) ? sprintf(", %s (%s)", @$referral->diag3->nmDiag, @$referral->diag3->kdDiag) : NULL ?>
											</p>
										</td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Telah diberikan" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo NULL ?></p></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-1"></div>
						<div class="col-xs-5" style="padding-left:100px">
							 <table class="table reports-table">
								<tbody>
									<tr>
										<td width="40%" colspan="4"><p style="font-size:11px;">&nbsp;</p></td>
									</tr>
									<tr>
										<td colspan="4"><p style="font-size:8px;">&nbsp;</p></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Umur:" ?></p></td>
										<td><p style="font-size:11px;"><?php echo "39" ?></p></td>
										<td><p style="font-size:11px;"><?php echo "Tahun:" ?></p></td>
										<td><p style="font-size:11px;"><?php echo DateTime::createFromFormat('d-m-Y', @$referral->tglLahir)->format('d-M-Y'); ?></p></td>
									</tr>
									<tr>
										<td colspan="4"><p style="font-size:8px;">&nbsp;</p></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Status:" ?></p></td>
										<td colspan="2"><p style="font-size:11px;"><?php echo sprintf("[%s] Utama/Tanggunan", @$referral->pisa) ?></p></td>
										<td><?php echo sprintf("[%s] (L / P)", @$referral->sex) ?></td>
									</tr>
									<tr>
										<td colspan="4"><p style="font-size:8px;">&nbsp;</p></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Catatan:" ?></p></td>
										<td colspan="3"><p style="font-size:11px;"><?php echo NULL ?></p></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row" style="margin: 10px 0 0 20px;padding:10px 0 0 0px;">
						<div class="col-xs-12">
							<table class="table reports-table">
								<tbody>
									<tr>
										<td width="30%">&nbsp;</td>
										<td width="40%">&nbsp;</td>
										<td align="center" width="30%"></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Atas bantuannya, diucapkan terima kasih" ?></p></td>
										<td>&nbsp;</td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td align="center"><p style="font-size:11px;"><?php echo sprintf("Salam sejawat, %s", DateTime::createFromFormat('d-m-Y', @$referral->tglKunjungan)->format('d F Y')); ?></p></td>
									</tr>
									<tr>
										<td colspan="2"><p style="font-size:11px;"><?php echo sprintf("Tgl. Rencana Berkunjung : %s", DateTime::createFromFormat('d-m-Y', @$referral->tglKunjungan)->format('d-M-Y')) ?></p></td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2"><p style="font-size:11px;"><?php echo sprintf("Jadwal Praktek : %s", @$referral->jadwal) ?></p></td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2"><p style="font-size:11px;"><?php echo sprintf("Surat rujukan berlaku 1[satu] kali kunjungan, berlaku sampai dengan : %s", DateTime::createFromFormat('d-m-Y', @$referral->tglAkhirRujuk)->format('d-M-Y')) ?></p></td>
										<td ></td>
									</tr>	
									<tr>
										<td></td>
										<td></td>
										<td align="center"><p style="font-size:11px;"><?= @$referral->dokter->nmDokter ?></p></td>
									</tr>									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
        </div>
    </div>
</div>
</body>
</html>