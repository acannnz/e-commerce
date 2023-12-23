<!DOCTYPE html>
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
                	<?php /*<img src="<?php echo base_url( "resource/images/logos" )."/".$report_logo ?> " /> */ ?>
                </div>
                <div class="col-xs-6">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h3><?php echo lang('reports:warehouse_card_heading'); ?></h3>
            	<h5><?php echo lang('reports:periode_label'); ?> <?php echo $post_data->date_start ?>  <?php echo lang('reports:till_label'); ?> <?php echo $post_data->date_end ?></h5>
            </div>
            

            <div class="row">
            	<div class="col-sm-5">
                    <table width="400px">
                        <tr>
                            <td><?php echo lang("reports:section_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo $section->SectionName ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang("reports:code_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo $barang->Kode_Barang ?> </td>
                        </tr>
                        <tr>
                            <td><?php echo lang("reports:item_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo $barang->Nama_Barang ?> </td>
                        </tr>
                        <tr>
                            <td><?php echo lang("reports:category_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo $barang->Nama_Kategori ?></td>
                        </tr>
                        <tr>
                            <td ><?php echo lang("reports:beginning_stock_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$collection[0]->QtySaldo ." / ". @$barang->Kode_Satuan ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang("reports:ending_stock_label") ?></td>
                            <td>&nbsp;:&nbsp;</td>
                            <td><?php echo @$last_data->QtySaldo ." / ". @$barang->Kode_Satuan ?> </td>
                        </tr>
                    </table>
				</div>
            </div>
            <div class="row">
            	<div class="col-sm-12">
                	<div class="">
                    	<table class="table"  style="border:1px solid black; font-size:10px">
                            <thead>
                            	<tr style="border:1px solid black;">
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:no_label'); ?></th>
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:evidence_number_label'); ?></th>
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:doctor_label'); ?></th>
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:patient_label'); ?></th>
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:address_label'); ?></th>
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:user_label'); ?></th>
	                            	<th align="center" style="border:1px solid black; padding:8px;">(+)</th>
	                            	<th align="center" style="border:1px solid black; padding:8px; color:#FF0000;">(-)</th>
	                            	<th align="center" style="border:1px solid black; padding:8px;"><?php echo lang('reports:last_stock_label'); ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
								<?php $i = 1 ;  if(!empty($collection)) : foreach ($collection as $row) :   ?>
                            	<tr style="border:1px dotted black; ">
                                	<td width="6" align="center" style="border:1px solid black; padding:2px;"><?php echo $i++; ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$row->No_Bukti ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$row->Nama_Supplier ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$row->NamaPasien ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$row->Alamat ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$row->Nama_Singkat ?></td>
                                	<td align="center" style="border:1px solid black; padding:2px;"><?php echo @$row->Qty_Masuk ?></td>
                                	<td align="center" style="border:1px solid black; padding:2px;"><?php echo @$row->QtyKeluar ?></td>
                                	<td align="center" style="border:1px solid black; padding:2px;"><?php echo @$row->QtySaldo ?></td>
                                </tr>
                                <?php endforeach; else: ?>
                            	<tr style="border:1px dotted black;">
                                	<td colspan="8" align="center" style="border:1px solid black; padding:2px;"><?php echo lang("reports:none_data_label"); ?></td>
                                </tr>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
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
