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
			padding: 5px;
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
                <h3 align="center"><b><u><?php echo strtoupper( lang("heading:stock_opname") )?></u></b></h3>
            </div>

            <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                    <table  class="reports-table" style="font-size:12px">
                        <tr>
                            <td><?php echo lang("label:no_opname")?></td>
                            <td>: <?php echo $item->No_Bukti ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang("label:date")?></td>
                            <td>: <?php echo date("d-M-Y", strtotime( substr($item->Tgl_Opname, 0, 10) )); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang("label:section")?></td>
                            <td>: <?php echo $section->SectionName ?></td>
                        </tr>
						<tr>
                            <td><?php echo lang("label:item_type_group")?></td>
                            <td>: <?php echo $item->KelompokJenis ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12" style="padding:0;font-size:12px;">
                    <table id="detail_table" class="table" style="font-size:12px" >
                        <tr>
                            <th align="center"><?php echo lang("label:no")?></th>
                            <th width="40%" align="center"><?php echo lang("label:item_name")?></th>
                            <th><?php echo lang("label:unit")?></th>
							<th align="center"><?php echo lang("label:qty_system")?></th>
							<th align="center"><?php echo lang("label:qty_physical")?></th>
							<?php if($item->Posted == 1):?>
							<th align="center"><?php echo lang("label:gap")?></th>
							<?php endif;?>
                        </tr>
						<?php $total_system = $total_physical = $total_gap = 0; ?>
                       	<?php $i=1; foreach($collection as $row): ?>
                        <tr>
                            <td align="center"><?php echo $i++; ?></td>
                            <td><?php echo sprintf('%s - %s', $row->Kode_Barang, $row->Nama_Barang) ?></td>
                            <td><?php echo $row->Kode_Satuan ?></td>
							<td align="right"><?php echo $row->Stock_Akhir ?></td>
							<td align="right"><?php echo ($item->Posted == 1) ? $row->Qty_Opname : ($row->Qty_Opname > 0) ? $row->Qty_Opname : '' ?></td>
							<?php if($item->Posted == 1):?>
							<td align="right"><?php echo $row->Selisih ?></td>
							<?php endif;?>
                        </tr>
						<?php
							$total_system +=  $row->Stock_Akhir;
							$total_physical +=  $row->Qty_Opname;
							if($item->Posted == 1)
								$total_gap +=  $row->Selisih;
						?>						
                       	<?php  endforeach; ?>
                        <tr>
                            <td colspan="2" align="right"><?php echo lang("label:total") ?></td>
							<td></td>
                            <td align="right"><?php echo $total_system ?></td>
							<td align="right"><?php echo $total_physical ?></td>
							<?php if($item->Posted == 1):?>
							<td align="right"><?php echo $total_gap ?></td>
							<?php endif;?>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12" style="margin-top:10px;">
                    <table class="table reports-table">
                    	<tr><td>&nbsp;</td></tr>
                        <tr>
                            <td align="center" style="font-size:10px"></td>
                            <td width="20%"></td>
                            <td align="center"> <?php echo sprintf('%s %s', lang("global:created_by"), $user->Nama_Asli) ?>, </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td align="center"></td>
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
