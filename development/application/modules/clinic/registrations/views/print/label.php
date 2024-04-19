<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/>
    <style type="text/css">
		p{
			font-size:9px;
			margin-left:12px!important;
		}
    </style>
</head>
<body>
    <div class="line" style="margin-top:5px !important;padding:0px !important;">
        <p><b><?php echo sprintf("%s (%s)", @$item->NamaPasien_Reg, $item->JenisKelamin) ?></b></p>
    </div>
    <div class="line" style="padding:0px !important;">
        <p><b><?php echo sprintf("%s (%s Thn)", substr(@$item->TglLahir, 0, 10), @$item->UmurThn) ?></b></p>
    </div>
    <div class="line" style="padding:0px !important;">
        <p><b><?php echo sprintf("%s (%s)", $item->NoReg, $item->NRM) ?></b></p>
    </div>
    <div class="line" style="margin-top:4px; padding:0px !important;">
        <barcode code="<?php echo @$item->NoRegLabel ?>" type="C39" size="0.9" height="1.5" />
    </div>
</body>
</html>
