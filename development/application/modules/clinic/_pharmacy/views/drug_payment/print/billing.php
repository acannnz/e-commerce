<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
    #pos-container {
        width: 100% !important;
		font-family: Tahoma, Arial, sans-serif;
    }
    td {
        padding: 6px 0px !important;
    }
	.border{
			border:1px solid #000;
	}
	h4 {
      font-weight: normal;
    }
	.container {
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;
        flex-direction: column;
    }
	.rapi-table {
        border-collapse: collapse;
        width: 100%;
    }

    .rapi-table td {
        
        padding: 8px;
    }

    .rapi-table .align-right {
        text-align: right;
    }
</style>
<div id="pos-container" class="row" style="margin:0 !important;">
    <div class="col-lg-4" style="margin:0 !important;">
        <div class="row" style="margin-top:26px">
            <div class="col-md-3" style="padding:0;">
                <div class="table-responsive">
				<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <div style="overflow: auto;">
					<img src=<?php echo base_url("resource/images/logos/pip.png") ?> width="100px" height="55px" style="float: left; margin-right: 10px;" />
					<p>
						<span style="font-size: 20px;"><strong><?php echo $this->config->item( "company_name" ) ?></strong></span><br>
						<?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?><br>
						Telp  <?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?>
					</p>
				</div>
				</div>
                    <table width="100%" style="font-size: 11px !important; font-family: Tahoma, Arial, sans-serif;">
                        <tbody>	           
                            <!-- <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> -->
                           <!-- <tr>              
                                <td colspan="5" style="padding:1px;border-bottom:1px solid #000000;" align="left">
                                    <span style="font-size:15px"><strong><?= config_item('apotek_name') ?></strong></span><br>
                                    <p style="font-size:11px; margin:0 !important;">
                                        <?= config_item('company_address')?><br>
                                        <?= config_item('company_phone')?> 
                                    </p>
                                </td>
                            </tr> -->
                            <!-- <tr>
                                <td width="150px" align="left" style="padding:2px;font-size:11px!important;" colspan="">User:</td>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= $this->user_auth->Nama_Singkat ?></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="">Tanggal:</td>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= DateTime::createFromFormat('Y-m-d H:i:s.u', $item->Jam)->format('d M Y, H.i') ?></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="">No. Transaksi:</td>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= $item->NoBukti ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="">Dokter:</td>
                                <td width="300px" align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= $item->Nama_Supplier ?></td>
                            </tr>
							<tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="">Tgl. Lahir:</td>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= date('d M Y', strtotime($item->TglLahir	)) ?></td>
                            </tr>
							<tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="">Alamat:</td>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= $item->Alamat ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="">Pasien:</td>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan=""><?= $item->Keterangan ?></td>
                            </tr> -->
                           <!-- <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;border-bottom:1px dashed #000000;" colspan="2">Peruntukan</td>
                                <td align="left" style="padding:2px;font-size:11px!important;border-bottom:1px dashed #000000;" colspan="3"><?= @$item->Peruntukan ?></td>
                            </tr>

                            <?php foreach($collection as $row): ?>
                                <?php 
                                    if ( $row->Nama_Barang != $row->NamaResepObat)
                                    {
                                        continue;
                                    }
                                    
                                    $left = sprintf("%s x %s -%s%%", number_format($sub_total[$row->NamaResepObat] / $row->Qty), $row->Qty, (float)@$row->Disc);
                                    $right = number_format($sub_total[$row->NamaResepObat]);
                                ?>
                                <tr>
                                    <td colspan="5" style="padding:2px 2px;font-size:11px!important;"><b><?= $row->Nama_Barang ?></b></td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="3" style="padding:2px 2px;font-size:11px!important;"><?= $left ?></td>
                                    <td align="right" colspan="2" style="padding:2px 2px;font-size:11px!important;"><?= $right ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if(@$item->BiayaAdministrasi > 0): ?>
                            <tr>
                                <td align="left" colspan="2" style="padding:5px 2px;font-size:11px!important;"><?= "Administrasi" ?></td>
                                <td align="right" colspan="3" style="padding:5px 2px;font-size:11px!important;"><?= number_format(@$item->BiayaAdministrasi) ?></td>
                            </tr>
                            <?php endif; ?>

                            <tr>
                                <td align="left" colspan="2" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;">Total</td>
                                <td align="right" colspan="3" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;"><?= number_format($grand_total) ?></td>
                            </tr>

                            <?php foreach($type_payment_used as $key => $val):
                                if ( $val > 0 ): ?>
                                    <tr>
                                        <td align="left" colspan="2" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;"><?= $key ?></td>
                                        <td align="right" colspan="3" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;"><?= number_format($val) ?></td>
                                    </tr>
                            <?php 
                                endif;
                            endforeach; ?>
                            <tr>
                                <td align="center" colspan="5" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;">
                                    <p>### LUNAS ###</p>
                                </td>
                            </tr>

                            <tr>
                                <td align="center" colspan="5" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;">
                                    <p><?= "Semoga lekas sembuh" ?></p>
                                </td>
                            </tr> -->

                        </tbody>
                    </table>
					<div class="row" style="border:1px solid #000;border-style: dotted;">
            	<div class="col-sm-12" style="padding:0;">
                	<div class="">
                    	<table class="table reports-table table_header" style="font-size: 17px;">
                        	<tr>
                            	<td width="210px">No Transaksi / <i>Transaction No</i></td>
                            	<td>: &nbsp; <?= $item->NoBukti ?></td>
                                <td width="100px"></td>
                                <td>Dokter / <i>Doctor</i> </td>
                                <td width="255px">: &nbsp; <?= $item->Nama_Supplier ?></td>
                            </tr>
                            <tr>
                            	<td width="80px">Tanggal / <i>Date</i></td>
                            	<td>: &nbsp; <?= DateTime::createFromFormat('Y-m-d H:i:s.u', $item->Jam)->format('d M Y, H.i') ?></td>
                                <td width="100px"></td>
                                <td width="210px">Tipe Pasien / <i>Patient Type</i> </td>
                                <td width="190px">: &nbsp; <?= $item->JenisPasien ?></td>
                            </tr>
                            <tr>
                            	<td width="80px">N.R.M / <i>N.M.R</i></td>
                            	<td width="180px">: &nbsp; <?= $item->NRM ?></td>
                                <td width="100px"></td>
                                <td width="140px">No Telp / <i>Phone Number</i> </td>
                                <td>: &nbsp; <?= $item->Phone ?> </td>
                            </tr>
                            <tr>
                            	<td width="80px">Nama Pasien / <i>Name Patient</i> </td>
								<td width="240px">: &nbsp; <?= $item->NamaPasien ?></td>
                                <td width="100px"></td>
                                <td>Jenis Kelamin / <i>Gender</i> </td>
								<td>: &nbsp; <?= $item->JenisKelamin == 'F' ? 'Perempuan' : 'Laki-Laki'; ?> </td>
                            </tr>
							<tr>
								<td width="150px">Alamat / <i>Address</i> </td>
                            	<td>: &nbsp; <?= $item->Alamat ?></td>
								<td width="100px"></td>
                                <td>Email / <i>Email</i> </td>
								<td>: &nbsp; <?= $item->Email; ?> </td>
							</tr>
							<tr>
								<td width="150px">Tgl Lahir / <i>DOB</i> </td>
                            	<td>: &nbsp; <?= date('d M Y', strtotime($item->TglLahir	)) ?></td>
								<td width="100px"></td>
                                <td>Penanggung Jawab / <i>Person responsible</i> </td>
								<td>: &nbsp; <?= $item->PenanggungNama; ?> </td>
							</tr>
							<tr>
								<td width="150px">Identitas / <i>Identity</i> </td>
                            	<td>: &nbsp; <?= $item->NoIdentitas ?></td>
								<td width="100px"></td>
                                <td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
                            </table>
                    </div>
                </div>
            </div>
					<table width="100%" class="border">
							<thead>
                            	<tr class="">
                                	<td width= "50px" class="pad10 bold center" style="font-size: 11px;">No.</td>
                                    <td width= "100px" class="pad10  w100 bold center" style="font-size: 11px;">Kode Item</td>
                                    <td width= "200px" class="pad10  w100 bold center" style="font-size: 11px;">Nama Item</td>
                                    <td class="pad10  w200 bold center" style="font-size: 11px;">Jml</td>
                                    <td class="pad10  bold center" style="font-size: 11px;">Satuan</td>
                                    <td class="pad10  w100 bold center" style="font-size: 11px;">Harga</td>
                                <!--<td class="pad10  w100 bold center">Pot</td> -->
								<!--<td class="pad10  w200 bold center">Tax</td> -->
                                    <td class="pad10  w200 bold center" style="font-size: 11px;">Total</td>
                                </tr>
                            </thead>
							<tbody>
								<?php $i=1; $totalQty = 0; $totalAmount = 0; foreach($collection as $row): $totalQty += $row->Qty; $totalAmount += $row->Harga * $row->Qty; ?>
								<tr>
									<td class="center pad5" style="font-size: 11px;"><?php echo $i++ ?></td>
									<td class="center pad5" style="font-size: 11px;"><?= $row->Barang_ID ?></td>
									<td class="pad5" style="font-size: 11px;"><?= $row->Nama_Barang ?></td>
									<td class="pad5" style="font-size: 11px;"><?= $row->Qty ?></td>
									<td class="pad5" style="font-size: 11px;"><?= $row->Satuan ?></td>
									<td class="pad5" style="font-size: 11px;"><?= number_format($row->Harga) ?></td>
								<!--<td class="pad5"><?= (float)@$row->Disc ?></td> -->
								<!--<td class="pad5"><?= $item->AddCharge ?></td> -->
									<td class="pad5" style="font-size: 11px;"><?= number_format($row->Harga * $row->Qty) ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
					</table>
					
					<table width="100%" class="rapi-table" style="font-size: 11px">
								<tr>
									<td>Keterangan:</td>
									<td class="align-right"><b>TOTAL AMOUNT =</b></td>
									<td class="align-right">Rp.<?= number_format($totalAmount) ?></td>
								</tr>
								<tr>
									<td></td>
									<td class="align-right"><b>DISKON =</b></td>
									<td class="align-right">Rp.<?= (float)@$row->Disc ?></td>
								</tr>
								<tr>
									<td></td>
									<td class="align-right"><b>TAX =</b></td>
									<td class="align-right">Rp.<?= $item->AddCharge ?></td>
								</tr>
							<!-- <tr>
									<td>Jml Item: <?= $totalQty ?></td>
									<td class="align-right">Sub Total: <?= number_format($grand_total) ?></td>
								</tr> -->
				<?php foreach ($type_payment_used as $key => $val):
						if ($val > 0): ?>
								<tr>
									<td></td>
                <?php $key = strtoupper($key); ?>
									<td class="align-right"><b><?= $key ?> =</b></td>
									<td class="align-right">Rp.<?= number_format($val) ?></td>
								</tr>
				<?php
				endif;
					endforeach; ?>
					</table>

					<div class="col-lg-12 far pad5" style="font-size: 11px; display: none;"><strong><?php echo "Terbilang : " ?></strong><?php echo ucwords($detail_money_to_text)." Rupiah </i>" ?></div>
					<div class="col-lg-12 far pad5" style="font-size: 11px; display: none;"><strong><?php echo "Counted : " ?></strong><?php echo '<i>' . ucwords($detail_money_to_text_english) . ' Rupiah </i>'; ?></div>
							<table width="100%" style="font-size: 11px;">
								<tr>
									<td>Hormat Kami </td>
									<td align="right">Penerima</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td><?php echo $user->Nama_Asli ?></td>
									<td align="right"><?php echo $item->Keterangan ?></td>
								</tr>
							</table>
                </div>
            </div>
        </div>
    </div>
</div>