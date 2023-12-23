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
                        <td align="center" colspan="" style="font-size: 24px; padding-left:10px; padding-right:10px; padding-top:2px;">KLINIK BALI SEHAT</td>
                    </tr>
                    <tr class="border-body">
                        <td align="center" colspan="" style="font-size: 12px;  padding-left:10px; padding-top:12px; background-color:#a0a0a0; color:White;">Culik, Abang, Karangasem Regency, Bali 80852
                            Telp 0812-3666-9566</td>
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
    <h4 class="text-center"><strong><?php echo @$item->NoBukti ?></strong></h4>
    <div class="row" style="border-bottom:1px solid #000; margin-top:50px !important;">
        <div class="col-xs-12" style="margin-top:50px;">
            <table class="table2" width="100%" style="font-size:14px; margin-bottom:0px;">
                <tr>
                    <td style="padding-bottom: 10px;">Nama</td>
                    <td width="30%">:</td>
                    <td><?php echo @$item->NamaPasien ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px;">No Identitas</td>
                    <td width="30%">:</td>
                    <td><?php echo @$item->NoIdentitas ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px;">Jenis Kelamin</td>
                    <td width="30%">:</td>
                    <?php $JenisKelamin = [
                        ['id' => 'M', 'desc' => 'Laki-Laki'],
                        ['id' => 'F', 'desc' => 'Perempuan']
                    ] ?>
                    <td><?php if (!empty($JenisKelamin)) : foreach ($JenisKelamin as $dt) : ?>
                                <?php echo (@$item->JenisKelamin == @$dt['id']) ? @$dt['desc'] : null ?>
                        <?php endforeach;
                        endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px;">Umur</td>
                    <td width="30%">:</td>
                    <td><?php echo @$item->UmurThn ?> Tahun</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px;">Alamat</td>
                    <td width="30%">:</td>
                    <td><?php echo @$item->Alamat ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 12px;">Tanggal</td>
                    <td width="3%">:</td>
                    <td><?php echo date("d F Y", strtotime(substr(@$item->TglReg, 0, 10))) ?></td>
                    <td style="padding-left:50px;">Pukul</td>
                    <td width="3%">:</td>
                    <td><?php echo date("H:i a", strtotime(@$item->JamReg, 10)) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top:50px;">
        <div class="col-xs-12" style="padding:0;">
            <table class="table table_detail" width="100%" style="font-size:14px;">
                <thead>
                    <tr>
                        <th width="40%">Pemeriksaan</th>
                        <th width="30%">Hasil</th>
                        <th width="30%">Nilai Rujukan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SARS-Cov-2Antigen (Covid 19) Rapid Tes</td>
                        <td align="center" style="">Negatif </td>
                        <td align="center">Negatif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <ul type="disc" style="margin-top:40px;">
        <li style="padding-bottom: 1px; font-size:16px;">Catatan :</li>
        <li style="padding-bottom: 10px; padding-left:50px;">Hasil negative tidak menyingkirkan kemungkinan terinfeksi SARS-CoV-2 </li>
        <li style="padding-bottom: 10px; padding-left:50px;">sehingga masih beresiko menularkan ke orang lain, disarankan test ulang</li>
        <li style="padding-bottom: 10px; padding-left:50px;">atau test konfirmasi dengan NAAT (nucleic acid amplification test), bila</li>
        <li style="padding-bottom: 10px; padding-left:50px;">probabilitas pretestnya relative tinggi, terutama bila pasien bergejala</li>
        <li style="padding-bottom: 10px; padding-left:50px;">atau diketahui memiliki kontak dengan orang yang terkonfirmasi Covid 19</li>
    </ul>
    <div class="row">
        <div class="col-sm-12" style="margin-top:30px;">
            <table class="table reports-table" align="right">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td rowspan="6" align="right"><img class="barcode" style="width: 26%; padding-top:30px; padding-left:150px;"  src="<?= base_url() . '../../themes/intuitive/assets/img/swab.png' ?>" alt=""></td>
                    <td align="center" style="font-size:14px; width: 225px;">Karangasem, <?= date('d-m-Y', strtotime($item->TglReg)); ?></td>
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