<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <style>
		#kwitansi_bg {
			background:url('<?php echo base_url("public/resource/print/kwitansi.png")?>');
			background-size: contain;			
			background-repeat:no-repeat;
			width:100%;
			height:300px;
			
		}
		
		.row {
			width:100%;
			display:block;
			font-size:14px;
		}

		#NoBukti {
			text-indent:165px;
			padding-top:15px;
			top:-100px;
			font-weight:bold;
			font-style:italic;
			position:fixed;
		}

		#NamaPasien {
			text-indent:130px;
			padding-top:8px;
			padding-left:150px;
			font-weight:bold;
			font-style:italic;
			position:absolute;
		}

		#Spelled {
			text-indent:135px;
			padding-top:30px;
			padding-left:150px;
			font-weight:bold;
			font-style:italic;
			position:absolute;
		}

		#ForPayment {
			text-indent:130px;
			padding-top:30px;
			padding-left:150px;
			font-weight:bold;
			font-style:italic;
			position:absolute;
		}

		#Location {
			text-indent:380px;
			padding-top:30px;
			padding-left:150px;
			font-weight:bold;
			font-style:italic;
			position:absolute;
		}
		
		#NilaiPembayaran {
			text-indent:120px;
			padding-top:65px;
			padding-left:130px;
			font-weight:bold;
			font-style:italic;
			position:absolute;
			font-size:14px;
			width:50%;
			float:left;
		}

		#User {
			text-indent:130px;
			padding-left:400px;
			padding-right:20px;
			font-weight:bold;
			font-style:italic;
			position:absolute;
			font-size:14px;
			width:50%;
			float:right;
			text-align:right;

		}
		
	</style>
</head>
<body>
    <div id="kwitansi_bg">
        <div id="NoBukti" class="row"><?php echo $item->NoBukti ?></div>
        <div id="NamaPasien" class="row"><?php echo $item->DIterimaDari ?></div>
        <div id="Spelled" class="row"><?php echo ucwords($spelled." rupiah") ?></div>
        <div id="ForPayment" class="row"><?php echo $for_payment ?></div>
        <div id="Location" class="row"><?php echo sprintf("%s, %s", $this->config->item( "company_city" ), date("Y-m-d") )?></div>
        <div id="NilaiPembayaran" class=""><?php echo number_format($item->Nilai, 2, ',', '.') ?></div>
        <div id="User" class=""><?php echo $user->Nama_Asli ?></div>
    </div>
</body>
</html>
