<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
    <style>
        table {
            font-size: 12.5px;
            font-family: "Times New Roman", Times, serif;
        }
        .detail_table {
            border-collapse: collapse;
            width: 100%;
            border: 0.5px solid rgb(170, 170, 170);
        }

        .detail_header {
            width: 100%;
            font-size: 12px;
            margin-left: 12px;
        }

        .detail_header td, .detail_header th {
            padding: 2px;
        }
        .detail_table td, .detail_table th {
            border: 0.5px solid rgb(170, 170, 170);
            padding: 8px;
        }

        .detail_table th {
            padding-top: 8px;
            padding-bottom: 8px;
            text-align: left;
            /* background-color: #f7f4f4; */
            color: #333;
            border: 0.5px solid rgb(170, 170, 170);
        }

        table tfoot th{
            background-color:none!important;
        }  
 </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
            <?php echo $this->load->view( "reports/header/header_report", true ) ?>	
            <div class="row">
                <h5 align="center"><strong><u style="padding-bottom: 2px;"><?= @$file_name  ?></u></strong><br><span style="font-size: 12px;!important">No. <?= @$item->No_Penerimaan ?></span></h5>
                <table class="detail_header" style="width: 50%;">
                    <thead>
                        <tr>
                            <td width="3%">Nama PBF</td>
                            <td width="3%">:</td>
                            <td width="44%"><?= @$supplier->Nama_Supplier ?></td>
                        </tr>

                        <tr>
                            <td>Alamat PBF</td>
                            <td>:</td>
                            <td><?= @$supplier->Alamat_1 ?></td>
                        </tr>
                        <tr>
                            <td>Telp. PBF</td>
                            <td>:</td>
                            <td><?= @$supplier->No_Telepon_1 ?></td>
                        </tr>
                    </thead>
                </table>
                <br>
                <div class="col-sm-12">
                    <table class="table reports-table detail_table" width="100%">
                        <thead>
                            <tr>
                                <th width="6%">No</th>
                                <th>Nama Obat</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Harga (@)</th>
                                <th>PPN</th>
                                <th>Subtotal</th>
                            </tr> 
                        </thead>
                        <tbody>
                            <?php 
                            	$no=1; 
                            	$grand_total = 0;
                                $grand_ppn = 0;
                            ?>
                            <?php if(!empty( $collection )): 
                                foreach ($collection as $key => $value):
                                    $subtotal = $value->Qty_Penerimaan * @$value->Harga_Beli;
                            		$grand_total += $subtotal;
                                    $ppn = @$value->Harga_Beli * @$value->Rate_Pajak /100;
                                    $grand_ppn += $ppn;
            
                            ?>
                            <tr>
	                            <td align="center"><?= @$no++ ?></td>
                                <td><?= @$value->Nama_Barang ?></td>
	                            <td><?= @$value->Kode_Satuan ?></td>
                                <td><?= @$value->Qty_Penerimaan ?></td>
                                <td align="right"><?= number_format(@$value->Harga_Beli, 2) ?></td>
                                <td align="right"><?= number_format(@$ppn, 2) ?></td>
	                            <td align="right"><?= number_format(@$subtotal,2) ?></td>
	                        </tr>
	                       	<?php endforeach; endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
	                            <th colspan="5" align="right"><b>Total</b></th>
                                <th align="right"><b><?= number_format(@$grand_ppn, 0);  ?></b></th>
	                            <th align="right"><b><?= number_format(@$grand_total, 0);  ?></b></th>
	                        </tr>
                            <tr>
	                            <th colspan="6" align="right"><b>Grandtotal</b></th>
                                <th align="right"><b><?= number_format(@$grand_ppn + @$grand_total, 0);  ?></b></th>
	                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <h4 style="color:red;"><?= (@$item->Status_Batal == 1) ? 'DIBATALKAN!':NULL ?></h4>
            <div class="row">
            	<div class="col-lg-12">
                	<div class="table-responsive">
                        <table class="table reports-table"  >
                            <tbody>
                                <tr>
                                    <td width="50%" align="center">&nbsp;</td>
                                    <td width="50%" align="center"><?= $this->config->item('company_city') ?>, <?= date('d M Y', strtotime(@$item->Tgl_Penerimaan)) ?><br> Penanggung Jawab</td>
                                </tr>
                                <tr>
                                    <td align="center"><br><br><br><br>&nbsp;</td>
                                    <td align="center" style="padding-top: 11px!important;"><br><br><br>( <?= @$user->Nama_Asli ?> )</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
