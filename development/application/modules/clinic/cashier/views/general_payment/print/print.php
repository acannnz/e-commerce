<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/reset.css" rel="stylesheet"/>
    <link href="<?php echo base_url("themes/default/assets/css") ?>/print.css" rel="stylesheet"/>
    <style type="text/css">
    	.tulisan{
			color:#000;
			font-style:normal;
			font-weight:2000px;
		}
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
			<br>
            <br>
            	<?php if( $invoice_logo = $this->config->item( "invoice_logo" ) ): ?>
                <div class="col-lg-2">
                   <span style="font-size:16px;margin-top:20px"><?php echo $this->config->item( "company_name" ) ?></span>
                      <p style="font-size:10px; margin:0">
                          <?php echo sprintf("%s, %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ) ) ?>
                      </p>
                      <p style="font-size:10px;">
                          <strong><?php echo lang( "reports:phone_label" ) ?> :</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span>
                      </p>                                    
                      <span style="font-size:16px;"><?php echo lang('reports:billing_label'); ?></span>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                	<h3 style="margin:0;"><?php echo $this->config->item( "company_name" ) ?></h3>
                    <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s, %s, %s%s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?></p>
                    <p style="font-size:11px;"><strong><?php echo lang( "reports:telp_label" ) ?>:</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span></p>
                </div>
                <?php endif ?>
            </div>
            <br>
        	<div class="row text-center" style="margin:0 !important;">
            	<h3><?php echo $file_name; ?></h3>
            </div>
            <br>

            <div class="row">
            	<div class="col-sm-12" style="padding:0;">
                	<div class="">
                    	<table class="table reports-table"  style="border:1px solid black; font-size:10px">
                        	<tr><td>&nbsp;</td></tr>
                        	<tr>
                            	<td></td>
                            	<td>No Reg</td>
                            	<td>: <?php echo $noreg; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>N.R.M</td>
                                <td>: <?php echo $detail_pasien->NRM ?></td>
                                <td></td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                            	<td></td>
                            	<td>Nama Pasien</td>
                            	<td>: <?php echo $detail_pasien->NamaPasien ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Alamat</td>
                                <td>: <?php echo $detail_pasien->Alamat ?></td>
                                <td></td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <thead>
                            	<tr style="border:1px solid black;">
                                	<th style="border:1px solid black; padding:8px;"><?php echo 'No'; ?></th>
	                            	<th style="border:1px solid black; padding:8px;"><?php echo 'No.Bukti'; ?></th>
	                            	<th style="border:1px solid black; padding:8px;"><?php echo 'Tgl'; ?></th>
	                            	<th style="border:1px solid black; padding:8px;"><?php echo 'Nama '.$bill_type; ?></th>
	                            	<th style="border:1px solid black; padding:8px;"><?php echo 'Qty'; ?></th>
                                    <th style="border:1px solid black; padding:8px;"><?php echo 'Nilai'; ?></th>
	                            	<th style="border:1px solid black; padding:8px;"><?php echo 'Disc %' ?></th>
	                            	<th style="border:1px solid black; padding:8px;"><?php echo 'Section' ?></th>
                                    <th style="border:1px solid black; padding:8px;"><?php echo 'Dokter' ?></th>
                                </tr> 
                            </thead>
                            <tbody>	
								<?php $i = 1 ;  if(!empty($reports)) : foreach ($reports as $report) :   ?>
                            	<tr style="border:1px dotted black; ">
                                	<td width="6" align="center" style="border:1px solid black; padding:2px;"><?php echo $i; ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$report->NoBukti ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo date("Y-m-d",strtotime(@$report->Tanggal)) ?></td>
                                    <td style="border:1px solid black; padding:2px;"><?php echo @$report->JenisBiaya ?></td>
                                	<td style="border:1px solid black; padding:2px;"><?php echo @$report->Qty ?></td>
                                    <td style="border:1px solid black; padding:2px; text-align:right"><?php echo number_format(@$report->Nilai,2,',','.') ?></td>
                                    <td style="border:1px solid black; padding:2px;"><?php echo @$report->Disc ?></td>
                                    <td style="border:1px solid black; padding:2px;"><?php echo @$report->SectionName ?></td>
                                    <td style="border:1px solid black; padding:2px;"><?php echo @$report->DokterName ?></td>                                    
                                </tr>
                                <?php $i++; endforeach; endif;?>
                                <tr></tr>
                                <tr>
                                	<td colspan="3"></td>
                                    <td></td>
                                    <td></td>
                                	<td class="tulisan" align="left">Sub Total </td>
                                	<td align="right">= </td>
                                    <td>&nbsp;Rp.<?php echo $sub_total ?></td>
                               </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-lg-12">
                	<div class="table-responsive">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
