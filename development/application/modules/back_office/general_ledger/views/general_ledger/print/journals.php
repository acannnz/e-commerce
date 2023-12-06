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
            	<h3>Laporan Jurnal Transaksi Akun <?php echo $account->Akun_Name ?></h3>
            	<h5>Periode<?php echo $from ?> s/d <?php echo $till ?></h5>
            </div>
            

            <div class="col-sm-12" style="padding:0;">
                <div class="">
                    <table class="table reports-table"  style="border:1px solid black; font-size:10px">
                        <thead>
                            <tr style="border:1px solid black;">
                                <th style="border:1px solid black; padding:8px;"><?php echo lang('general_ledger:no_label'); ?></th>
                                <th><?php echo lang("general_ledger:journal_date_label") ?></th>
                                <th><?php echo lang("general_ledger:journal_number_label") ?></th>
                                <th><?php echo lang("general_ledger:notes_label") ?></th>                        
                                <th><?php echo lang("general_ledger:debit_label") ?></th>
                                <th><?php echo lang("general_ledger:credit_label") ?></th>                        
                                <th><?php echo lang("general_ledger:balance_label") ?></th>                        
                            </tr> 
                        </thead>
                        <tbody>	
                            <?php 
								
								$saldo = (float) $collections[0]->value; 
								$debit = $credit = 0;
							
                            	$i = 1;  if(!empty($collections)) : foreach ($collections as $item) :   
                            
								$debit = $debit + $item->debit;
								$credit = $credit + $item->credit;
                                
                                if ($account->normal_pos == "D")
                                    $saldo = $saldo + $item->debit - $item->credit; 
                                elseif ($account->normal_pos == "K")
                                    $saldo = $saldo + $item->credit - $item->debit; 
                            
                            ?>
                            <tr style="border:1px dotted black; ">
                                <td width="6" align="center" style="border:1px solid black; padding:2px;"><?php echo $i; ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo @$item->journal_date ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo @$item->journal_number ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo @$item->notes ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo number_format(@$item->debit, 2, ",", "."); ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo number_format(@$item->credit, 2, ",", "."); ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo number_format(@$saldo, 2, ",", ".") ?></td>
                            </tr>
                            <?php $i++; endforeach; endif;?>
                            <tr>
                            	<td colspan="2">
                                    <span style="font-weight:bold"><?php echo lang("general_ledger:beginning_balance_label")  ?></span>
								</td>
                                <td colspan="2">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$collections[0]->value, 2, ",", ".");  ?></span>
                                </td>
                                <td align="right">
                                	<span style="font-weight:bold"><?php echo lang("general_ledger:debit_label")  ?></span>
                                </td>
                                <td align="right" colspan="2">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$debit, 2, ",", "."); ?></span>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2">
                                    <span style="font-weight:bold"><?php echo lang("general_ledger:ending_balance_label")  ?></span>
                                </td>
                                <td colspan="2">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$saldo, 2, ",", ".");  ?></span>
                                </td>
                                <td align="right">
                                    <span style="font-weight:bold"> <?php echo lang("general_ledger:credit_label")  ?></span>
                                </td>
                                <td align="right"  colspan="2">
                                    <span style="font-weight:bold">Rp. <?php echo number_format(@$credit, 2, ",", "."); ?></span>
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