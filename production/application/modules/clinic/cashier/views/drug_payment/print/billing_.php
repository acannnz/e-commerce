<?php 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" media="print" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" media="print" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-4" style="margin:0 !important;">

            <div class="row" style="margin-top:26px">
            	<div class="col-md-3" style="padding:0;">
                	<div class="table-responsive">
                    	<table width="100%"  style="font-size:26px !important">
                            <tbody>	           
                            	<tr>              
                                	<td colspan="5" style="padding:2px;" align="center">
                                    	<span style="font-size:30px"><?php echo $this->config->item( "company_name" ) ?></span>
                                    </td>
                                </tr>
                            	<tr>              
                                	<td colspan="5" align="center">
                                    	<p style="font-size:26px; margin:0 !important;">
											<?php echo sprintf("%s, %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ) ) ?>
                                        </p>
                                    </td>
                                </tr>
                            	<tr>              
                                	<td colspan="5" align="center" style="border-bottom:1px dashed #000000;">
										<p style="font-size:26px;">
                                        	<strong><?php echo lang( "drug_payment:phone_label" ) ?> :</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span>
                                        </p>                                    
                                    </td>
                                </tr>
                            	<tr>              
                                	<td colspan="5" align="center" style="padding:8px 2px;">
                                    	<span style="font-size:14px;"><?php echo strtoupper(lang('drug_payment:billing_subtitle')); ?></span>
                                    </td>
                                </tr>
                            	<tr >
                                	<td align="left" style="padding:2px;font-size:26px!important;" colspan="2"><p  style="font-size:26px;"><?php echo lang('drug_payment:no_buk_label'); ?> : <?php echo @$item->NoBukti ?></p></td>
                                    <td align="right" style="padding:2px;font-size:26px!important;" colspan="3"><p  style="font-size:26px;"><?php echo substr($item->Jam, 0, 19) ?></p></td>
                                </tr>
                            	<tr >
                                	<td align="left" style="padding:2px;font-size:26px!important;" colspan="2"><p  style="font-size:26px;"><?php echo lang('drug_payment:no_reg_label'); ?> : <?php echo !empty($item->NoReg) ? $item->NoReg : '-' ?></p></td>
                                    <td align="right" style="padding:2px;font-size:26px!important;" colspan="3"><p  style="font-size:26px;"><?php echo @$item->Nama_Supplier ?></td>
                                </tr>
                            	<tr style="border-bottom:1px dashed #000000;">
                                	<td align="left" style="padding:2px;font-size:26px!important;" colspan="3"><p  style="font-size:26px;"><?php echo lang('drug_payment:name_label'); ?> : <?php echo @$item->Keterangan ?></p></td>
                                    <td align="right" style="padding:2px;font-size:26px!important;" colspan="2"><p  style="font-size:26px;"><?php echo @$item->JenisKerjasama ?></td>
                                </tr>
                                <tr style="border-bottom:1px dashed #000000;">
	                            	<td align="center" style="padding:8px 2px;font-size:26px!important;border-bottom:1px dashed #000000"><?php echo lang('drug_payment:qty_label'); ?></td>
	                            	<td style="padding:8px 2px;font-size:26px!important;border-bottom:1px dashed #000000; font: bold;"><?php echo lang('drug_payment:item_name_label'); ?></td>
	                            	<td align="center" style="padding:8px 2px;font-size:26px!important;border-bottom:1px dashed #000000; font:bold;"><?php echo lang('drug_payment:value_label'); ?></td>
	                            	<td align="center" style="padding:8px 2px;font-size:26px!important;border-bottom:1px dashed #000000; font:bold;"><?php echo lang('drug_payment:disc_label'); ?></td>
	                            	<td align="right" style="padding:8px 2px;font-size:26px!important;border-bottom:1px dashed #000000; font:bold;"><?php echo lang('drug_payment:total_label'); ?></td>
                                </tr> 

								<?php $i = 1 ;  if(!empty($collection)) : foreach ($collection as $row) :   ?>
                            	<tr>
                                	<td align="center" style="padding:5px 2px;font-size:26px!important;"><?php echo $row->Qty; ?></td>
                                    <td width="60%" style="padding:5px 3px;font-size:26px!important;"><?php echo @$row->Nama_Barang ?></td>
                                	<td align="center" style="padding:5px 2px;font-size:26px!important;"><?php echo number_format(@$row->Harga, 2, ".", ",") ?></td>
                                    <td align="center" style="padding:5px 2px;font-size:26px!important;"><?php echo @$row->Disc ?></td>
                                	<td align="right" style="padding:5px 2px;font-size:26px!important;"><?php echo number_format(@$row->SubTotal, 2, ".", ",") ?></td>
                                </tr>
                                <?php $i++; endforeach; endif;?>
                                <tr style="border-top:1px dashed #000000;border-bottom:1px dashed #000000;">
                                	<td colspan="5" align="right" style="padding:26px 3px 26px;border-bottom:1px dashed #000000;"><?php echo sprintf("%s : %s %s", lang('drug_payment:grand_total_label'), "Rp.", number_format(@$grand_total, 2, ".", ",")) ?></td>
                                </tr>                                
                                <tr>
                                	<td colspan="5" style="padding:1px;border-bottom:1px dashed #000000;"></td>
                                </tr>
                                <tr>
                                	<td colspan="3" align="left" style="padding:1px;border-bottom:1px dashed #000000;font-size:9px!important;"><?php echo date("Y-m-d H:i:s") ?></td>
                                	<td colspan="2" align="right" style="padding:1px;border-bottom:1px dashed #000000;font-size:9px!important;"><?php echo $user->Nama_Singkat ?></td>
                                </tr>
                                <tr style="border-bottom:1px dashed #000000;">
                                	<td colspan="5" align="center" style="padding:2px 3px 5px;border-bottom:1px dashed #000000;">THANK YOU</td>
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
