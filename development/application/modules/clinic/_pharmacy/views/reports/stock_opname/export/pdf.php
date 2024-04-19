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
            	<h4><?php echo sprintf("%s %s %s", lang('reports:stock_opname_heading'), lang("reports:section_label"), @$section->SectionName ); ?><br>
                <small style="color:#000"><?php echo lang('reports:periode_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo lang('reports:till_label'); ?> <?php echo date('d-m-Y', strtotime($post_data->date_end)) ?></small>
                </h4>
            </div>
            
            <!-- KELOMPOK NEGATIF POSITIF-->
			<?php $i = 1 ; if(!empty($collection)) : foreach ($collection as $k_group => $v_group) :   ?>
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="text-danger"><?php echo $k_group ?></h2>
                    </div>
                </div>
            	<!-- TANGGAL OPNAME dan user-->
				<?php if(!empty($v_group)) : foreach ($v_group as $k_date => $v_date) :   ?>
                	<?php list($date, $user, $evidence_number) = explode( "|", $k_date ) ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table width="50%">
                                <tr>
                                    <td><?php echo lang("reports:evidence_number_label") ?></td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td><?php echo $evidence_number ?> </td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("reports:date_label") ?></td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td><?php echo $date ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("reports:user_label") ?></td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td><?php echo $user ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                                <table class="table_header">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('reports:no_label'); ?></th>
                                            <th><?php echo lang('reports:code_label'); ?></th>
                                            <th><?php echo lang('reports:item_label'); ?></th>
                                            <th><?php echo lang('reports:unit_label'); ?></th>
                                            <th><?php echo lang('reports:qty_system_label'); ?></th>
                                            <th><?php echo lang('reports:qty_physical_label'); ?></th>
                                            <th><?php echo lang('reports:difference_label'); ?></th>
                                            <th><?php echo lang('reports:price_@_label'); ?></th>
                                            <th><?php echo lang('reports:amount_label'); ?></th>
                                            <th><?php echo "Jumlah Fisik"; ?></th>
                                            <th><?php echo lang('reports:description_label'); ?></th>
                                        </tr> 
                                    </thead>
                                    <tbody>	
                                        <?php $total = 0; $totalFisik = 0; if(!empty($v_date)) : foreach ($v_date as $row) : 											
											$sub_total = @$row->Selisih * @$row->Harga_Rata;
											$sub_total_fisik = @$row->Harga_Rata*@$row->Qty_Opname;
											$totalFisik = $totalFisik +  $sub_total_fisik;
											$total = $total +  $sub_total;
											
											if( !empty($post_data->show_zero_difference) && $post_data->show_zero_difference === 0 &&  @$row->Selisih == 0 )
											continue;
										?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td align="center"><?php echo @$row->Kode_Barang ?></td>
                                            <td><?php echo @$row->Nama_Barang ?></td>
                                            <td><?php echo @$row->Satuan_Stok ?></td>
                                            <td align="right"><?php echo @$row->Stock_Akhir ?></td>
                                            <td align="right"><?php echo @$row->Qty_Opname ?></td>
                                            <td align="right"><?php echo @$row->Selisih ?></td>
                                            <td align="right"><?php echo number_format(@$row->Harga_Rata, 2) ?></td>
                                            <td align="right"><?php echo number_format( $sub_total, 2) ?></td>
                                            <td align="right"><?php echo number_format(@$sub_total_fisik, 2) ?></td>
                                            <td><?php echo @$row->Keterangan ?></td>
                                        </tr>
                                        <?php endforeach ?> 
                                    	<?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8" align="right"><b><?php echo 'Subtotal '. $k_group ?></b></td>
                                            <td align="right"><b><?php echo number_format( $total, 2); ?></b></td>
                                            <td align="right"><b><?php echo number_format( $totalFisik, 2); ?></b></td>
                                            <td align="right"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                        </div>
                    </div>
				<?php endforeach; endif;?>
			<?php endforeach; else: ?>
	            <h3><?php echo lang("reports:none_data_label"); ?></h3>
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
