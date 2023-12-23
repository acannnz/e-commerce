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
        .table_header th {
            border: 1px solid #808080;
            padding: 6px;
        }
        .table_header td {
            border: 1px solid #808080;
            padding: 5px;
        }

        .table_header {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <div class="col-lg-12">
                	<p>
                        <span style="font-size:20px"><strong><?php echo $this->config->item( "company_name" ) ?></strong></span><br>
                        <?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?><br>
                        Telp  <?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?>
                    </p>
                </div>
            </div>
            <div class="row" style="margin:0; padding-top:10px !important;">
                <p align="center">
                    <span style="font-size:14px"><strong><?php echo sprintf("%s %s", 'Laporan Rekap Transaksi Kasir', @$section->SectionName ); ?></strong></span> <br> 
                    <?php echo 'Periode'; ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo 's/d'; ?> <?php echo  date('d-m-Y', strtotime($post_data->date_end)) ?>
                </p>
            </div>
            <?php $i = 1; if(!empty($collection['data'])) : foreach ($collection['data'] as $key => $value) :   ?>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <strong><?php echo sprintf("%s", $key );?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <table class="table_header" width="100%">
                            <thead>
                                <tr>
                                    <th align="center" width="4%"><?php echo 'No'; ?></th>
                                    <th width="10%"><?php echo 'Tanggal'; ?></th>
                                    <th width="15%"><?php echo 'No Bukti'; ?></th>
                                    <th width="6%"><?php echo 'NRM'; ?></th>
                                    <th width="22%"><?php echo 'Nama Pasien'; ?></th>
                                    <th width="8%"><?php echo 'Tipe Pasien'; ?></th>
                                    <th width="10%"><?php echo 'Pemeriksaan'; ?></th>
                                    <th width="10%"><?php echo 'Tindakan'; ?></th>
                                    <th width="10%"><?php echo 'Obat'; ?></th>
                                    <th width="10%"><?php echo 'Diskon'; ?></th>
                                    <th width="10%"><?php echo 'Total'; ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
                                <?php $total_diskon = 0;$total_pemeriksaan = 0;$total_tindakan = 0; $total_obat = 0; $grandtotal = 0; $no = 1; if(!empty($value)) : foreach ($value as $row): ?>
                                <?php 
                                    $total = @$row->Tindakan + @$row->Obat + @$row->Dokter - @$row->NilaiDiscount;
                                    $total_tindakan += @$row->Tindakan; 
                                    $total_pemeriksaan += @$row->Dokter; 
                                    $total_obat += @$row->Obat; 
                                    $grandtotal += $total;
                                    $total_diskon += @$row->NilaiDiscount;
                                ?>
                                <tr>
                                    <td align="center" width="6px"><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime(@$row->TglClosing)) ?></td>
                                    <td><?php echo @$row->NoBukti ?></td>
                                    <td><?php echo @$row->NRM ?></td>
                                    <td><?php echo @$row->NamaPasien ?></td>
                                    <td><?php echo @$row->JenisKerjasama ?></td>
                                    <td align="right"><?php echo number_format(@$row->Dokter, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Tindakan, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Obat, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->NilaiDiscount, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$total, 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="10"><?php echo 'Tidak terdapat data!' ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td align="right" colspan="6"><strong>GRAND TOTAL</strong></td>
                                    <td align="right"><strong><?php echo number_format(@$total_pemeriksaan, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$total_tindakan, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$total_obat, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$total_diskon, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$grandtotal, 0) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <strong><?php echo sprintf("%s", 'OBAT BEBAS' );?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <table class="table_header" width="100%">
                            <thead>
                                <tr>
                                    <th align="center" width="4%"><?php echo 'No'; ?></th>
                                    <th width="10%"><?php echo 'Tanggal'; ?></th>
                                    <th width="15%"><?php echo 'No Bukti'; ?></th>
                                    <th width="6%"><?php echo 'NRM'; ?></th>
                                    <th width="22%"><?php echo 'Nama Pasien'; ?></th>
                                    <th width="8%"><?php echo 'Tipe Pasien'; ?></th>
                                    <th width="10%"><?php echo 'Pemeriksaan'; ?></th>
                                    <th width="10%"><?php echo 'Tindakan'; ?></th>
                                    <th width="10%"><?php echo 'Obat'; ?></th>
                                    <th width="10%"><?php echo 'Total'; ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
                                <?php $GrandTotalObat = 0; $no = 1; if(!empty($collection['obat'])) : foreach ($collection['obat'] as $row): ?>
                                <?php 
                                    $GrandTotalObat += @$row->Total;
                                ?>
                                <tr>
                                    <td align="center" width="6px"><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime(@$row->Tanggal)) ?></td>
                                    <td><?php echo @$row->NoBuktiPembayaran ?></td>
                                    <td><?php echo '' ?></td>
                                    <td><?php echo '' ?></td>
                                    <td><?php echo '' ?></td>
                                    <td><?php echo '' ?></td>
                                    <td align="right"><?php echo number_format(0, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Total, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Total, 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="9"><?php echo 'Tidak terdapat data!' ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td align="right" colspan="6"><strong>GRAND TOTAL</strong></td>
                                    <td align="right"><strong><?php echo number_format(0, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(0, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$GrandTotalObat, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$GrandTotalObat, 0) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <strong><?php echo sprintf("%s", 'RESEP LUAR' );?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <table class="table_header" width="100%">
                            <thead>
                                <tr>
                                    <th align="center" width="4%"><?php echo 'No'; ?></th>
                                    <th width="10%"><?php echo 'Tanggal'; ?></th>
                                    <th width="15%"><?php echo 'No Bukti'; ?></th>
                                    <th width="6%"><?php echo 'NRM'; ?></th>
                                    <th width="22%"><?php echo 'Nama Pasien'; ?></th>
                                    <th width="8%"><?php echo 'Tipe Pasien'; ?></th>
                                    <th width="10%"><?php echo 'Pemeriksaan'; ?></th>
                                    <th width="10%"><?php echo 'Tindakan'; ?></th>
                                    <th width="10%"><?php echo 'Obat'; ?></th>
                                    <th width="10%"><?php echo 'Total'; ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
                                <?php $GrandTotalResepLuar = 0; $no = 1; if(!empty($collection['resep_luar'])) : foreach ($collection['resep_luar'] as $row): ?>
                                <?php 
                                    $GrandTotalResepLuar += @$row->Total;
                                ?>
                                <tr>
                                    <td align="center" width="6px"><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime(@$row->Tanggal)) ?></td>
                                    <td><?php echo @$row->NoBuktiPembayaran ?></td>
                                    <td><?php echo '' ?></td>
                                    <td><?php echo '' ?></td>
                                    <td><?php echo '' ?></td>
                                    <td><?php echo '' ?></td>
                                    <td align="right"><?php echo number_format(0, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Total, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Total, 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="9"><?php echo 'Tidak terdapat data!' ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td align="right" colspan="6"><strong>GRAND TOTAL</strong></td>
                                    <td align="right"><strong><?php echo number_format(0, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(0, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$GrandTotalResepLuar, 0) ?></strong></td>
                                    <td align="right"><strong><?php echo number_format(@$GrandTotalResepLuar, 0) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
