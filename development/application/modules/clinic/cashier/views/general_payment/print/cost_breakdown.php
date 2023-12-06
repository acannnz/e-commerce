<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <base href="<?php echo site_url() ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo 'Detail Rincian Biaya' . "-" . "$detail_reg->NoReg" ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet" />
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet" />

    <style type="text/css">
        body {
            font-family: Tahoma, Arial, sans-serif !important;
        }

        .tulisan {
            color: #000;
            font-style: normal;
            font-weight: 2000px;
        }

        .pad10 {
            padding: 6px;
            font-family: "arial-ce" !important;
        }

        .pad5 {
            padding: 5px 0px 5px 5px;
            font-size: 20px !important;
            font-family: "arial-ce" !important;
        }

        .border {
            /* border:0px solid #898989; */
            border: 1px solid #898989;
            border-style: solid;
            padding: 10px;
            font-size: 20px !important;
        }

        .w100 {
            width: 100px;
            font-family: "arial-ce" !important;
        }

        .w200 {
            width: 200px;
        }

        .w500 {
            width: 300px;
        }

        .bgdark {
            background-color: #e9e4e4;
            color: #000;
            font-family: "arial-ce" !important;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .table_header tr td {
            padding: 3px;
            font-family: "arial-ce" !important;
        }
    </style>
</head>

<body>
    <div class="row" style="margin:0 !important;">
        <div class="col-lg-12" style="margin:0 !important;">
            <div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <div class="col-lg-12">
                    <p>
                        <span style="font-size:20px"><strong><?php echo $this->config->item("company_name") ?></strong></span><br>
                        <?php echo sprintf("%s, %s, %s %s", $this->config->item("company_address"), $this->config->item("company_city"), $this->config->item("company_country"), ($this->config->item("company_zip_code") ? " (" . $this->config->item("company_zip_code") . ")" : "")) ?><br>
                        Telp <?php echo ($this->config->item("company_phone") ? $this->config->item("company_phone") : "n/a") ?>
                    </p>

                </div>
            </div>
            <div class="row">
                <h4 align="center"><u><b>DETAIL RINCIAN BIAYA</b></u></h4>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="" style="">
                        <table class="table reports-table table_header" style="font-size:13px!important">
                            <tr>
                                <td width="90px">No Reg/Tgl Reg</td>
                                <td>: <?php echo $detail_reg->NoReg . " / " . date('d M Y', strtotime($date_reg)) ?></td>
                                <td width="100px"></td>
                                <td>Tipe Pasien</td>
                                <td>: <?php echo $detail_patient->JenisPasien ?></td>
                            </tr>
                            <tr>
                                <td width="80px">NRM</td>
                                <td>: <?php echo $detail_patient->NRM ?></td>
                                <td width="100px"></td>
                                <td>Perusahaan</td>
                                <td colspan="2">:</td>
                            </tr>
                            <tr>
                                <td width="80px">Nama Pasien</td>
                                <td>: <?php echo $detail_patient->NamaPasien ?></td>
                                <td width="100px"></td>
                                <td>No.Kartu</td>
                                <td>: </td>
                            </tr>
                            <tr>
                                <td width="140px">Alamat</td>
                                <td>: <?php echo $detail_patient->Alamat ?></td>
                                <td width="100px"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>

                        <table class="border">
                            <thead>
                                <tr class="border">
                                    <td class="pad10 border bold center">No</td>
                                    <td class="pad10 border w100 bold center">No Bukti</td>
                                    <td class="pad10 border w100 bold center">Tanggal</td>
                                    <td class="pad10 border w200 bold center">Jenis</td>
                                    <td class="pad10 border bold center">Qty</td>
                                    <td class="pad10 border w100 bold center">Nilai</td>
                                    <td class="pad10 border w500 bold center">Harga (Qty x Nilai)</td>
                                    <td class="pad10 border w100 bold center">Disc %</td>
                                    <td class="pad10 border w200 bold center">Section</td>
                                    <td class="pad10 border w200 bold center">Dokter</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0;
                                if (!empty($detail_data)) : foreach ($detail_data as $key => $row) : ?>
                                        <tr class="border">
                                            <!-- <td></td> -->
                                            <td class="bgdark pad5 bold" colspan="10"><?php echo "Group Biaya " . " : " . $key  ?></td>
                                        </tr>
                                        <?php $grand_total = 0;
                                        foreach ($row as $k_row => $k_key) : ?>
                                            <tr class="border">
                                                <td></td>
                                                <td class="pad5 bold" colspan="9"><?php echo "Kategori Biaya" . " : " . $k_row ?></td>
                                            </tr>
                                            <?php $sub_total = 0;
                                            $i = 1;
                                            foreach ($k_key as $h_row => $y_row) : ?>
                                                <tr>
                                                    <td class="center pad5"><?php echo $i++ ?></td>
                                                    <td class="pad5"><?php echo $y_row->NoBukti ?></td>
                                                    <td class="pad5"><?php echo date('d/M/Y', strtotime($y_row->Tanggal)) ?></td>
                                                    <td class="pad5"><?php echo $y_row->JenisBiaya ?></td>
                                                    <td class="pad5"><?php echo $y_row->Qty ?></td>
                                                    <td class="pad5 right"><?php echo number_format($y_row->Nilai, 0, ',', '.') ?> <?php echo ($y_row->BiayaResep > 0) ? '| ' . number_format($y_row->BiayaResep, 0, ',', '.') : '' ?></td>
                                                    <td class="pad5 center"><?php echo number_format($y_row->Nilai * $y_row->Qty, 0, ',', '.') ?> <?php echo ($y_row->BiayaResep > 0) ? '| ' . number_format($y_row->BiayaResep, 0, ',', '.') : '' ?></td>
                                                    <td class="pad5 center"><?php echo $y_row->Disc ?></td>
                                                    <td class="pad5"><?php echo $y_row->SectionName ?></td>
                                                    <td class="pad5"><?php echo $y_row->DokterName ?></td>
                                                </tr>
                                            <?php $sub_total = $sub_total + currency_ceil(($y_row->Nilai * $y_row->Qty - $y_row->Disc + $y_row->BiayaResep) * (1 - ($y_row->Disc / 100)));
                                            endforeach; ?>
                                            <tr class="border">
                                                <td class="right pad5 bold" colspan="8"><strong><?php echo "Sub Total Kategori Biaya ($k_row) :" ?></strong></td>
                                                <td class="right pad5 bold"><?php echo number_format($sub_total, 0, ',', '.') ?></td>
                                                <td></td>
                                            </tr>
                                        <?php $grand_total = $grand_total + $sub_total;
                                        endforeach; ?>
                                        <tr class="border">
                                            <td class="right pad5 bold" colspan="8"><strong><?php echo "Sub Total Group Biaya ($key) :" ?></strong></td>
                                            <td class="right pad5 bold"><?php echo number_format($grand_total, 0, ',', '.') ?></td>
                                            <td></td>
                                        </tr>
                                <?php $total = $total + $grand_total;
                                    endforeach;
                                endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="border">
                                    <td class="pad5 right bold bgdark" colspan="8"><?php echo "GRAND TOTAL :" ?></td>
                                    <td class="pad5 right bold bgdark"><?php echo number_format($total, 0, ',', '.') ?></td>
                                    <td class="bgdark"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>