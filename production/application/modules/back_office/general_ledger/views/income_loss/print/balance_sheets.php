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
            	<h3>Laporan Neraca</h3>
            	<h5>Periode <?php echo date("F Y", strtotime($date)) ?></h5>
            </div>
            

            <div class="col-sm-12" style="padding:0;">
                <div class="">
                    <table class="table reports-table"  style="border:1px solid black; font-size:10px">
                        <thead>
                            <tr style="border:1px solid black;">
                                <th align="center"><h3>Activa</h3></th>
                                <th align="center"><h3>Pasiva</h3></th>                        
                            </tr> 
                        </thead>
                        <tbody>	
                        	<tr style="border:1px dotted black; "s>
                            	<td width="50%">
                                	<table class="table reports-table"  style="border:1px solid black; font-size:10px">
										<?php if(!empty($collections['activa'])) : foreach ($collections['activa'] as $item) : ?>
                                        <tr style="border:1px dotted black; ">
                                            <td style="padding:2px;"><?php echo str_repeat("&nbsp;", ($item->a_level - 1) * 4); echo @$item->a_account_name ?></td>
                                            <td align="right" style="padding:2px;"><?php echo @$item->a_value ?></td>
                                        </tr>
                                        <?php endforeach; endif;?>
                                    </table>
                            	</td>
                            	<td width="50%">
                                	<table class="table reports-table"  style="border:1px solid black; font-size:10px">
										<?php if(!empty($collections['pasiva'])) : foreach ($collections['pasiva'] as $item) : ?>
                                        <tr style="border:1px dotted black; ">
                                            <td style="padding:2px;"><?php echo str_repeat("&nbsp;", ($item->p_level - 1) * 4); echo @$item->p_account_name ?></td>
                                            <td style="padding:2px;"><?php echo @$item->p_value ?></td>
                                        </tr>
                                        <?php endforeach; endif;?>
                                    </table>
                            	</td>
                            </tr>        
                            <tr>
                                <td width="50%">
                                	<table  class="table reports-table">
                                    	<tr style="border:1px solid black">
                                        	<td><h5>&nbsp;Total Activa</h5></td>
                                            <td align="right"><h5>Rp. <?php echo number_format($collections['activa'][0]->a_value, 2)?></h5></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="50%">
                                	<table  class="table reports-table">
                                    	<tr style="border:1px solid black">
                                        	<td><h5>&nbsp;Total Pasiva</h5></td>
                                            <td align="right"><h5>Rp. <?php echo number_format($collections['pasiva'][0]->p_value, 2) ?></h5></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2" align="center"><h5>Rp. <?php echo number_format($collections['activa'][0]->a_value - $collections['pasiva'][0]->p_value, 2) ?></h5></td>
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