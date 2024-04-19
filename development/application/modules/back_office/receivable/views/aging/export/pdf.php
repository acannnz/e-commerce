<?php //print_r($post_data->collection); exit;?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>

    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
  <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
            	<?php if( config_item( "report_logo" ) ): ?>
                <div class="col-xs-2" >
                	<img src="<?php echo base_url( "resource/images/logos" )."/".config_item('report_logo') ?> " style="width:100%;height: auto;" />
                </div>
                <div class="col-xs-9">
                	<h3 style="color:#000000 !important; margin:0 !important;"><?php echo config_item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", config_item('company_address'), config_item( "company_city" ), config_item( "company_country" ), (config_item( "company_zip_code" ) ? " (".config_item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo config_item('company_phone') ?></span></p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0 !important;"><?php echo config_item( "company_name" ) ?></h3>
                    <p  style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", config_item('company_address'), config_item( "company_city" ), config_item( "company_country" ), (config_item( "company_zip_code" ) ? " (".config_item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong>Telepon <?php echo lang( "phone" ) ?>:</strong> <span><?php echo config_item('company_phone') ?></span></p>
                </div>
                <?php endif ?>
            </div>
			
        	<div class="row text-center" style="margin:0 !important;">
            	<h4><?php echo sprintf('%s %s', lang("aging:list_heading"), $post_data->date_start ) ?></h4>
            </div>
            
            <div class="col-sm-12" style="padding:0;">
                <div class="">
                    <table class="table reports-table" style="border:1px solid black; font-size:10px">
                        <thead>
                        	<tr style="border:1px solid black;">
								<th rowspan="2" style="border:1px solid black; padding:2px;"></th>
								<th rowspan="2" style="border:1px solid black; padding:2px;"><?php echo lang('aging:customer_label') ?></th>
								<th rowspan="2" style="border:1px solid black; padding:2px;"><?php echo lang('aging:amount_label') ?></th>
								<th rowspan="2" style="border:1px solid black; padding:2px;"><?php echo lang('aging:not_due_label') ?></th>
								<th colspan="6" class="text-center" style="border:1px solid black; padding:2px;"><?php echo lang('aging:due_label') ?></th>
							</tr>
							<tr style="border:1px solid black;">
								<th style="border:1px solid black; padding:2px;"><?php echo lang('aging:1_30_label') ?></th>
								<th style="border:1px solid black; padding:2px;"><?php echo lang('aging:31_60_label') ?></th>
								<th style="border:1px solid black; padding:2px;"><?php echo lang('aging:61_90_label') ?></th>
								<th style="border:1px solid black; padding:2px;"><?php echo lang('aging:91_180_label') ?></th>
								<th style="border:1px solid black; padding:2px;"><?php echo lang('aging:181_365_label') ?></th>
								<th style="border:1px solid black; padding:2px;"><?php echo lang('aging:1_year_label') ?></th>
							</tr>
                        </thead>
                        <tbody style="border:1px solid black;">	
                            <?php $i = 1; if(!empty( $post_data->collection )): foreach( $post_data->collection as $item ): ?>
                            <tr >
                                <td align="center" style="border:1px solid black; padding:2px;"><?php echo @$i ?></td>
                                <td align="left" style="border:1px solid black; padding:2px;"><?php echo $item->Nama_Customer ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->Jumlah != 0) ? number_format(@$item->Jumlah, 2, ".", ",") : 0; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->BelumTempo != 0) ? number_format(@$item->BelumTempo, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->antara30 != 0) ? number_format(@$item->antara30, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->antara60 != 0) ? number_format(@$item->antara60, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->antara90 != 0) ? number_format(@$item->antara90, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->antara180 != 0) ? number_format(@$item->antara180, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->antara365 != 0) ? number_format(@$item->antara365, 2, ".", ",") : NULL; ?></td>
                                <td align="right" style="border:1px solid black; padding:2px;"><?php echo ($item->diatas1th != 0) ? number_format(@$item->diatas1th, 2, ".", ",") : NULL; ?></td>
                            </tr>
                            <?php $i++; endforeach; endif;?>
                        </tbody>
						<tfoot>
							<tr style="border:1px solid black;">
								<th colspan="2" class="text-right" style="border:1px solid black; padding:2px;"><?php echo lang('aging:grand_total_label') ?></th>
								<th id="total" style="border:1px solid black; padding:2px;"><?php echo $post_data->total ?></th>
								<th id="not_due" style="border:1px solid black; padding:2px;"><?php echo $post_data->not_due ?></th>
								<th id="in_1_30" style="border:1px solid black; padding:2px;"><?php echo $post_data->in_1_30 ?></th>
								<th id="in_31_60" style="border:1px solid black; padding:2px;"><?php echo $post_data->in_31_60 ?></th>
								<th id="in_61_90" style="border:1px solid black; padding:2px;"><?php echo $post_data->in_61_90 ?></th>
								<th id="in_91_180" style="border:1px solid black; padding:2px;"><?php echo $post_data->in_91_180 ?></th>
								<th id="in_181_365" style="border:1px solid black; padding:2px;"><?php echo $post_data->in_181_365 ?></th>
								<th id="in_1_year" style="border:1px solid black; padding:2px;"><?php echo $post_data->in_1_year ?></th>
							</tr>
						</tfoot>
                    </table>
                </div>
            </div>
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
                                    <td align="center"><?php echo lang( "reports:madeby_label" ) ?>,</td>
                                    <td>&nbsp;</td>
                                    <td align="center"><?php echo lang( "reports:approvedby_label" ) ?>,</td>
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