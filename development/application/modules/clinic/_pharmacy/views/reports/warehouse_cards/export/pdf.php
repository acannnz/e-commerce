<!DOCTYPE html>
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
                    <?php echo lang('reports:warehouse_card_heading'); ?><br>
                    <small style="color:#000"><?php echo lang('reports:periode_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo lang('reports:till_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_end)) ?></small>
                </h4>
            </div>
            <div class="row">
            	<div class="col-sm-5">
                    <?php $qty_saldo_akhir = (strpos(@$collection[0]->No_Bukti, 'SAS') !== FALSE ) ? @$collection[0]->QtySaldo : 0;  
					if(!empty($collection)) : foreach ($collection as $row) :   ?>
						<?php $qty_saldo_akhir = @$qty_saldo_akhir + (@$row->Qty_Masuk - @$row->QtyKeluar) ?>
                    <?php endforeach; else: ?>
                    <?php endif;?>
                    <table width="400px">
                        <tr>
                            <td><?php echo lang("reports:section_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$section->SectionName ?></td>
                        </tr>
                        <tr>
                            <td><?php echo 'Kode Barang' ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$barang->Kode_Barang ?> </td>
                        </tr>
                        <tr>
                            <td><?php echo 'Nama Barang' ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$barang->Nama_Barang ?> </td>
                        </tr>
                        <tr>
                            <td><?php echo lang("reports:category_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$barang->Nama_Kategori ?></td>
                        </tr>
                        <tr>
                            <td ><?php echo lang("reports:beginning_stock_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$collection[0]->QtySaldo ." / ". @$barang->Kode_Satuan ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang("reports:ending_stock_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$qty_saldo_akhir ." / ". @$barang->Kode_Satuan ?> </td>
                        </tr>
                    </table>
				</div>
            </div>
            <div class="row">
            	<div class="col-sm-12">
                	<div class="">
                    	<table class="table_header">
                            <thead>
                            	<tr>
	                            	<th align="center"><?php echo lang('reports:no_label'); ?></th>
	                            	<th align="center"><?php echo lang('reports:evidence_number_label'); ?></th>
	                            	<th align="center"><?php echo lang('reports:doctor_label'); ?></th>
	                            	<th align="center"><?php echo lang('reports:patient_label'); ?></th>
	                            	<th align="center">Tgl. Rxpired</th>
	                            	<th align="center"><?php echo lang('reports:user_label'); ?></th>
	                            	<th align="center">(+)</th>
	                            	<th align="center" style="color:#FF0000;">(-)</th>
	                            	<th align="center"><?php echo lang('reports:last_stock_label'); ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
								<?php $i = 1; 
								$qty_saldo = (strpos($collection[0]->No_Bukti, 'SAS') !== FALSE ) ? $collection[0]->QtySaldo : 0;  
								if(!empty($collection)) : foreach ($collection as $row) :   ?>
								<?php $qty_saldo = @$qty_saldo + (@$row->Qty_Masuk - @$row->QtyKeluar) ?>
                            	<tr>
                                	<td width="6" align="center"><?php echo $i++; ?></td>
                                	<td><?php echo @$row->No_Bukti ?></td>
                                	<td><?php echo @$row->Nama_Supplier ?></td>
                                	<td><?php echo @$row->NamaPasien ?></td>
                                	<td><?php echo !empty(@$row->ExpDate) ? date("Y-m-d", strtotime(@$row->ExpDate)) : '-' ?></td>
                                	<td><?php echo @$row->Nama_Singkat ?></td>
                                	<td align="center"><?php echo @$row->Qty_Masuk ?></td>
                                	<td align="center"><?php echo @$row->QtyKeluar ?></td>
                                	<td align="center"><?php echo @$qty_saldo; //@$row->QtySaldo ?></td>
                                </tr>
                                <?php endforeach; else: ?>
                            	<tr>
                                	<td colspan="8" align="center"><?php echo lang("reports:none_data_label"); ?></td>
                                </tr>
                                <?php endif;?>
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <br><br>
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
