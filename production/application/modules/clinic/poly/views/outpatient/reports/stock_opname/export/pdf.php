<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <?php /*?><link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/><?php */?>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
            	<?php if( $report_logo = $this->config->item( "report_logo" ) ):  ?>
                <div class="col-xs-2">
                	<img src="<?php echo base_url( "resource/images/logos" )."/".$report_logo ?> " />
                </div>
                <div class="col-xs-6">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h3><?php echo sprintf("%s %s", lang('reports:stock_opname_heading'), lang("reports:section_label") ); ?></h3>
            	<h5><?php echo lang('reports:periode_label'); ?> <?php echo $post_data->date_start ?>  <?php echo lang('reports:till_label'); ?> <?php echo $post_data->date_end ?></h5>
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
                            <table width="100%">
                                <tr>
                                    <td><?php echo lang("reports:evidence_number_label") ?></td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td><?php echo $evidence_number ?> </td>

                                    <td><?php echo lang("reports:user_label") ?></td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td><?php echo $user ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang("reports:date_label") ?></td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td width="300px"><?php echo $date ?></td>
                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="">
                                <table class="" width="100%"  style="border:1px solid black; font-size:10px">
                                    <thead>
                                        <tr style="border:1px solid black;">
                                            <th align="center" style="padding:8px;"><?php echo lang('reports:no_label'); ?></th>
                                            <th align="center" style="padding:8px;"><?php echo lang('reports:code_label'); ?></th>
                                            <th align="center" style="padding:8px;"><?php echo lang('reports:item_label'); ?></th>
                                            <th align="center" style="padding:8px;"><?php echo lang('reports:unit_label'); ?></th>
                                            <th align="right" style="padding:8px;"><?php echo lang('reports:qty_system_label'); ?></th>
                                            <th align="right" style="padding:8px;"><?php echo lang('reports:qty_physical_label'); ?></th>
                                            <th align="right" style="padding:8px;"><?php echo lang('reports:difference_label'); ?></th>
                                            <th align="right" style="padding:8px;"><?php echo lang('reports:price_@_label'); ?></th>
                                            <th align="right" style="padding:8px;"><?php echo lang('reports:amount_label'); ?></th>
                                            <th align="right" style="padding:8px;"><?php echo lang('reports:description_label'); ?></th>
                                        </tr> 
                                    </thead>
                                    <tbody>	
                                        <?php $total = 0; if(!empty($v_date)) : foreach ($v_date as $row) : if($i == 400) { break; } ?>
                                        <?php 
											$sub_total = @$row->Selisih * @$row->Harga_Rata;
											$total = $total +  $sub_total;
										?>
                                        <tr style="">
                                            <td align="center" width="10px" style="padding:2px;"><?php echo $i++; ?></td>
                                            <td align="center" width="150px" style="padding:2px;"><?php echo @$row->Kode_Barang ?></td>
                                            <td style="padding:2px;"><?php echo @$row->Nama_Barang ?></td>
                                            <td width="50px" style="padding:2px;"><?php echo @$row->Satuan_Stok ?></td>
                                            <td align="right" width="75px" style="padding:2px;"><?php echo @$row->Stock_Akhir ?></td>
                                            <td align="right" width="75px" style="padding:2px;"><?php echo @$row->Qty_Opname ?></td>
                                            <td align="right" width="50px" style="padding:2px;"><?php echo @$row->Selisih ?></td>
                                            <td align="right" width="50px" style="padding:2px;"><?php echo @$row->Harga_Rata ?></td>
                                            <td align="right" width="75px" style="padding:2px;"><?php echo number_format( $sub_total, 0, ",", ".") ?></td>
                                            <td width="200px" style="padding:2px;"><?php echo @$row->Keterangan ?></td>
                                        </tr>
                                        <?php endforeach ?> 
                                        <tr style="border:1px solid black;">
                                            <td colspan="9" align="right" style="padding:2px;"><h4><?php echo @$evidence_number ?></h4></td>
                                            <td align="right" style="padding:2px;"><h4><?php echo number_format( $total, 0, ",", "." ); ?></h4></td>
                                        </tr>
                                    	<?php endif; ?>
                                    </tbody>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
				<?php endforeach; endif;?>
			<?php endforeach; else: ?>
	            <h1><?php echo lang("reports:none_data_label"); ?></h1>
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
