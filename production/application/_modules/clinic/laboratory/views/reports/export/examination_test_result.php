<html>

<head>
    <meta charset="utf-8" />
    <base href="<?php echo site_url() ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet" />
    <link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet" />
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet" />
</head>
<style>
    .patient-info {
        border: 1px solid !important;
    }
</style>

<body>
    <div class="row" style="margin:0 !important;">
        <div class="col-xs-12" style="margin:0 !important;">
            <div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <?php if ($report_logo = $this->config->item("report_logo")) :  ?>
                    <div class="col-xs-2">
                        <img src="<?php echo base_url("resource/images/logos") . "/" . $report_logo ?> " />
                    </div>
                    <div class="col-xs-6">
                        <h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item("company_name") ?></h3>
                        <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item("company_address"), $this->config->item("company_city"), $this->config->item("company_country"), ($this->config->item("company_zip_code") ? " (" . $this->config->item("company_zip_code") . ")" : "")) ?></p>
                        <p style="font-size:11px;"><strong><?= 'Telp' ?>:</strong> <span><?php echo ($this->config->item("company_phone") ? $this->config->item("company_phone") : "n/a") ?></span></p>
                    </div>
                <?php else : ?>
                    <div class="col-lg-12">
                        <h3 style="margin:0 !important;"><?php echo $this->config->item("company_name") ?></h3>
                        <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s %s", $this->config->item("company_address"), $this->config->item("company_city"), $this->config->item("company_country"), ($this->config->item("company_zip_code") ? " (" . $this->config->item("company_zip_code") . ")" : "")) ?></p>
                        <p style="font-size:11px;"><strong><?= 'Telp' ?>:</strong> <span><?php echo ($this->config->item("company_phone") ? $this->config->item("company_phone") : "n/a") ?></span></p>
                    </div>
                <?php endif ?>
            </div>
            <div class="row text-center" style="margin:0 !important;">
                <h3><?= "Hasil Pemeriksaan Laboratorium" ?></h3>
            </div>
            <br />
            <br />
            <div class="row patient-info" style="padding-top: 10px !important">
                <div class="col-xs-5">
                    <table class="table">
                        <tr>
                            <td style="padding:10px !important"><?= 'Nama Pasien' ?></td>
                            <td style="padding:10px !important">: <?= $item->PasienNama ?></td>
                        </tr>
                        <tr>
                            <td style="padding:10px !important"><?= 'Umur/Kelamin' ?></td>
                            <td style="padding:10px !important">: <?= $item->Pasien_UmurTh ?> Thn / <?= $item->JenisKelamin == 'F' ? 'Perempuan' : 'Laki-laki' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:10px !important"><?= 'Bahan Pemeriksaan' ?></td>
                            <td style="padding:10px !important">: <?= @$item->BahanPemeriksaan ?> </td>
                        </tr>
                    </table>
                </div>
                <div class="col-xs-5">
                    <table class="table">
                        <tr>
                            <td style="padding:10px !important"><?= 'Tgl. Pengambilan' ?></td>
                            <td style="padding:10px !important">: <?= date('Y') ?></td>
                        </tr>
                        <tr>
                            <td style="padding:10px !important"><?= 'Dokter' ?></td>
                            <td style="padding:10px !important">: <?= $doctor->Nama_Supplier ?></td>
                        </tr>
                        <tr>
                            <td style="padding:10px !important"><?= 'Alamat' ?></td>
                            <td style="padding:10px !important">: <?= config_item('company_name') ?> </td>
                        </tr>
                    </table>
                </div>
            </div>

            <br />
            <br />

            <div class="row">
                <div class="">
                    <table class="table" style="border:1px solid black; font-size:10px">
                        <thead>
                            <tr style="border:1px solid black;">
                                <th align="center" style="border:1px solid black; padding:8px;"><?= 'Pemeriksaan' ?></th>
                                <th align="center" style="border:1px solid black; padding:8px;"><?= 'Hasil' ?></th>
                                <th align="center" style="border:1px solid black; padding:8px;"><?= 'Satuan' ?></th>
                                <th align="center" style="border:1px solid black; padding:8px;"><?= 'Nilai Rujukan' ?></th>
                                >
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            if (!empty($collection)) : foreach ($collection as $row) :   ?>
                                    <tr style="border:1px dotted black; ">
                                        <td align="center" style="border:1px solid black; padding:2px;"><?= @$row->NamaTest ?></td>
                                        <td align="center" style="border:1px solid black; padding:2px;"><?= @$row->Nilai ?></td>
                                        <td align="center" style="border:1px solid black; padding:2px;"><?= @$row->Satuan ?></td>
                                        <td width="300px" style="border:1px solid black; padding:2px;"><?= @$row->Keterangan ?></td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr style="border:1px dotted black;">
                                    <td colspan="8" align="center" style="border:1px solid black; padding:2px;"><?= 'Tidak ada data ditemukan' ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <br />
            <br />

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table reports-table">
                            <tbody>
                                <tr>
                                    <td width="30%">&nbsp;</td>
                                    <td width="40%">&nbsp;</td>
                                    <td align="center" width="30%"></td>
                                </tr>
                                <tr>
                                    <td align="center">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align="center"><?= 'Pemeriksa' ?> ,</td>
                                </tr>
                                <tr>
                                    <td style="height: 50px;"></td>
                                    <td></td>
                                    <td style="height: 50px;"></td>
                                </tr>
                                <tr>
                                    <td align="center">&nbsp;</td>
                                    <td></td>
                                    <td align="center">(<?= @$analysis->Nama_Supplier ?>)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>