<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
    <style>
		#detail_table {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}
		
		#detail_table td, #detail_table th {
			border: 1px solid #ddd;
			padding: 8px;
		}
		
		#detail_table th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #CCC;
			color: #333;
		}
	</style>
</head>
<body>
    <div class="row" style="margin:0 !important; font-size:12px">
    	<div class="col-lg-12" style="margin-left:50px !important;margin-right:30px !important;">
        	
        	<div class="row" style="margin:0 0 40px 0; padding-top:30px !important;">
                <h3 align="center"><b><u><?php echo strtoupper( lang("facturs:factur_label") )?></u></b></h3>
            </div>

            <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                    <table  class="table reports-table" style="font-size:12px">
                        <tr>
                            <td width="60%"><?php echo lang("facturs:to_label")?>:</td>
                            <td ><?php echo lang("facturs:number_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td ><?php echo $item->No_Faktur ?></td>
                        </tr>
                        <tr>
                            <td ><b><u><?php echo $item->Nama_Customer ?></u></b></td>
                            <td ><?php echo lang("facturs:date_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td ><?php echo date("d-M-Y", strtotime( substr($item->Tgl_Faktur, 0, 10) )); ?></td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td ><?php echo lang("facturs:currency_label")?></td>
                            <td align="center" width="3%">:</td>
                            <td><?php echo $item->Currency_Code ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                    <table id="detail_table" class="table" >
                        <tr>
                            <th align="center"><?php echo lang("facturs:no_label")?></th>
                            <th width="70%" align="center"><?php echo lang("facturs:transaction_description_label")?></th>
                            <th width="25%" align="center"><?php echo lang("facturs:amount_label")?></th>
                        </tr>
                       	<?php $no=1; for($i = 0; $i <= 10; $i++): if(@$collection[$i]->Pos == 'D'){continue;}?>
                        <tr>
                            <td align="center"><?php echo !empty($collection[$i]) ? $no++ : "&nbsp;" ?></td>
                            <td><?php echo @$collection[$i]->Keterangan ?></td>
                            <td align="right"><?php echo !empty($collection[$i]) ? number_format(@$collection[$i]->Harga_Transaksi, 2, ".", ",") : NULL ?></td>
                        </tr>
                       	<?php endfor; ?>
                        <tr>
                            <td colspan="2" align="right"><?php echo lang("facturs:total_label") ?></td>
                            <td align="right"><?php echo number_format(@$item->Sisa, 2, '.', ',') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                	<?php echo lang("facturs:note_label")?> : <?php echo @$item->Keterangan ?>
				</div>
            	<div class="col-sm-12" style="padding:0;">
                	<?php echo lang("facturs:spelled_label")?> : <br/>
					<p style="text-indent:20px;font-size:14px;font-style:italic"><?php echo @$spelled ?></p>
				</div>
            </div>
            <div class="row">
            	<div class="col-sm-12" style="margin-top:10px;">
                    <table class="table reports-table">
                    	<tr><td>&nbsp;</td></tr>
                        <tr>
                            <td align="center" style="font-size:10px"><?php echo lang("global:created_by") ?>,</td>
                            <td width="40%"></td>
                            <td align="center"> <?php echo config_item("company_name") ?></td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td align="center"><?php echo $user->Nama_Asli ?></td>
                            <td></td>
                            <td align="center">__________________________________</td>
                        </tr>
                    </table>
				</div>
            </div>
        </div>
    </div>
</body>
</html>
