<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <?php /*?><link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/><?php */?>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
    <style>
        body{
            font-family: "Helvetica Neue",Roboto,Arial,"Droid Sans",sans-serif!important;
            color: #000;
        }
        .table_header{
            width: 100%;
            border: 1px;
            font-size: 11.5px;
        }
        .table_header th{
            padding: 8px;
            border: 1px solid #3e3e3e;
        }
        .table_header td{
            padding: 5px;
            border: 1px solid #3e3e3e;
        }
        
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
            <div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <?php if( $report_logo = $this->config->item( "report_logo" ) ):  ?>
                    <div class="col-xs-2">
                        <img src="<?php echo base_url( "resource/images/logos" )."/".$report_logo ?> " />
                    </div>
                <?php endif ?>
                <?php if( $report_logo = $this->config->item( "report_logo" ) ):  ?>
                    <div class="col-xs-6">
                <?php else: ?>
                    <div class="col-xs-12">
                <?php endif ?>
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s", $this->config->item( "company_address" )) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h4>
                    <?php echo sprintf("%s ", 'Laporan Harga Obat Keseluruhan'); ?><br>
                    <small style="color:#000"><?php echo "Tanggal" ?> : <?php echo date('d-m-Y') ?></small>
                </h4>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table_header">
                        <thead>
                            <tr>
                                <th align="center"><?php echo 'No'; ?></th>
                                <th align="center"><?php echo 'Nama Obat'; ?></th>
                                <th align="right"><?php echo 'Harga Pokok'; ?></th>
                                <th align="right"><?php echo 'Harga Umum'; ?></th>
                                <th align="right"><?php echo 'Harga IKS'; ?></th>
                                <th align="right"><?php echo 'Harga BPJS'; ?></th>
                                <th align="right"><?php echo 'Harga Executive'; ?></th>
                            </tr> 
                        </thead>
                        <tbody>	
                        
                            <?php $i = 1 ; if(!empty($collection)) : foreach ($collection as $row) : ?>
                            <tr>
                                <td align="center" width="6px"><?php echo $i++; ?></td>
                                <td align="center"><?php echo @$row->Nama_Barang ?></td>
                                <td align="right"><?php echo number_format(@$row->Harga_Jual_PPN,2) ?></td>
                                <td align="right"><?php echo number_format(@$row->H_Jual_Umum,2) ?></td>
                                <td align="right"><?php echo number_format(@$row->H_Jual_IKS,2) ?></td>
                                <td align="right"><?php echo number_format(@$row->H_Jual_BPJS,2) ?></td>
                                <td align="right"><?php echo number_format(@$row->H_Jual_Executive, 2) ?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
            	<div class="col-lg-12">
                	<div class="table-responsive">
                        <table class="table reports-table"  >
                            <tbody>
                                <tr>
                                    <td width="30%">&nbsp;</td>
                                    <td width="40%">&nbsp;</td>
                                    <td align="center" width="30%"></td>
                                </tr>
                                <tr>
                                    <td align="center"><?php echo lang( "reports:madeby_label" ) ?> ,</td>
                                    <td>&nbsp;</td>
                                    <td align="center"><?php echo lang( "reports:receiver_label" ) ?> ,</td>
                                </tr>
                                <tr>
                                    <td style="height: 50px;"></td>
                                    <td></td>
                                    <td style="height: 50px;"></td>
                                </tr>
                                <tr>
                                    <td align="center">(_____________________________________)</td>
                                    <td></td>
                                    <td align="center">(_____________________________________)</td>
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
