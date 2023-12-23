<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= @$file_name ?></title>
    <link href="<?= base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?= base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
	<style>
		.table {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			width: 40%;
		}

        .font {
            font-family: "Tams New Roman", serif;
            letter-spacing: 4px;
        }

        .font2 {
            font-family: "Verdana", sans-serif;
            letter-spacing: 3px;
        }
		
		.table_head td, .table_head th {
			padding: 0px;
            font-size:12px;
		}
		
		.table_detail td, .table_detail th {
			border: 1px solid #ddd;
			padding: 8px;
		}
		
		.table_detail th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #CCC;
			color: #333;
		}

        .table1 {
			font-family: sans-serif;
			font-size: 12px;
			color: #232323;
			border-collapse: collapse;
			border: 2px solid #999;
			padding: 28px;
			float: right;
		}

        .kolom1 {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px;
        }

        .kolom2 {
            float: right;
            width: 50%;
            padding: 10px;
            height: 300px;
        }

        .border-body{
		border-left: 1px solid black;
        border-right:1px solid black;
        width: 100% !important;
        padding: 5px !important;
        }

        .border-top {
            border-top: 1px solid black;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }  
        
        body {
            background-image: url("public/themes/default/assets/img/aa-racmat.png") !important;
            background-repeat: no-repeat;
        }

	</style>
</head>
<body>
    <div class="row">
    	<div class="col-xs-12" width="100%">
            <div class="col-xs-7">
                <img align="left"  width="85%" src="<?= base_url( "resource/images/logos/Butawarna.png" ) ?>" />
                <table class="table_head" width="90%">
                    <tbody>
                        <tr>
                            <td colspan="3" style="font-size: 12px;" class="text-right">Yang bertanda tangan dibawah ini menerangkan bahwa :</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; padding-top:20px;" width="25%">Nama</td>
                            <td style="font-size: 12px; padding-top:20px;">: &nbsp; <?php echo @$item->NamaPasien ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; padding-top:6px;">Jenis Kelamin</td>
                            <?php $JenisKelamin =   [
                                                        ['id'=> 'M', 'desc' => 'Laki-Laki'],
                                                        ['id'=> 'F', 'desc' => 'Perempuan']
                                                    ] ?>
                            <td style="font-size: 12px; padding-top:6px;">: &nbsp;
                                <?php if (!empty($JenisKelamin)) : foreach($JenisKelamin as $dt) : ?>
                                    <?php echo ($item->JenisKelamin == @$dt['id']) ? @$dt['desc'] : null ?>
                                <?php endforeach; endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; padding-top:6px;">Umur</td>
                            <td style="font-size: 12px; padding-top:6px;">: &nbsp; <?php echo @$item->UmurThn ?> Tahun</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; padding-top:6px;">Pekerjaan</td>
                            <td style="font-size: 12px; padding-top:6px;">: &nbsp; <?php echo @$item->Pekerjaan ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; padding-top:6px;">Alamat</td>
                            <td style="font-size: 12px; padding-top:6px;">: &nbsp; <?php echo @$item->Alamat ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px; padding-top:30px;">Memang telah diperiksa menggunakan Ishihara Test, yang </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px; padding-top:3px;">bersangkutan dinyatakan <strong><?php echo @$post['Keterangan_Surat'] ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-5 text-right">
                <table class="table_head">
                    <tbody>
                        <tr>
                            <td colspan="3" style="font-size: 14px; padding-top:10px;" class="text-right"><strong> No. : <?php echo str_replace("-","/",@$item->NoReg) ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px; padding-top:20px;" class="text-right">Praktek <?php echo @$item->Nama_Supplier ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px;" class="text-right">Apotek Adi Husada</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 11,5px; padding-top:20px;" class="text-right">Jl P Bawean No.2 Br Jagasatru, Kediri Tabanan Bali</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px;" class="text-right">Phone : 087863629488</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px;" class="text-right">Email : Ascornermedical@gmail.com</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px; padding-bottom:15px;" class="text-right">Instagram : praktekyayasana</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px; padding-top:65px;">Tabanan : ...........................................</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size: 12px;">Dokter Pemeriksa</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
