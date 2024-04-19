<html>
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
								<td><?php echo @$visite->ppk->kc->kdKR->nmKR ?></td>
							</tr>
							<tr>
								<td><p style="font-size:10px;">&nbsp;</p></td>
								<td></td>
							</tr>
							<tr>
								<td><p style="font-size:13px;font-weight:bold;"><?php echo "Kantor Cabang " ?></p></td>
								<td><?php echo @$visite->ppk->kc->nmKC ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>
			<div class="row" style="margin:0 !important;">
				<div class="col-xs-11 text-center" style="margin:10px 0 !important;">
					<span style="font-size:16px; font-weight:bold"><?php echo "Data Kunjungan Puskesmas / Dokter Keluarga" ?></span>
				</div>
			</div>
			<div class="row" style="border:2px solid #000000;margin: 15px 0 0 20px; padding-bottom:20px">
				<div class="col-xs-11">
					<div class="row" style="border:2px solid #000000;margin: 10px 0 0 20px;padding:10px 0 0 0px;">
						<div class="col-xs-5">
							 <table class="table reports-table">
								<tbody>
									<tr>
										<td width="40%"><p style="font-size:11px;"><?php echo "No. Kunjungan" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$visite->noKunjungan ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Puskesmas / Dokter Keluarga" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo sprintf("%s (%s)", @$visite->providerPelayanan->nmProvider, @$visite->providerPelayanan->kdProvider) ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Kabupaten / Kota" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo sprintf("%s (%s)", @$visite->ppk->kc->dati->nmDati, @$visite->ppk->kc->dati->kdDati) ?></p></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-xs-1"></div>
						<div class="col-xs-5" style="padding-top:20px">
							<barcode code="<?= @$visite->noKunjungan ?>" type="C39" height="1" />
						</div>
					</div>
					<div class="row" style="margin: 10px 0 0 20px;padding:10px 0 0 0px;">
						<div class="col-xs-5">
							 <table class="table reports-table">
								<tbody>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td> 
									</tr>
									<tr>
										<td width="40%" ><p style="font-size:11px;"><?php echo "Nama" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$visite->nmPst ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "No. Kartu BPJS" ?></p></td>
										<td>:</td>
										<td><p style="font-size:11px;"><?php echo @$visite->nokaPst ?></p></td>
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
												<?php echo sprintf("%s (%s)", @$visite->diagnosa1->nmDiag, @$visite->diagnosa1->kdDiag) ?>
												<?php echo !empty($visite->diagnosa2->nmDiag) ? sprintf(", %s (%s)", @$visite->diagnosa2->nmDiag, @$visite->diagnosa2->kdDiag) : NULL ?>
												<?php echo !empty($visite->diagnosa3->nmDiag) ? sprintf(", %s (%s)", @$visite->diagnosa3->nmDiag, @$visite->diagnosa3->kdDiag) : NULL ?>
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
										<td width="40%" colspan="3"><p style="font-size:11px;">&nbsp;</p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Umur:" ?></p></td>
										<td><p style="font-size:11px;"><?php echo "39" ?></p></td>
										<td><p style="font-size:11px;"><?php echo "Tahun:" ?></p></td>
										<td><p style="font-size:11px;"><?php echo DateTime::createFromFormat('d-m-Y', @$item->TglLahir)->format('d-M-Y'); ?></p></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><p style="font-size:11px;"><?php echo "Status:" ?></p></td>
										<td colspan="2"><p style="font-size:11px;"><?php echo sprintf("[%s] Utama/Tanggunan", @$visite->pisa) ?></p></td>
										<td><?php echo sprintf("[%s] (L / P)", @$visite->peserta->sex) ?></td>
									</tr>
									<tr>
										<td><p style="font-size:8px;">&nbsp;</p></td>
										<td></td>
										<td></td>
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
										<td colspan="3"><p style="font-size:11px;"><?php echo "Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya" ?></p></td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td align="center"><p style="font-size:11px;"><?php echo sprintf("Salam sejawat, %s", DateTime::createFromFormat('d-m-Y', @$visite->tglKunjungan)->format('d F Y')); ?></p></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
										<td ></td>
									</tr>	
									<tr>
										<td></td>
										<td></td>
										<td align="center"><p style="font-size:11px;"><?= @$visite->dokter->nmDokter ?></p></td>
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
