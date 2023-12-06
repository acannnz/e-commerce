<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <?php /*?><link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/><?php */?>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif!important;
        }
        table {
            font-size: 12.5px;
            font-family: "Times New Roman", Times, serif;
        }
        .detail_table {
            border-collapse: collapse;
            width: 100%;
            border: 0.3px solid rgb(170, 170, 170);
        }

        .detail_header {
            width: 100%;
            font-size: 12px;
            margin-left: 12px;
        }

        .detail_header td, .detail_header th {
            padding: 2px;
        }
        .detail_table td, .detail_table th {
            border: 0.3px solid rgb(170, 170, 170);
            padding: 10px;
        }

        .detail_table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #f7f4f4;
            color: #333;
            border: 0.3px solid rgb(170, 170, 170);
        }

        table tfoot th{
            background-color:none!important;
        }  
 </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
            <?php echo $this->load->view( "reports/header/header_report", true ) ?>	
        	<div class="row text-center">
            	<h5><strong><span style="font-size: 16px;"><?= @$title ?></span></strong><br><?= lang('reports:periode_label'); ?> <?= date('d-m-Y',strtotime($params->date)) ?></h5>
            </div>
            <h5><strong>Section : <?php echo $section->SectionName ?></strong></h5><br>
            <?php $grandtotal = 0; ?>
			<?php $i = 1 ; if(!empty($collection)) : foreach ($collection as $key => $value) :   ?>
                <div class="row" style="margin-top: 1px;">
                    <div class="col-sm-12">
                        <span style="font-size: 12px;"><strong><?= $key; ?></strong></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="table reports-table detail_table" width="100%">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 6%;"><?php echo lang('reports:no_label'); ?></th>
                                        <th style="width: 10%;"><?php echo 'Kode Barang'; ?></th>
                                        <th style="width: 30%;"><?php echo 'Nama Barang'; ?></th>
                                        <th style="width: 10%;"><?php echo 'Satuan'; ?></th>
                                        <th style="width: 10%;"><?php echo 'Stok'; ?></th>
                                        <th style="width: 10%;"><?php echo 'Harga@'; ?></th>
                                        <th style="width: 10%;"><?php echo 'Total Harga'; ?></th>
                                        <th><?php echo 'Penerimaan Terakhir'; ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php 
                                        $jumlah_harga = 0; 
                                    ?>
                                    <?php if(!empty($value)) { foreach ($value as $row) { if($i == 500) { break; } ?>
                                    <?php 
                                        $jumlah_harga =  $row->JumlahStok * $row->Harga_Beli;
                                        $grandtotal +=  $row->JumlahStok * $row->Harga_Beli;
                                    ?>
                                    
                                    <tr>
                                        <td align="center"><?php echo $i++; ?></td>
                                        <td><?php echo @$row->Kode_Barang ?></td>
                                        <td><?php echo @$row->Nama_Barang ?></td>
                                        <td><?php echo @$row->Nama_Satuan ?></td>
                                        <td align="right"><?php echo @$row->JumlahStok ?></td>
                                        <td align="right"><?php echo number_format(@$row->Harga_Beli, 2) ?></td>
                                        <td align="right"><?php echo number_format($jumlah_harga, 2) ?></td>
                                        <td><?php echo date('d M Y', strtotime(@$row->Tgl_Penerimaan_Terakhir)) ?></td>
                                    </tr>
                                    <?php } } else { ?>
                                    <tr style="border:1px dotted black;">
                                        <td colspan="7" align="center" style="border:1px solid black; padding:2px;"><?php echo lang("reports:none_data_label"); ?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			<?php endforeach; else: ?>
	            <h1><?php echo lang("reports:none_data_label"); ?></h1>
            <?php endif;?>
            <h5 align="right"><strong>Grandtotal : <?= number_format($grandtotal, 2) ?></strong></h5>
            <br>
            <div class="row">
            	<div class="col-lg-12">
                	<div class="table-responsive">
                        <table class="table reports-table"  >
                            <tbody>
                                <tr>
                                    <td width="40%">&nbsp;</td>
                                    <td width="20%">&nbsp;</td>
                                    <td align="center" width="40%"></td>
                                </tr>
                                <tr>
                                    <td align="center"><br><?php echo lang( "reports:madeby_label" ) ?> , 
                                   <br><br><br><br><br><br>( <?= @$user->Nama_Asli ?>)
                                
                                    </td>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="<?php echo base_url( "resource/images/ttd/ttd_apoteker.jpg" ) ?> " style="height: 130px;"/></td>

                                </tr>
                                <tr>
                                    <td align="center"></td>
                                    <td>&nbsp;</td>
                                    <td align="center"></td>

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
