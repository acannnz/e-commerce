<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= @$file_name ?></title>
    <link href="<?= base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <?php /*?><link href="<?= base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/><?php */?>
    <link href="<?= base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
    <style>
        #detail_table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        #detail_table td, #detail_table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        #detail_table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #CCC;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
            	<?php if( $report_logo = $this->config->item( "report_logo" ) ):  ?>
                <div class="col-xs-2">
                	<img src="<?= base_url("public/themes/intuitive/assets/img")."/".config_item('logo') ?>" />
                </div>
                <br>
                <div class="col-xs-6">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?= $section->SectionName ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?= sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?= lang( "reports:telp_label" ) ?>:</strong> <span><?= ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?= $section->SectionName ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?= sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?= lang( "reports:telp_label" ) ?>:</strong> <span><?= ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h3><?= "Laporan Mutasi Stok" ?></h3>
            	<h5>
                    <?= lang('reports:periode_label'); ?> 
                    <?= DateTime::createFromFormat('Y-m-d', $post_data->date_start)->format('d-M-Y') ?>  
                    <?= lang('reports:till_label'); ?> 
                    <?= DateTime::createFromFormat('Y-m-d', $post_data->date_end)->format('d-M-Y') ?>                      
                </h5>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <table class="table" width="100%" style="border:1px solid black; font-size:10px">
                            <thead>
                                <tr style="border:1px solid black;">
                                    <th align="center" style="border:1px solid black; padding:10px;">No</th>
                                    <th align="" style="border:1px solid black; padding:10px;">Tanggal</th>
                                    <th align="" style="border:1px solid black; padding:10px;">Nama</th>
                                    <th align="center" style="border:1px solid black; padding:10px;">JUMLAH</th>
                                    <th align="center" style="border:1px solid black; padding:10px;">SATUAN</th>
                                    <th align="center" style="border:1px solid black; padding:10px;">HARGA SATUAN</th>
                                    <th align="center" style="border:1px solid black; padding:10px;">TOTAL NILAI PENGELUARAN</th>
                                </tr> 
                            </thead>
                            <tbody> 
                                <?php foreach ($collection as $location => $items) : ?>                                
                                    <tr id="detail_table" width="100%" style="border:1px; solid black;">
                                        <th colspan="7" align="" style="padding:4px; font-size: 14px;">Tujuan: <?= $location ?></th>
                                    </tr> 
                                    
                                    <?php 
                                        $no=1;
                                            $total = 
                                                $grand_total = 0;
                                    ?>
                                    <?php if(!empty( $items )) : foreach ($items as $row) : 
                                        $total = $row->Harga * $row->Qty;
                                        $grand_total += $total; 
                                    ?>
                                    <tr style="border:1px dotted black; ">
                                        <td width="6" align="center" style="border:1px solid black; padding:4px;"><?= $no++; ?></td>
                                        <td align="center" width="100px" style="border:1px solid black; padding:4px;"><?= DateTime::createFromFormat('Y-m-d H:i:s', @$row->Tgl_Mutasi)->format('d-M-Y') ?></td>
                                        <td width="250px" style="border:1px solid black; padding:4px;"><?= @$row->Nama_Barang ?></td>
                                        <td align="center" style="border:1px solid black; padding:4px;"><?= @$row->Qty ?></td>
                                        <td width="100px" align="center" style="border:1px solid black; padding:4px;"><?= @$row->Kode_Satuan ?></td>
                                        <td align="right" style="border:1px solid black; padding:4px;"><?= number_format( @$row->Harga, 0, ",", ".") ?></td>
                                        <td align="right" style="border:1px solid black; padding:4px;"><?= number_format( @$total, 0, "," , ".") ?></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                <?php endforeach;?>
                                <tr>
                                    <td colspan="6" align="right" style="padding:4px; font-size: 12px;"><b>GRAND TOTAL</b></td>
                                    <td align="right" style="padding:4px; font-size: 12px;"><b><?= number_format(@$grand_total, 0, ".", ",");  ?></b></td>
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
