<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
    #pos-container {
        width: 100% !important;
        font-family: Tahoma, Arial, sans-serif;
    }

    td {
        padding: 6px 0px !important;
    }

    .border {
        border: 1px solid #000;
    }

    h4 {
        font-weight: normal;
    }

    .container {
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;
        flex-direction: column;
    }

    .rapi-table {
        border-collapse: collapse;
        width: 100%;
    }

    .rapi-table td {

        padding: 8px;
    }

    .rapi-table .align-right {
        text-align: right;
    }
</style>
<div id="pos-container" class="row" style="margin:0 !important;">
    <div class="col-lg-4" style="margin:0 !important;">
        <div class="row" style="margin-top:26px">
            <div class="col-md-3" style="padding:0;">
                <div class="table-responsive">
                    <div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                        <div style="overflow: auto;">
                            <img src=<?php echo base_url("resource/images/logos/pip.png") ?> width="100px" height="55px" style="float: left; margin-right: 10px;" />
                            <p>
                                <span style="font-size: 20px;"><strong><?php echo $this->config->item("company_name") ?></strong></span><br>
                                <?php echo sprintf("%s, %s, %s %s", $this->config->item("company_address"), $this->config->item("company_city"), $this->config->item("company_country"), ($this->config->item("company_zip_code") ? " (" . $this->config->item("company_zip_code") . ")" : "")) ?><br>
                                Telp <?php echo ($this->config->item("company_phone") ? $this->config->item("company_phone") : "n/a") ?>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="border:1px solid #000;border-style: dotted;">
                        <div class="col-sm-12" style="padding:0;">
                            <div class="">
                                <h4 class="table reports-table table_header" align="center">KWITANSI/RECEIPT</h4>
                                <table class="table reports-table table_header" style="font-size: 17px;">
                                    <tr>
                                        <td width="210px">No Transaksi / <i>Transaction No</i></td>
                                        <td>: &nbsp; <?= $item->NoBukti ?></td>
                                        <td width="100px"></td>
                                        <td>&nbsp;</td>
                                        <td width="255px">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="80px">Tanggal / <i>Date</i></td>
                                        <td>: &nbsp; <?= DateTime::createFromFormat('Y-m-d H:i:s.u', $item->Jam)->format('d M Y, H.i') ?></td>
                                        <td width="100px"></td>
                                        <td width="210px">&nbsp;</td>
                                        <td width="190px">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="80px">Nama Pasien / <i>Name Patient</i> </td>
                                        <td width="240px">: &nbsp; <?= $item->Keterangan ?></td>
                                        <td width="100px"></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="80px">No Telp / <i>Phone Number</i></i></td>
                                        <td width="180px">: &nbsp; <?= $item->Phone ?></td>
                                        <td width="100px"></td>
                                        <td width="140px">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <table width="100%" class="border">
                        <thead>
                            <tr class="">
                                <td width="50px" class="pad10 bold center" style="font-size: 11px;">No.</td>
                                <td width="100px" class="pad10  w100 bold center" style="font-size: 11px;">Kode Item</td>
                                <td width="200px" class="pad10  w100 bold center" style="font-size: 11px;">Nama Item</td>
                                <td class="pad10  w200 bold center" style="font-size: 11px;">Jml</td>
                                <td class="pad10  bold center" style="font-size: 11px;">Satuan</td>
                                <td class="pad10  w100 bold center" style="font-size: 11px;">Harga</td>
                                <td class="pad10  w200 bold center" style="font-size: 11px;">Total</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            $totalQty = 0;
                            $totalAmount = 0;
                            $total_disc = 0;
                            foreach ($collection as $row) :
                                $totalQty += $row->Qty; ?>
                                <tr>
                                    <td class="center pad5" style="font-size: 11px;"><?php echo $i++ ?></td>
                                    <td class="center pad5" style="font-size: 11px;"><?= $row->Barang_ID ?></td>
                                    <td class="pad5" style="font-size: 11px;"><?= $row->Nama_Barang ?></td>
                                    <td class="pad5" style="font-size: 11px;"><?= $row->Qty ?></td>
                                    <td class="pad5" style="font-size: 11px;"><?= $row->Satuan ?></td>
                                    <td class="pad5" style="font-size: 11px;"><?= number_format($row->Harga) ?></td>
                                    <td class="pad5" style="font-size: 11px;"><?= number_format($row->Harga * $row->Qty) ?></td>
                                </tr>
                            <?php $total_disc += $row->TotDisc;
                                $totalAmount += $row->Harga * $row->Qty + @$item->AddCharge;
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <table width="100%" class="rapi-table" style="font-size: 11px">
                        <?php foreach ($type_payment_used as $key => $val) :
                            if ($val > 0) : ?>
                                <tr>
                                    <td>Keterangan :</td>
                                    <?php $key = strtoupper($key); ?>
                                    <td class="align-right"><b><?= $key ?> =</b></td>
                                    <td class="align-right">Rp.<?= number_format($val + @$total_disc) ?></td>
                                </tr>
                        <?php
                            endif;
                        endforeach; ?>
                        <tr>
                            <td></td>
                            <td class="align-right"><b>DISKON =</b></td>
                            <td class="align-right">Rp.<?= number_format(@$total_disc) ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-right"><b>GRAND TOTAL =</b></td>
                            <td class="align-right">Rp.<?= number_format(@$totalAmount - @$total_disc + @$type_payment_used['Card Charge']) ?></td>
                        </tr>
                    </table>
                    <div class="col-lg-12" style="border-top:1px solid black;border-bottom:1px solid black;border-style: dotted;">
                        <div class="col-lg-12 far pad5" style="font-size: 11px;"><strong><?php echo "Terbilang : " ?></strong><?php echo ucwords($detail_money_to_text) . " Rupiah </i>" ?></div>
                        <div class="col-lg-12 far pad5" style="font-size: 11px;"><strong><?php echo "Counted : " ?></strong><?php echo '<i>' . ucwords($detail_money_to_text_english) . ' Rupiah </i>'; ?></div>
                    </div>
                    <br>
                    <table width="100%" style="font-size: 11px;">
                        <tr>
                            <td>Hormat Kami </td>
                            <td align="right">Penerima</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo $user->Nama_Asli ?></td>
                            <td align="right"><?php echo $item->Keterangan ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>