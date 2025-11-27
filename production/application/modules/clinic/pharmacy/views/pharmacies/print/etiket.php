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
                    <table width="100%"  style="font-size:10px !important">
                        <tbody>	           
                            <tr>              
                                <td colspan="5" style="padding:1px;border-bottom:1px solid #000000;" align="center">
                                    <span style="font-size:13px"><strong><?= config_item('company_name') ?></strong></span><br>
                                    <p style="font-size:10px; margin:0 !important;">
                                        <?= config_item('company_address')?><br>
                                        <?= config_item('company_phone')?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:10px!important;" width="20%">No</td>
                                <td align="left" style="padding:2px;font-size:10px!important;">: <?= @$item->NoEtiket ?></td>
                                <td align="right" style="padding:2px;font-size:10px!important;">Tgl</td>
                                <td align="left" style="padding:2px;font-size:10px!important;">: <?= date('d-m-Y', strtotime(@$item->Tanggal)) ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding:2px;font-size:10px!important;">Nama</td>
                                <td align="left" style="padding:2px;font-size:10px!important;" colspan="4">: <?= ucwords(strtolower(!empty(@$item->NamaPasien) ? @$item->NamaPasien : @$item->Keterangan)) ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:10px!important;">RM</td>
                                <td align="left" style="padding:2px;font-size:10px!important;" colspan="4">: <?= @$item->NRM ?></td>
                            </tr>
                            <tr>              
                                <td align="left" style="padding:2px;font-size:10px!important;">Obat</td>
                                <td align="left" style="padding:2px;font-size:10px!important;" colspan="4">: <?= ucwords(strtolower(@$item->Nama_Barang)) ?></td>
                            </tr>
        

                            <tr>
                                <td align="left" colspan="5" style="padding:5px 2px;font-size:10px!important;border-top:1px dashed #000000;">
                                    <p>- <?= @$item->Dosis ?></p>
                                    <p>- <?= @$item->Dosis2 ?></p>
                                </td>
                            </tr>

                            <tr>
                                <td align="left" colspan="5" style="padding:5px 2px;font-size:10px!important;border-top:1px dashed #000000;">
                                    <p><i><?= "ED : " . date('d-m-Y', strtotime(@$item->TglED)) ?></i></p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>