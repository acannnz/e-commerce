<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <base href="<?= site_url() ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= @$file_name ?></title>
    <link href="<?= base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet" />
    <link href="<?= base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet" />
    <style>
        .table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            width: 100%;
        }

        .font {
            font-family: "Tams New Roman", serif;
            /* letter-spacing: 4px; */
        }

        .font2 {
            font-family: "Verdana", sans-serif;
            letter-spacing: 3px;
        }

        .table_head td,
        .table_head th {
            padding: 0px;
            font-size: 12px;
        }

        .table_detail td,
        .table_detail th {
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

        .table2 {
            font-family: sans-serif;
            font-size: 13px;
            padding-top: 12px;
            padding-bottom: 12px;
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

        .border-body {
            border-left: 1px solid black;
            border-right: 1px solid black;
            width: 100% !important;
            padding: 5px !important;
        }

        .border-top {
            border-top: 1px solid black;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

    </style>
</head>

<body>
    <div class="row">
        <div class="col-xs-8">
            <table class="table_head font">
                <tbody>
                    <tr class="border-body border-top">
                        <td align="center" colspan="" style="font-size: 24px; padding-left:10px; padding-right:10px; padding-top:2px;"><?php echo $this->config->item( "company_name" ) ?></td>
                    </tr>
                    <tr class="border-body">
                        <td align="center" colspan="" style="font-size: 12px;  padding-left:10px; padding-top:12px; background-color:#a0a0a0; color:White;">
                            <?php echo $this->config->item( "company_address" ) ?><br>
                            Telp  <?php echo ($this->config->item( "company_phone" )) ?>
                        </td>
                    </tr>
                    <tr class="border-body border-bottom">
                        <td align="center" colspan="" style="font-size: 12px; padding-left:10px; padding-bottom:12px; background-color:#a0a0a0; color:white;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-3">
            <img width="90%" src="<?= base_url("resource/images/logos/logo.png") ?>" />
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <table>
                <tr>
                    <td>Bangli, <?php echo date("d F Y", strtotime(substr(@$item->TglReg, 0, 10))) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top:10px !important;">
        <div class="col-xs-12" style="margin-top:35px;">
            <table class="table2" width="100%" style="font-size:14px; margin-bottom:0px;">
                <tr>
                    <td style="padding-bottom: 4px;">Yth. .....................................</td>
                    <td style="padding-left:300px;">No : &nbsp; <?php echo str_replace("-", "/", @$item->NoReg) ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 4px;">Di Tempat</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top:30px !important;">
        <div class="col-xs-12" style="margin-top:30px;">
            <table class="table2" width="100%" style="font-size:14px; margin-bottom:0px;">
                <tr>
                    <td style="padding-bottom: 5px;">Dengan hormat,</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;">Menghadapkan Pasien a/n :</td>
                </tr>
            </table>
            <table class="table2" width="100%" style="font-size:14px; margin-bottom:0px;">
                <tr>
                    <td style="padding-bottom: 5px;">Nama</td>
                    <td width="40%">: &nbsp;<?php echo @$item->NamaPasien ?></td>
                    <td style="padding-right: 280px;"></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;">Umur</td>
                    <td width="40%">: &nbsp;<?php echo @$item->UmurThn ?> Tahun</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;">Jenis Kelamin</td>
                    <?php $JenisKelamin = [
                        ['id' => 'M', 'desc' => 'Laki-Laki'],
                        ['id' => 'F', 'desc' => 'Perempuan']
                    ] ?>
                    <td width="40%">:&nbsp;<?php if (!empty($JenisKelamin)) : foreach ($JenisKelamin as $dt) : ?>
                        <?php echo (@$item->JenisKelamin == @$dt['id']) ? @$dt['desc'] : null ?>
                <?php endforeach;
                                            endif; ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;">Alamat</td>
                    <td width="40%">: &nbsp;<?php echo @$item->Alamat ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;">Diagnosis</td>
                    <td width="40%">: &nbsp;<?php echo @$item->Assessment ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;">Therapi</td>
                    <td width="40%">: &nbsp;<?php echo @$item->Plan ?></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>

    <ul type="disc">
        <li style="padding-bottom: 1px; font-size:14px;">Bahwa yang bersangkutan telah berobat ke praktek kami dan mohon
            ditindaklanjuti </li>
        <li style="padding-bottom: 1px; font-size:14px;">penanganan dan advice lebih lanjut</li>
    </ul>

    <div class="row">
        <div class="col-sm-12" style="margin-top:30px;">
            <table class="table reports-table" align="right">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td rowspan="6" align="right"><img class="barcode" style="width: 15%; padding-top:30px; padding-left:150px;"  src="<?= base_url("resource/images/barcode_surat_keterangan.jpeg") ?>" alt=""></td>
                    <td align="center" style="font-size:14px; width: 225px;">Tabanan, <?php $tgl = date('d-m-Y'); echo $tgl; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center"><img width="20%" src="<?= base_url("resource/images/logos/{$item->Ttd_Supplier}.png") ?>" /></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center"><u><?php echo $item->NamaDokter ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center">(SIP <?php echo $item->Sip ?>)</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>