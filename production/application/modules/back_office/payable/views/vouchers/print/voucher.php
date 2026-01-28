<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
            	<?php if( $report_logo = $this->config->item( "report_logo" ) ):  ?>
                <div class="col-xs-2">
                	<img src="<?php echo base_url( "resource/images/logos" )."/".$report_logo ?>" />
                </div>
                <div class="col-xs-6">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telp : </strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telp : </strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row" style="margin:0; padding-top:20px !important;">
                <h4 align="center"><u><b>VOUCHER</b></u></h4>
            </div>
			
            <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                    <table  class="" style="font-size:12px">
                        <tr>
                            <td width="30%"><?php echo lang("vouchers:no_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td ><?php echo $item->No_Voucher ?></td>
                        </tr>
                        <tr>
                            <td ><?php echo lang("vouchers:date_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td ><?php echo date("d-M-Y", strtotime( substr($item->Tgl_Voucher, 0, 10) )); ?></td>
                        </tr>
                        <tr>
                            <td ><?php echo lang("vouchers:to_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td><?php echo $item->Nama_Supplier ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
			 <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                    <table  class="" style="font-size:12px">
                        <tr>
                        	<th><?php echo lang("vouchers:no_label")?></th>
                        	<th><?php echo lang("vouchers:date_label")?></th>
                        	<th><?php echo lang("vouchers:supplier_name_label")?></th>
                        	<th></th>
                        	<th></th>
                        	<th></th>
                        	<th></th>
                        	<th></th>
                        </tr>
                        <tr>
                            <td ><?php echo lang("vouchers:date_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td ><?php echo date("d-M-Y", strtotime( substr($item->Tgl_Voucher, 0, 10) )); ?></td>
                        </tr>
                        <tr>
                            <td ><?php echo lang("vouchers:to_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td><?php echo $item->Nama_Supplier ?></td>
                        </tr>
                    </table>
                </div>
            </div>            
            
        </div>
    </div>
</body>
</html>
