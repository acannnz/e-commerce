
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name;?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/js/plugins/bootstrap/css") ?>/bootstrap.min.css" rel="stylesheet"/>
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
            	<h3><?php echo sprintf("%s %s", lang('reports:unit_performance_heading'), $section->SectionName ); ?></h3>
            	<h5><?php echo lang('reports:periode_label'); ?> <?php echo $post_data->date_start ?>  <?php echo lang('reports:till_label'); ?> <?php echo $post_data->date_end ?></h5>
            </div>
             <?php $grand_total = []; ?>
			<?php if(!empty($collection)) : foreach ($collection as $key => $groups) :   ?>
                 <?php $sub_total = 0;?>
				<div class="row">
                    <div class="col-sm-12">
                        <?php echo $key;?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="table reports-table"  style="border:1px solid black; font-size:10px">
                               <thead>
                           	 		<tr style="border:1px solid black;">
                                		<th style="padding:8px;border:1px solid black;"><?php echo lang('reports:group_label'); ?></th>
                                        <th align="left" style="padding:8px;border:1px solid black;"><?php echo lang('reports:service_category_label'); ?></th>
                                        <th align="left" style="padding:8px;border:1px solid black;"><?php echo lang('reports:service_name_label'); ?></th>
                                        <th align="right" style="padding:8px;border:1px solid black;"><?php echo lang('reports:qty_label'); ?></th>
                                        <th align="right" style="padding:8px;border:1px solid black;"><?php echo lang('reports:amount_label'); ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
									<?php $group_before = NULL; ?>
                                    <?php foreach ($groups as $group_k => $group) :  ?>
										<?php foreach ($group as $v) :  ?>
											<tr>
												<td style="padding:5px;"><?php echo @$v->GroupJasaName == $group_before ? '' : $v->GroupJasaName ?></td>
												<td style="padding:5px;"><?php echo @$v->KategoriJasaName ?></td>
												<td style="padding:5px;"><?php echo @$v->JasaName ?></td>
												<td align="right" style="padding:5px;"><?php echo @$v->QTy ?></td>
												<td align="right" width="150px" style="padding:5px;border:1px solid black;"><?php echo number_format($v->Nilai, 2, '.', ',') ?></td>
											</tr>
											<?php $group_before = $v->GroupJasaName; $sub_total = $sub_total + $v->Nilai; ?>
										<?php endforeach;  $grand_total[ $key ] = @$grand_total[ $key ] + $sub_total;  ?>
									<?php endforeach; ?>
								</tbody>
								<tfoot>	
									<tr style="border:1px solid black;">
										<th align="right" colspan="4" style="padding:5px;border:1px solid black;"><strong><?php echo lang('reports:total_label'). ' ' . $key ?></strong></th>
										<th align="right" style="padding:5px;border:1px solid black;"><strong><?php echo number_format($sub_total, 2, '.', ',') ?></strong></th>
									</tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
			 <div class="row">
            	<div class="col-lg-12">
                	<div class="table-responsive">
                        <table class="table reports-table" style="border:1px solid black; font-size:11px">
                            <thead>
								<?php $gap = 0; foreach ($grand_total as $key => $val) :  ?>
                                <tr>
                                    <th align="right" style="padding:5px;border:1px solid black;"><?php echo lang('reports:total_label'). ' ' . $key ?></th>
                                    <th align="right" width="150px" style="padding:5px;border:1px solid black;"><?php echo number_format($val, 2, '.', ',') ?></th>
									<?php $gap = abs( $gap - $val ); ?>
                                </tr>
								<?php endforeach;?>
                                <tr>
                                    <th align="right" style="padding:5px;border:1px solid black;"><?php echo lang('reports:balance_label') ?></th>
                                    <th align="right" width="150px" style="padding:5px;border:1px solid black;"><?php echo number_format($gap, 2, '.', ',') ?></th>
                                </tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<?php else: ?>
	            <h1><?php echo lang("reports:none_data_label"); ?></h1>
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
