<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>

    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
  <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
            	<?php if( $invoice_logo = $this->config->item( "invoice_logo" ) ): ?>
                <div class="col-xs-2" style="background-color: #ccc;">
                	<img src="<?php echo base_url( "resource/images/logos" )."/".$invoice_logo ?> " style="width:100%;height: auto;" />
                </div>
                <div class="col-xs-9">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $house->address, $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo $house->phone_service ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo $house->house_name ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $house->address, $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo $house->phone_service ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h3>Laporan Kartu Hutang</h3>
            </div>
            
            <div class="row">
            	<div class="col-md-6">
	            	<h5><?php echo sprintf("%s: %s", lang("reports:supplier_label"), $data->supplier_name ) ?></h5>
                </div>
            	<div class="col-md-6">
	            	<h5><?php echo sprintf("%s: %s %s %s", lang("reports:periode_label"), $data->date_start, lang("reports:till_label"), $data->date_end ) ?></h5>
                </div>
            </div>

            <div class="col-sm-12" style="padding:0;">
                <div class="">
                    <table class="table reports-table"  style="border:1px solid black; font-size:10px">
                        <thead>
                            <tr style="border:1px solid black;">
                                <th style="border:1px solid black; padding:2px;"><?php echo lang('reports:number_label'); ?></th>
                                <th style="border:1px solid black; padding:2px;"><?php echo lang('reports:date_label'); ?></th>
                                <th style="border:1px solid black; padding:2px;"><?php echo lang("reports:evidence_number_label") ?></th>
                                <th style="border:1px solid black; padding:2px;"><?php echo lang("reports:description_label") ?></th>
                                <th style="border:1px solid black; padding:2px;"><?php echo lang("reports:beginning_balance_label") ?></th>                        
                                <th style="border:1px solid black; padding:2px;"><?php echo lang("reports:debit_label") ?></th>
                                <th style="border:1px solid black; padding:2px;"><?php echo lang("reports:credit_label") ?></th>                        
                                <th style="border:1px solid black; padding:2px;"><?php echo lang("reports:ending_balance_label") ?></th>                        
                            </tr> 
                        </thead>
                        <tbody>	
                            <?php 
								$i = 1;
								$tot_beginning_balance =
									$tot_debit =
										$tot_credit =
											$tot_ending_balance = 0;
                            ?>
                            <?php if(!empty( $collections )): foreach( $collections as $item ): 
							
								$tot_ending_balance = $tot_ending_balance + $item->beginning_balance + $item->credit - $item->debit;
								$tot_beginning_balance = $tot_beginning_balance + $item->beginning_balance;
								$tot_debit = $tot_debit + $item->debit;
								$tot_credit = $tot_credit + $item->credit;
							
							?>
                            <tr style="border:1px dotted black; ">
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo @$i ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo @$item->date ?></td>
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo @$item->evidence_number ?></td>
                                <td align="left" style="border:1px solid black; padding:2px;"><?php echo @$item->description ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo number_format(@$item->beginning_balance, 2, ",", "."); ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo number_format(@$item->debit, 2, ",", "."); ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo number_format(@$item->credit, 2, ",", "."); ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo number_format(@$tot_ending_balance, 2, ",", ".") ?></td>
                            </tr>
                            <?php $i++; endforeach; endif;?>
                            <tr>
                            	<td colspan="4" rowspan="2" align="center" valign="middle">
                                    <h4><span style="font-weight:bold"><?php echo lang("reports:grand_total_label")  ?></span></h4>
								</td>
                                <td style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold"><?php echo lang("reports:beginning_balance_label") ?></span>
                                </td>
                                <td  style="border:1px solid black; padding:2px;">
                                	<span style="font-weight:bold"><?php echo lang("reports:debit_label")  ?></span>
                                </td>
                                <td style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold"><?php echo lang("reports:credit_label") ?></span>
                                </td>
                                <td style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold"><?php echo lang("reports:ending_balance_label") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$tot_beginning_balance, 2, ",", ".");  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$tot_debit, 2, ",", ".");  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$tot_credit, 2, ",", ".");  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$tot_ending_balance, 2, ",", ".");  ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table reports-table" style="border:none;"  >
                        <tbody>
                            <tr>
                                <td width="30%" style="border:none;">&nbsp;</td>
                                <td width="40%" style="border:none;">&nbsp;</td>
                                <td align="center" width="30%" style="border:none;"></td>
                            </tr>
                            <tr>
                                <td align="center" style="border:none;"></td>
                                <td style="border:none;">&nbsp;</td>
                                <td align="right" style="border:none;"><?php echo sprintf("%s, %s" ,$house->address, date("d F Y")) ?></td>
                            </tr>
                            <tr>
                                <td style="height: 40px;border:none;"></td>
                                <td style="border:none;"></td>
                                <td style="height: 40px;border:none;"></td>
                            </tr>
                            <tr>
                                <td align="center" style="border-bottom:1px solid black;"></td>
                                <td style="border:none;"></td>
                                <td align="right" style="border-bottom:1px solid black;"><?php echo $this->tank_auth->get_username() ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>