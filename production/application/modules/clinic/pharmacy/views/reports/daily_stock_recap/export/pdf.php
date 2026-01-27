<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Laporan Rekap Stok Harian</title>
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
            background-color: #f2f2f2;
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
                    Laporan Rekap Stok Harian (Section: <?php echo @$section->SectionName ?>)<br>
                    <small style="color:#000"><?php echo lang('reports:periode_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo lang('reports:till_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_end)) ?></small>
                </h4>
            </div>
            
			<?php $i = 1 ; if(!empty($collection)) : foreach ($collection as $key => $value) :   ?>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo sprintf("%s : %s", lang("reports:group_label"), $key );?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                            <table class="table_header">
                                <thead>
                                    <tr>
                                        <th align="center" width="30px"><?php echo lang('reports:no_label'); ?></th>
                                        <th align="center" width="120px"><?php echo lang('reports:code_label'); ?></th>
                                        <th align="center"><?php echo lang('reports:item_label'); ?></th>
                                        <th align="center" width="80px"><?php echo lang('reports:unit_label'); ?></th>
                                        <th align="right" width="100px">SALDO AWAL</th>
                                        <th align="right" width="80px">MASUK</th>
                                        <th align="right" width="80px">KELUAR</th>
                                        <th align="right" width="80px">STOK AKHIR</th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php if(!empty($value)) : foreach ($value as $row) : ?>
                                    <tr>
                                        <td align="center"><?php echo $i++; ?></td>
                                        <td align="center"><?php echo @$row->KOde_Barang ?></td>
                                        <td><?php echo @$row->Nama_Barang ?></td>
                                        <td align="center"><?php echo @$row->Satuan_Stok ?></td>
                                        <td align="right"><?php echo @$row->SA ?></td>
                                        <td align="right"><?php echo @$row->MASUK ?></td>
                                        <td align="right"><?php echo @$row->KELUAR ?></td>
                                        <td align="right"><?php echo (@$row->SA + @$row->MASUK - @$row->KELUAR) ?></td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="8" align="center"><?php echo lang("reports:none_data_label"); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                    </div>
                </div>
			<?php endforeach; else: ?>
	            <h3 align="center"><?php echo lang("reports:none_data_label"); ?></h3>
            <?php endif;?>
            <br><br>
            <div class="row">
            	<div class="col-xs-12">
                	<div class="table-responsive">
                        <table class="table reports-table">
                            <tbody>
                                <tr>
                                    <td width="30%" align="center"><?php echo lang( "reports:madeby_label" ) ?> ,</td>
                                    <td width="40%">&nbsp;</td>
                                    <td align="center" width="30%"><?php echo lang( "reports:receiver_label" ) ?> ,</td>
                                </tr>
                                <tr>
                                    <td style="height: 60px;"></td>
                                    <td></td>
                                    <td style="height: 60px;"></td>
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
