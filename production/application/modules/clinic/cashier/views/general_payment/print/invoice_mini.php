<?php //print_r($detail_data);exit;?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <style>
        body{
            font-family: "arial-ce"!important;
            color: #000;
            font-size: 12px;
        }
        .table_header td{
            padding: 5px;
        }
        
    	.tulisan{
			color:#000;
			font-style:normal;
			font-weight:1000px;
		}
		.pad10{
			padding:10px;
		}
		.pad5{
			padding: 5px 0px 5px 5px;
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
        /* tr, td, table{
            border: 1pt solid black ;
        } */
    </style>
</head>
<body>
<div id="pos-container" class="row" style="margin:0 !important;">
    <div class="col-lg-4" style="margin:0 !important;">
        <div class="row" style="margin-top:26px">
            <div class="col-md-3" style="padding:0;">
                <div class="table-responsive">
                    <table width="100%"  style="font-size:11px !important">
                        <tbody>	           
                            <tr>              
                                <td colspan="5" style="padding:1px;border-bottom:1px solid #000000;" align="center">
                                    <span style="font-size:15px"><strong><?= config_item('company_name') ?></strong></span><br>
                                    <p style="font-size:11px; margin:0 !important;">
                                        <?= config_item('company_address')?><br>
                                        <?= config_item('company_phone')?> 
                                    </p>
                                </td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="2">&nbsp;</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="3"><?= $detail_cashier->NoBukti ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="2">Pasien</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="3"><?= $detail_patient->NamaPasien ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="2">NRM</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="3"><?= $detail_patient->NRM ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>        
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<!-- <div class="row" style="margin:0; padding-top:10px !important;">
                <h4 align="center">INVOICE RAWAT JALAN <br> <small style="color: #000;"><?php echo $detail_cashier->NoBukti ?></small></h4>
            </div>
            <div class="row" style="border:1px solid #000;border-style: dotted;">
            	<div class="col-sm-12" style="padding:0;">
                	<div class="">
                    	<table class="table reports-table table_header">
                        	<tr>
                            	<td width="90px">No Registrasi</td>
                            	<td>: <?php echo $detail_reg->NoReg; ?></td>
                                <td width="100px"></td>
                                <td>Tanggal</td>
                                <td>: <?php echo date('d M Y', strtotime($date_reg)) ?></td>
                            </tr>
                            <tr>
                            	<td width="80px">N.R.M</td>
                            	<td>: <?php echo $detail_patient->NRM ?></td>
                                <td width="100px"></td>
                                <td>Dokter</td>
                                <td colspan="2">: <?php echo ($detail_doctor != null) ? $detail_doctor->Nama_Supplier : NULL; ?></td>
                            </tr>
                            <tr>
                            	<td width="80px">Nama Pasien</td>
                            	<td>: <?php echo $detail_patient->NamaPasien ?></td>
                                <td width="100px"></td>
                                <td>Diagnosa</td>
                                <td>: </td>
                            </tr>
                            <tr>
                            	<td width="150px">Umur / Jenis Kelamin</td>
                            	<td>: <?php echo $detail_reg->UmurThn .' / '. $detail_gender ?></td>
                                <td width="100px"></td>
                                <td>Tipe Pasien</td>
                                <td>: <?php echo $detail_patient->JenisPasien ?></td>
                            </tr>
                            </table>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <table style="font-size: 50px;">
                    <tr><td>&nbsp;</td></tr>
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
                            <td class="w500 pad10 left bold" colspan="6"><u>RINCIAN BIAYA</u></td>
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
                        <?php $no = 1; if(!empty($detail_data)): $total = 0; $grandtotal_total = 0;
                        foreach($detail_data as $group_biaya => $key): 
                            $l = $group_biaya; 
                            $grandtotal_total += $detail_summerise->$group_biaya;
                            
                            ?>
                            <tr class="" height="10px">
                                <td height="10px" style="padding:1px;border-bottom:1px solid #000000;" class="pad5"></td>
                                <td style="padding:1px;border-bottom:1px solid #000000;" class="pad5 bold far" colspan="3"><?php echo $group_biaya ?></td>
                                <td style="padding:1px;border-bottom:1px solid #000000;" class="pad5 bold right far"><?php echo "Rp." ?></td>
                                <td style="padding:1px;border-bottom:1px solid #000000;" class="pad5 bold right far"><?php echo number_format($detail_summerise->{$group_biaya}) ?></td>
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
                                    <td class="pad5 left far" colspan="4"><?php echo $r_row ?></td>
                                </tr>
                                <tr class="">
                                    <td></td>
                                    <td class="pad5 far" style="padding-left: 30px;"><?php echo '' ?>.</td>
                                    <td class="pad5 left far" colspan="4"><?php echo number_format($harga) ." x ". $b  ?></td>
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
                            
                        <?php $grandtotal = $grandtotal + $d_tot; endforeach;?>
                        <tr class="">
                            <!-- <td></td>
                            <td class="pad5 far"><?php //echo $j++ ?></td> -->
                            <!-- <td class="pad5 bold far" colspan="3"><?php echo @$data ?></td> -->
                            <td class="pad5 bold right far" colspan="5"><?php echo 'DISKON TOTAL = ' ?></td>
                            <td class="pad5 bold right far">(<?php echo  "Rp. ". number_format($total_diskon) ?>)</td>
                        </tr>
                        <?php endif; $t_total= $total - $grandtotal; $grandtotal_total = $grandtotal_total - $total_diskon ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="padding:1px;border-bottom:1px solid #000000; border-top:1px solid #000000;" colspan="5" class="pad5 bold right far"><?php echo "GRAND TOTAL = Rp. " ?></td>
                            <td style="padding:1px;border-bottom:1px solid #000000; border-top:1px solid #000000; " class="pad5 bold right far" ><b><?php echo number_format($grandtotal_total) ?></b></td>
                        </tr>
                    </tfoot>
                    <tfoot>
                        <tr>
                            <td align="center" style="padding:50px; font-size:50px;" colspan="6" class="pad5 bold right far"><?php echo "~~ Terima Kasih ~~`" ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
