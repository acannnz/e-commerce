<?php //print_r($detail_data);exit;?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <style>
        body {
			font-family: Tahoma, Arial, sans-serif;
			color: #000;
			font-size: 11px;
		}

        .table_header td{
            padding: 2px;
			
        }
        
    	.tulisan{
			color:#000;
			font-style:normal;
			font-weight:1000px;
		}
		.pad10{
			padding:2px;
		}
		.pad5{
			padding: 2px 0px 2px 2px;
		}
		.border{
			border:1px solid #000;
		}
		.w100{
			width:100px;
		}
		.w200{
			width:200px;
		}
		.w500{
			width:300px;
			/* border:1px solid #000; */
		}
		.w1000{
			width:1200px;
		}
		.bgdark{
			background-color:#CCC;
			color:#000;
			font-family:"arial-ce";
		}
		.center{
			text-align:center;
		}
		.right{
			text-align:right;
		}
		.left{
			text-align:left;
		}
		.bold{
			font-weight:bold;
		}
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <div style="overflow: auto;">
					<img src=<?php echo base_url("resource/images/logos/pip.png") ?> width="100px" height="55px" style="float: left; margin-right: 10px;" />
					<p>
						<span style="font-size: 15px;"><strong><?php echo $this->config->item( "company_name" ) ?></strong></span><br>
						<?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?><br>
						Telp  <?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?>
					</p>
				</div>
            </div>
        	<div class="row" style="margin:0; padding-top:10px !important;">
                <h4 align="center">INVOICE RAWAT JALAN <br> (<i>OUTPATIENT INVOICES</i>) <br> <small style="color: #000;"><?php echo $detail_cashier->NoBukti ?></small></h4>
            </div>
            <div class="row" style="border:1px solid #000;border-style: dotted;">
            	<div class="col-sm-12" style="padding:0;">
                	<div class="">
                    	<table class="table reports-table table_header">
                        	<tr>
                            	<td width="180px">No Registrasi / <i>Registration No</i></td>
                            	<td>: &nbsp; <?php echo $detail_reg->NoReg; ?></td>
                                <td width="100px"></td>
                                <td>Dokter / <i>Doctor</i> </td>
                                <td>: &nbsp; <?php echo ($detail_doctor != null) ? $detail_doctor->Nama_Supplier : NULL; ?></td>
                            </tr>
                            <tr>
                            	<td width="80px">Tanggal / <i>Date</i></td>
                            	<td>: &nbsp; <?php echo date('d M Y', strtotime($date_reg)) ?></td>
                                <td width="100px"></td>
                                <td>Tipe Pasien / <i>Patient Type</i> </td>
                                <td width="190px">: &nbsp; <?php echo $detail_patient->JenisPasien ?></td>
                            </tr>
                            <tr>
                            	<td width="80px">N.R.M / <i>N.M.R</i></td>
                            	<td width="180px">: &nbsp; <?php echo $detail_patient->NRM ?></td>
                                <td width="100px"></td>
                                <td width="140px">No Telp / <i>Phone Number</i> </td>
                                <td>: &nbsp; <?php echo $detail_patient->Phone ?> </td>
                            </tr>
                            <tr>
                            	<td width="80px">Nama Pasien / <i>Name Patient</i> </td>
								<td>: &nbsp; <?php echo $detail_patient->NamaPasien ?></td>
                                <td width="100px"></td>
                                <td>Jenis Kelamin / <i>Gender</i> </td>
								<td>: &nbsp; <?php echo $detail_patient->JenisKelamin == 'F' ? 'Perempuan' : 'Laki-Laki'; ?> </td>
                            </tr>
							<tr>
								<td width="150px">Alamat / <i>Address</i> </td>
                            	<td>: &nbsp; <?php echo $detail_patient->Alamat ?></td>
								<td width="100px"></td>
                                <td>Email / <i>Email</i> </td>
								<td>: &nbsp; <?php echo $detail_patient->Email; ?> </td>
							</tr>
							<tr>
								<td width="150px">Umur & Tgl Lahir / <i>Age & DOB</i> </td>
                            	<td>: &nbsp; <?php echo $detail_reg->UmurThn .' / '. date('d F Y', strtotime($detail_patient->TglLahir)); ?></td>
								<td width="100px"></td>
                                <td>Penanggung Jawab / <i>Person responsible</i> </td>
								<td>: &nbsp; <?php echo $detail_reg->PenanggungNama; ?> </td>
							</tr>
							<tr>
								<td width="150px">Identitas / <i>Identity</i> </td>
                            	<td>: &nbsp; <?php echo $detail_patient->NoIdentitas; ?></td>
								<td width="100px"></td>
                                <td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
                            </table>
                    </div>
                </div>
            </div>
            <div class="row">
                        <table style="font-size: 18px;">
                            <tr style="height: 3px;"><td>&nbsp;</td></tr>
                            <thead>
                            	<tr>
                                	<td></td>
                                	<td></td>
                                	<td class="w500"></td>
                                    <td class="w500"></td>
                                    <td class="w500"></td>
                                    <td class="w200"></td>
                                </tr>
                                <?php if(!empty($detail_data)): ?>
                                <tr>
                                	<td class="w500 pad10 left bold" colspan="3">RINCIAN BIAYA</td>
									<td class="w500 pad10 left bold" colspan="1" style="text-align: center;">QTY</td>
									<td class="w500 pad10 left bold" colspan="">HARGA</td>
									<td class="w500 pad10 left bold" colspan="" style="text-align: right;">JUMLAH</td>
                                </tr>
                                <?php endif; ?>
                                <?php if(!empty($discounts)): ?>
                                <tr class="">
                                	<td class="w500 pad10" colspan="5">RINCIAN DISKON</td>
                                </tr>
                                <?php endif; ?>
								
                            </thead>
                            <tbody>
                            	<?php $subtotal = 0; $harga = 0; ?>
                            	<?php $no = 1; if(!empty($detail_data)): $total = 0; $grandtotal_total = 0; $grandtotal2 = 0;
                                foreach($detail_data as $group_biaya => $key): 
                                    $l = $group_biaya; 
                                    $grandtotal_total += $detail_summerise->$group_biaya;
                                    
                                    ?>
                                    <tr class="">
                                        <td class="pad5"></td>
                                        <td class="pad5 bold far" colspan="3"><?php echo $group_biaya ?></td>
                                        <td class="pad5 bold right far" colspan="1" style="text-align: right;"><?php echo "&nbsp;" ?></td>
                                        <td class="pad5 bold right far"><?php echo "Rp. " . number_format($detail_summerise->{$group_biaya}) ?></td>
                                    </tr>
                                    <?php $t_tot = 0; $subtotal = 0; 
                                    foreach($key as $r_row => $k_row): ?>
                                    <?php unset($a); $harga = 0; 
                                        foreach($k_row as $l_row):  ?>
                                            <?php $a[]= $l_row->JenisBiaya; $harga = $l_row->Nilai; 
                                        endforeach; 
                                        $b = count($a); $t_tot = $b * $l_row->Nilai; ?>
                                    <!-- JIKA GROUP BIAYA OBAT TIDAK DITAMPILKAN DETAIL -->
                                    <?php if($group_biaya == 'Obat'): ?>
                                    <?php else: ?>
                                        <tr class="">
                                            <td></td>
                                            <td class="pad5 far" style="padding-left: 30px;"><?php echo $no++ ?>.</td>
                                            <td class="pad5 left far" colspan="1"><?php echo $r_row ?></td>
                                            <!-- <td class="pad5 left far" colspan="2"><?php echo number_format($harga) ." x ". $b  ?></td> -->
											<td class="pad5 left far" colspan="1" style="text-align: center;"><?php echo  $b  ?></td>
											<td class="pad5 left far" colspan="1"><?php echo number_format($harga)  ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php $subtotal += $t_tot; endforeach;?>
                                    <?php $total += $subtotal; endforeach; endif; ?>
                                    <?php $j = 1; $grandtotal = 0; $d_total = 0; $total_diskon = 0;
                                    
                                if(!empty($detail_discount)): foreach($detail_discount as $data=>$col): ?>
                                    <tr class="">
                                        <td class="w500 pad10 left bold far" colspan="6">Rincian Diskon</td>
                                    </tr>
                                	<?php unset($c); $d_harga=0; foreach($col as $c_col=>$s_col): $total_diskon += $s_col->NilaiDiscount; ?>
                                    	<tr class="">
                                        	<td></td>
                                            <td class="pad5 far" colspan="3"><?php echo $s_col->NamaDiscount ?></td>
                                            <td class="pad5 far" colspan="2"><?php echo number_format($s_col->NilaiDiscount)." x ". 1 ?></td>
                                        </tr>
                                    <?php 
                                        $c[]= $s_col->NamaDiscount; 
                                        $d_harga = $s_col->NilaiDiscount; 
                                    endforeach; 
                                    $d=count($c); $d_tot= $d * $d_harga 
                                    
                                    ?>
                                    
								<tr>
                                    <td class="pad5 bold right far" colspan="5"><b><?php echo "SUB TOTAL = " ?></b></td>
                                    <td class="pad5 bold right far"><b><?php echo " Rp. ". number_format($grandtotal_total) ?></b></td>
								</tr>
                                <?php $grandtotal = $grandtotal + $d_tot; endforeach;?>
                                <tr class="">
                                    <!-- <td></td>
                                    <td class="pad5 far"><?php //echo $j++ ?></td> -->
                                    <!-- <td class="pad5 bold far" colspan="3"><?php echo @$data ?></td> -->
                                    <td class="pad5 bold right far" colspan="5"><?php echo 'DISKON TOTAL = ' ?></td>
                                    <td class="pad5 bold right far">(<?php echo  "Rp. ". number_format($total_diskon) ?>)</td>
                                </tr>
								<?php endif; $t_total= $total - $grandtotal; $grandtotal_total = $grandtotal_total - $total_diskon; $grandtotal2 = $grandtotal_total + $detail_cashier->AddCharge ?>
								
                            </tbody>
                            <tfoot>
								<tr>
                                    <td class="pad5 bold right far" colspan="5"><b><?php echo "TOTAL AMOUNT = " ?></b></td>
                                    <td class="pad5 bold right far"><b><?php echo " Rp. ". number_format($grandtotal_total) ?></b></td>
                                </tr>
								<tr>
                                    <td class="pad5 bold right far" colspan="5"><b><?php echo "CASH / BANK = " ?></b></td>
                                    <td class="pad5 bold right far"><b><?php echo " Rp. ". number_format($detail_cashier->NilaiPembayaranKKAwal) ?></b></td>
                                </tr>
								<tr>
									<td class="pad5 bold right far" colspan="5"><b>TAX (<?php echo isset($detail_cashier) ? $detail_cashier->AddCharge_Persen . "%" : "0%"; ?>) = </b></td>
									<td class="pad5 bold right far"><b><?php echo isset($detail_cashier) ? "Rp. " . number_format($detail_cashier->AddCharge) : "Rp. 0"; ?></b></td>
								</tr>
                                <tr>
                                    <td class="pad5 bold right far" colspan="5"><b><?php echo "GRAND TOTAL = " ?></b></td>
                                    <td class="pad5 bold right far"><b><?php echo " Rp. ". number_format($grandtotal2) ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="col-lg-12" style="border-top:1px solid black;border-bottom:1px solid black;border-style: dotted;">
                        <div class="col-lg-12 far pad5"><strong><?php echo "Terbilang : " ?></strong><?php echo ucwords($detail_money_to_text)." Rupiah </i>" ?></div>
						<div class="col-lg-12 far pad5"><strong><?php echo "Counted : " ?></strong><?php echo '<i>' . ucwords($detail_money_to_text_english) . ' Rupiah </i>'; ?></div>
                    </div>
            </div>
			
            <div class="col-lg-12">
                    <table width="100%">
                    	<tr><td>&nbsp;</td></tr>
                        <tr>
                            <td>Pasien / <i>Patient</i> </td>
                            <td align="right"><?= $this->config->item('company_city') ?>, <?php echo date("d M Y") ?></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td><?php echo $detail_patient->NamaPasien ?></td>
                            <!-- <td align="right"><?php echo $user->Nama_Asli ?></td> -->
							<td align="right">Kasir / <i>Cashier</i> </td>
                        </tr>
					 <!--	<tr>
							<td>&nbsp;</td>
							<td align="right"><?php echo "Kasir" ?></td>
						</tr> -->
                    </table>
            </div>
        </div>
    </div>
</body>
</html>
