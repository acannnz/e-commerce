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
                    <span style="font-size:14px"><strong><?php echo sprintf("%s", 'Laporan Harian Kas FO'); ?></strong></span> <br> 
                    <?php echo 'Periode'; ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo 's/d'; ?> <?php echo  date('d-m-Y', strtotime($post_data->date_end)) ?>
                </p>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <table class="table_header" width="100%">
                            <thead>
                                <tr>
                                    <th align="center" width="4%"><?php echo 'No'; ?></th>
                                    <th width="13%"><?php echo 'No Bukti'; ?></th>
                                    <th width="10%"><?php echo 'Nama User'; ?></th>
                                    <th width="6%"><?php echo 'NRM'; ?></th>
                                    <th width="17%"><?php echo 'Pasien'; ?></th>
                                    <th width="10%"><?php echo 'Saldo Awal'; ?></th>
                                    <th width="10%"><?php echo 'Penerimaan'; ?></th>
                                    <th width="10%"><?php echo 'Pengeluaran'; ?></th>
                                    <th width="18%"><?php echo 'Sumber'; ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
                                <?php $_tot_saldo_awal = 0; $_tot_penerimaan = 0; $_tot_pengeluaran = 0; $no = 1; if(!empty($collection['data'])) : foreach ($collection['data'] as $row) :    ?>
                                <?php 
                                    $_tot_saldo_awal += $row->Saldoawal;
                                    $_tot_penerimaan += $row->Penerimaan;
                                    $_tot_pengeluaran += $row->Pengeluaran;
                                ?>
                                <tr>
                                    <td align="center" width="6px"><?php echo $no++; ?></td>
                                    <td><?php echo @$row->NoBukti ?></td>
                                    <td><?php echo @$row->NamaUser ?></td>
                                    <td><?php echo @$row->NRM ?></td>
                                    <td><?php echo @$row->NamaPasien ?></td>
                                    <td align="right"><?php echo number_format(@$row->Saldoawal, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Penerimaan, 0) ?></td>
                                    <td align="right"><?php echo number_format(@$row->Pengeluaran, 0) ?></td>
                                    <td><?php echo @$row->Tipe ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8"><?php echo 'Tidak terdapat data!' ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                     
                            </tfoot>
                        </table>
                        <br>
                        <table class="table_header" width="100%">
                            <tr>
                                    <td align="right" colspan="5" rowspan="3"><strong>GRAND TOTAL</strong></td>
                                    <td align="center"><strong>SALDO AWAL</strong></td>
                                    <td align="center"><strong>PENERIMAAN</strong></td>
                                    <td align="center"><strong>PENGELUARAN</strong></td>
                                    <td align="center"><strong>SALDO AKHIR</strong></td>
                                </tr>
                                <tr>
                                    <td align="center"><strong><?php echo number_format($_tot_saldo_awal, 0) ?></strong></td>
                                    <td align="center"><strong><?php echo number_format($_tot_penerimaan, 0) ?></strong></td>
                                    <td align="center"><strong><?php echo number_format($_tot_pengeluaran, 0) ?></strong></td>
                                    <td align="center"><strong><?php echo number_format(($_tot_saldo_awal + $_tot_penerimaan) - $_tot_pengeluaran, 0) ?></strong></td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
