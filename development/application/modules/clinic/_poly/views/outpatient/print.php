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
    	.tulisan{
			color:#000;
			font-style:normal;
			font-weight:2000px;
		}
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;padding-top:30px;">
        	<p><?php echo $nrm ?></p>
            <p><?php echo $dob ?></p>
        </div>
    </div>
</body>
</html>
