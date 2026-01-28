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
            	<?php if( $invoice_logo = config_item( "invoice_logo" ) ): ?>
                <div class="col-xs-2" style="background-color: #ccc;">
                	<img src="<?php echo base_url( "resource/images/logos" )."/".$invoice_logo ?> " style="width:100%;height: auto;" />
                </div>
                <div class="col-xs-9">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo config_item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", config_item('company_address'), config_item( "company_city" ), config_item( "company_country" ), (config_item( "company_zip_code" ) ? " (".config_item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo $house->phone_service ?></span></p>
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
            	<h3>Laporan Jurnal</h3>
            	<h5>Periode <?php echo $post_data->date_start ?> s/d <?php echo $post_data->date_till ?></h5>
            </div>
            

            <div class="col-sm-12" style="padding:0;">
                <div class="">
                    <table class="table reports-table"  style="border:1px solid black; font-size:10px">
                        <thead>
                            <tr style="border:1px solid black; padding:10px;">
                                <th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:journal_date_label") ?></th>
                                <th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:journal_number_label") ?></th>
                                <th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:account_number_label") ?></th>                        
								<th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:account_name_label") ?></th>                        
								<th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:notes_label") ?></th>                        
                                <th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:debit_label") ?></th>
                                <th style="border:1px solid black; padding:10px;"><?php echo lang("general_ledger:credit_label") ?></th>                        
                            </tr> 
                        </thead>
                        <tbody>	
                            <?php if(!empty($collection)) : foreach ($collection as $row) :   ?>
                            <tr style="border:1px dotted black; ">
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo @$row->Tanggal ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo @$row->NoBukti ?></td>
                                <td style="border:1px solid black; padding:2px;"><?php echo @$row->NoAkun ?></td>
								<td style="border:1px solid black; padding:2px;"><?php echo @$row->NamaAkun ?></td>
								<td style="border:1px solid black; padding:2px;"><?php echo @$row->Keterangan ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo @$row->Debit; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo @$row->Kredit; ?></td>
                            </tr>
                            <?php endforeach; endif;?>
                            <tr style="border:1px solid black;">
                            	<td colspan="4" style="border:1px solid black; padding:5px;">
                                    <span style="font-weight:bold"><?php echo lang("general_ledger:balance_label")  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:5px;">
                                    <span style="font-weight:bold">Rp. <?php echo @$balance;  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:5px;">
                                    <span style="font-weight:bold">Rp. <?php echo @$debit;  ?></span>
                                </td>
                                <td align="right" style="border:1px solid black; padding:5px;">
                                    <span style="font-weight:bold">Rp. <?php echo @$credit; ?></span>
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
                                <td align="right" style="border:none;"><?php echo sprintf("%s, %s", config_item('company_city'), date("d F Y")) ?></td>
                            </tr>
                            <tr>
                                <td style="height: 40px;border:none;"></td>
                                <td style="border:none;"></td>
                                <td style="height: 40px;border:none;"></td>
                            </tr>
                            <tr>
                                <td align="center" style="border-bottom:1px solid black;"></td>
                                <td style="border:none;"></td>
                                <td align="right" style="border-bottom:1px solid black;"><?php echo $this->user_auth->Nama_Asli ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>