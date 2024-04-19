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
                        <?php /* <img src="<?php echo base_url( "resource/images/logos" )."/".$report_logo ?> " /> */ ?>
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
                    <?php echo sprintf("%s %s %s", lang('reports:recap_transaction_heading'), lang("reports:section_label"), $section->SectionName ); ?><br>
                    <small style="color:#000"><?php echo lang('reports:periode_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo lang('reports:till_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_end)) ?></small>
                </h4>
            
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php echo sprintf("%s : %s", 'User', (!empty(@$user->Nama_Asli)) ? @$user->Nama_Asli : 'Semua' );?>
                </div>
                <div class="col-sm-12">
                    <?php echo sprintf("%s : %s", 'Shift', (!empty(@$shift->Deskripsi)) ? @$shift->Deskripsi : 'Semua' );?>
                </div>
            </div>
            <br>
			<?php $i = 1; $grandtotal = 0; if(!empty($collection['data'])) : foreach ($collection['data'] as $key => $transaction) :   ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo sprintf("%s", $key );?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="table_header">
                                <thead>
                                    <tr>
                                        <th><?php echo 'No'; ?></th>
                                        <th><?php echo 'Transaksi'; ?></th>
                                        <th><?php echo 'Tipe Pasien'; ?></th>
                                        <th><?php echo 'Item'; ?></th>
                                        <th><?php echo 'Qty'; ?></th>
                                        <th><?php echo 'Nilai'; ?></th>
                                        <th><?php echo 'Jasa Apotek'; ?></th>
                                        <th><?php echo 'Subtotal'; ?></th>
                                        <th><?php echo 'Diskon'; ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php $total_subtotal = 0; $total_nilai = 0; $total_jasa_resep = 0; $diskon_total = 0;
                                    if(!empty($transaction)) : foreach ($transaction as $evidence_number => $items): foreach($items as  $item): 
                                    $item = (object) $item;    
                                    ?>
                                    <?php 
                                        $sub_total = $item->Qty * $item->Nilai + $item->JasaResep + $item->HExt; 
                                        $total_subtotal += $sub_total;
                                        $total_nilai += $item->Nilai;
                                        $total_jasa_resep += $item->JasaResep;
                                        $diskon = $item->Qty * $item->Nilai * $item->Diskon / 100;
                                        $diskon_total += $diskon;
                                    ?>
                                    <tr>
                                        <td align="center"><?php echo $i++; ?></td>
                                        <td><?php echo @$evidence_number ?></td>
                                        <td><?php echo @$item->JenisKerjasama ?></td>
                                        <td><?php echo @$item->NamaObat ?></td>
                                        <td align="right"><?php echo @$item->Qty ?></td>
                                        <td align="right"><?php echo number_format(@$item->Nilai, 0, ",", ".") ?></td>
                                        <td align="right"><?php echo number_format(@$item->JasaResep, 0, ",", ".") ?></td>
                                        <td align="right"><?php echo number_format(@$sub_total, 0, ",", ".") ?></td>
                                        <td align="right"><?php echo number_format(@$diskon, 0, ",", ".") ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="5" align="right"><b><?php echo 'TOTAL' ?></b></td>
                                        <td align="right"><b><?php echo number_format(@$total_nilai, 2, ",", ".") ?></b></td>
                                        <td align="right"><b><?php echo number_format(@$total_jasa_resep, 2, ",", ".") ?></b></td>
                                        <td align="right"><b><?php echo number_format(@$total_subtotal, 2, ",", ".") ?></b></td>
                                        <td align="right"><b><?php echo number_format(@$diskon_total, 2, ",", ".") ?></b></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
            <?php $grandtotal += $total_subtotal - $diskon_total; ?>
			<?php endforeach; ?>
            	<br>
                <div class="row">
                	<div class="col-md-12">
                    	<table class="table_header">
                        	<tr>
                            	<td align="right"><b><?php echo 'GRANDTOTAL'?></b></td>
                            	<td align="right" width="150px"><b><?php echo number_format($grandtotal, 2, ",", ".")?></b></td>
                            </tr>
                        </table>
                    </div>
                </div>

            <br>
            <!-- TIPE PEMBAYARAN -->
            <div class="row">
                <div class="col-sm-12">
                    <?php echo 'PEMBAYARAN';?>
                </div>
                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table_header" style="width: 50%!important;">
                        <thead>
                            <tr>
                                <th><?php echo 'Tipe Pembayaran'; ?></th>
                                <th><?php echo 'Nilai'; ?></th>
                            </tr> 
                        </thead>
                        <tbody>	
                            <?php $_total_payment = 0;  if(!empty($collection['payment'])) : foreach($collection['payment'] as $type => $val ):
                                $_total_payment += $val;
                                ?>
                                
                                <tr>
                                    <td><?= $type ?></td>
                                    <td><?= number_format($val, 2) ?></td>
                                </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th><?=  number_format($_total_payment, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <br>
            <!-- end -->
            <!-- MERCHAN -->
            <div class="row">
                <div class="col-sm-12">
                    <?php echo 'PASIEN PEMBAYARAN MERCHAN';?>
                </div>
                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table_header" style="width: 50%!important;">
                        <thead>
                            <tr>
                                <th><?php echo 'Pasien'; ?></th>
                                <th><?php echo 'Nilai'; ?></th>
                            </tr> 
                        </thead>
                        <tbody>	
                            <?php $_total_merchan = 0;  if(!empty($collection['merchan'])) : foreach($collection['merchan'] as $pay ):
                                $_total_merchan += $pay->Nilai;
                                ?>
                                
                                <tr>
                                    <td><?= $pay->NamaPasien ?></td>
                                    <td><?= number_format($pay->Nilai, 2) ?></td>
                                </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th><?=  number_format($_total_merchan, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- end -->
            <br>
            <?php else: ?>
	            <h3 class="text-center"><?php echo lang("reports:none_data_label"); ?></h3>
            <?php endif;?>
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
