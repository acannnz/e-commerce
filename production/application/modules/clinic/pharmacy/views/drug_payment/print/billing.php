<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
    #pos-container {
        width: 80mm !important;
    }
    td {
        padding: 6px 0px !important;
    }
</style>
<div id="pos-container" class="row" style="margin:0 !important;">
    <div class="col-lg-4" style="margin:0 !important;">
        <div class="row" style="margin-top:26px">
            <div class="col-md-3" style="padding:0;">
                <div class="table-responsive">
                    <table width="100%"  style="font-size:11px !important">
                        <tbody>	           
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>              
                                <td colspan="5" style="padding:1px;border-bottom:1px solid #000000;" align="center">
                                    <span style="font-size:15px"><strong><?= config_item('company_name') ?></strong></span><br>
                                    <p style="font-size:11px; margin:0 !important;">
                                        <?= config_item('company_address')?><br>
                                        <?= config_item('company_phone')?> 
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="1">Kasir</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="4"><?= $this->user_auth->Nama_Singkat ?></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="1">Waktu</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="4"><?= DateTime::createFromFormat('Y-m-d H:i:s.u', $item->Jam)->format('d M Y, H.i') ?></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="1">No. Transaksi</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="4"><?= $item->NoBukti ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="2">Dokter</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="3"><?= $item->Nama_Supplier ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;" colspan="2">Pasien</td>
                                <td align="right" style="padding:2px;font-size:11px!important;" colspan="3"><?= $item->Keterangan ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:11px!important;border-bottom:1px dashed #000000;" colspan="2">Peruntukan</td>
                                <td align="right" style="padding:2px;font-size:11px!important;border-bottom:1px dashed #000000;" colspan="3"><?= @$item->Peruntukan ?></td>
                            </tr>

                            <?php foreach($collection as $row): ?>
                                <?php 
                                    if ( $row->Nama_Barang != $row->NamaResepObat)
                                    {
                                        continue;
                                    }
                                    
                                    $left = sprintf("%s x %s -%s%%", number_format($sub_total[$row->NamaResepObat] / $row->Qty), $row->Qty, (float)@$row->Disc);
                                    $right = number_format($sub_total[$row->NamaResepObat]);
                                ?>
                                <tr>
                                    <td colspan="5" style="padding:2px 2px;font-size:11px!important;"><b><?= $row->Nama_Barang ?></b></td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="3" style="padding:2px 2px;font-size:11px!important;"><?= $left ?></td>
                                    <td align="right" colspan="2" style="padding:2px 2px;font-size:11px!important;"><?= $right ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if(@$item->BiayaAdministrasi > 0): ?>
                            <tr>
                                <td align="left" colspan="2" style="padding:5px 2px;font-size:11px!important;"><?= "Administrasi" ?></td>
                                <td align="right" colspan="3" style="padding:5px 2px;font-size:11px!important;"><?= number_format(@$item->BiayaAdministrasi) ?></td>
                            </tr>
                            <?php endif; ?>

                            <tr>
                                <td align="left" colspan="2" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;">Total</td>
                                <td align="right" colspan="3" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;"><?= number_format($grand_total) ?></td>
                            </tr>

                            <?php foreach($type_payment_used as $key => $val):
                                if ( $val > 0 ): ?>
                                    <tr>
                                        <td align="left" colspan="2" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;"><?= $key ?></td>
                                        <td align="right" colspan="3" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;"><?= number_format($val) ?></td>
                                    </tr>
                            <?php 
                                endif;
                            endforeach; ?>
                            <tr>
                                <td align="center" colspan="5" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;">
                                    <p>### LUNAS ###</p>
                                </td>
                            </tr>

                            <tr>
                                <td align="center" colspan="5" style="padding:5px 2px;font-size:11px!important;border-top:1px dashed #000000;">
                                    <p><?= "Semoga lekas sembuh" ?></p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>