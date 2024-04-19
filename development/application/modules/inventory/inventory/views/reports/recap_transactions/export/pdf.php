<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <?php /*?><link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/><?php */?>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.css" media="print" rel="stylesheet"/>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
            	<?php if( $report_logo = $this->config->item( "report_logo" ) ):  ?>
                <div class="col-xs-2">
                	<img src="<?php echo base_url( "resource/images/logos" )."/".$report_logo ?> " />
                </div>
                <div class="col-xs-6">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php endif ?>
            </div>
        	<div class="row text-center" style="margin:0 !important;">
            	<h3><?php echo sprintf("%s %s %s", lang('reports:recap_transaction_heading'), lang("reports:section_label"), $section->SectionName ); ?></h3>
            	<h5><?php echo lang('reports:periode_label'); ?> <?php echo $post_data->date_start ?>  <?php echo lang('reports:till_label'); ?> <?php echo $post_data->date_end ?></h5>
            </div>
            
			<?php $i = 1; $total = 0; if(!empty($collection)) : foreach ($collection as $key => $value) :   ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo sprintf("%s : %s", lang("reports:cooperation_type_label"), $key );?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="" width="100%" style="border:1px solid black; font-size:11px">
                                <thead>
                                    <tr style="border:1px solid black;">
                                        <th align="center" style="padding:8px;"><?php echo lang('reports:no_label'); ?></th>
                                        <th style="padding:8px;"><?php echo lang('reports:company_name_label'); ?></th>
                                        <th align="center" style="padding:8px;"><?php echo lang('reports:code_label'); ?></th>
                                        <th align="center" style="padding:8px;"><?php echo lang('reports:item_label'); ?></th>
                                        <th align="right" style="padding:8px;"><?php echo lang('reports:amount_label'); ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php $sub_total = 0; if(!empty($value)) : foreach ($value as $row) : if($i == 400) { break; } ?>
                                    <?php $sub_total = $sub_total + $row->Nilai; ?>
                                    <tr>
                                        <td align="center" width="6px"><?php echo $i++; ?></td>
                                        <td width="350px"><?php echo @$row->Nama_Customer ?></td>
                                        <td align="center" width="150px"><?php echo @$row->Kode_Barang ?></td>
                                        <td width="250px"><?php echo @$row->Nama_Barang ?></td>
                                        <td align="right" width="150px" style="padding:2px;"><?php echo number_format(@$row->Nilai, 0, ",", ".") ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4"><?php echo sprintf("%s %s", lang("reports:qty_patient_label"), @$key ) ?></td>
                                        <td><?php echo number_format(@$sub_total, 2, ",", ".") ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php $total = $total + $sub_total; ?>
			<?php endforeach; ?>
            	<br>
                <div class="row">
                	<div class="col-md-12">
                    	<table border="1" width="100%" cellpadding="2">
                        	<tr>
                            	<td align="right"><?php echo lang("reports:grand_total_label")?></td>
                            	<td align="right" width="150px"><?php echo number_format($total, 2, ",", ".")?></td>
                            </tr>
                        </table>
                    </div>
                </div>

			<?php else: ?>
	            <h1 class="text-center"><?php echo lang("reports:none_data_label"); ?></h1>
            <?php endif;?>

            <div class="row">
            	<div class="col-lg-12">
                	<div class="table-responsive">
                        <table class="table reports-table"  >
                            <tbody>
                                <tr>
                                    <td width="30%">&nbsp;</td>
                                    <td width="40%">&nbsp;</td>
                                    <td align="center" width="30%"></td>
                                </tr>
                                <tr>
                                    <td align="center"><?php echo lang( "reports:madeby_label" ) ?> ,</td>
                                    <td>&nbsp;</td>
                                    <td align="center"><?php echo lang( "reports:receiver_label" ) ?> ,</td>
                                </tr>
                                <tr>
                                    <td style="height: 50px;"></td>
                                    <td></td>
                                    <td style="height: 50px;"></td>
                                </tr>
                                <tr>
                                    <td align="center">(_____________________________________)</td>
                                    <td></td>
                                    <td align="center">(_____________________________________)</td>
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
