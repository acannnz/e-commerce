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
            	<?php if( config_item( "report_logo" ) ): ?>
                <div class="col-xs-2" >
                	<img src="<?php echo base_url( "resource/images/logos" )."/".config_item('report_logo') ?> " style="width:100%;height: auto;" />
                </div>
                <div class="col-xs-9">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo config_item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", config_item('company_address'), config_item( "company_city" ), config_item( "company_country" ), (config_item( "company_zip_code" ) ? " (".config_item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo config_item('company_phone') ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo config_item( "company_name" ) ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", config_item('company_address'), config_item( "company_city" ), config_item( "company_country" ), (config_item( "company_zip_code" ) ? " (".config_item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo config_item('company_phone') ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h4><?php echo lang('reports:card_payable_label')?></h4>
            </div>
            
            <div class="row">
            	<div class="col-md-6">
	            	<h5><?php echo sprintf("%s: %s", lang("reports:supplier_label"), $data->supplier_name ) ?></h5>
	            	<h5><?php echo sprintf("%s: %s %s %s", lang("reports:periode_label"), date("d/m/Y", strtotime($data->date_start)), lang("reports:till_label"), date("d/m/Y", strtotime($data->date_end)) ) ?></h5>
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
							
								$tot_ending_balance = $tot_ending_balance + $item->SaldoAwal + $item->Kredit - $item->Debet;
								$tot_beginning_balance = $tot_beginning_balance + $item->SaldoAwal;
								$tot_debit = $tot_debit + $item->Debet;
								$tot_credit = $tot_credit + $item->Kredit;
							
							?>
                            <tr >
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo @$i ?></td>
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo date("d-m-Y", strtotime(substr($item->Tanggal, 0, 10))) ?></td>
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo @$item->No_Bukti ?></td>
                                <td align="left" style="border:1px solid black; padding:2px;"><?php echo @$item->Keterangan ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->SaldoAwal != 0) ? number_format(@$item->SaldoAwal, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->Debet != 0) ? number_format(@$item->Debet, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->Kredit != 0) ? number_format(@$item->Kredit, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo number_format(@$tot_ending_balance, 2, ".", ",") ?></td>
                            </tr>
                            <?php $i++; endforeach; endif;?>
                            <tr style="border:1px solid black; ">
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
                                    <span style="font-weight:bold"><?php echo number_format(@$tot_beginning_balance, 2, ".", ",");  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold"><?php echo number_format(@$tot_debit, 2, ".", ",");  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold"><?php echo number_format(@$tot_credit, 2, ".", ",");  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:2px;">
                                    <span style="font-weight:bold"><?php echo number_format(@$tot_ending_balance, 2, ".", ",");  ?></span>
                                </td>
                            </tr>
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
                                    <td align="center"><?php echo lang( "reports:madeby_label" ) ?>,</td>
                                    <td>&nbsp;</td>
                                    <td align="center"><?php echo lang( "reports:approvedby_label" ) ?>,</td>
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