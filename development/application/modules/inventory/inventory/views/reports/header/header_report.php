<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
    <?php if( $report_logo = $this->config->item( "apotek_logo" ) ):  ?>
    <div class="col-xs-2">
        <img src="<?php echo base_url( "resource/images/logos/".$report_logo ) ?>" width="150px" />
    </div>
    <div class="col-xs-6">
        <h3 style="color:#000000 !important; margin:0 !important;padding-top: 10px!important;"><?php echo $this->config->item( "apotek_name" ) ?></h3>
        <p style="font-size:11px; margin:0 !important;"><?php echo sprintf("%s", $this->config->item( "apotek_address" )) ?></p>
        <p style="font-size:11px;"><?= 'No. Telp' ?>: <span><?= $this->config->item( "apotek_telp" ) ?></span></p>
    </div>
    <?php else: ?>
    <div class="col-lg-12">
        <h3 style="margin:0 !important;text-align:center"> <strong><?php echo $this->config->item( "apotek_name" ) ?></strong></h3>
        <p  style="font-size:12px; margin:0 !important;text-align:center"><b><?php echo sprintf("%s",  $this->config->item( "apotek_address" )) ?></b></p>
        <p style="font-size:12px;text-align:center"><strong><?php echo 'Telp.' ?> <?php echo $this->config->item( "apotek_telp" ) ?></strong></p>
    </div>
    <?php endif ?>
</div>