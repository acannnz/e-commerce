<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-xs-12">
    	<div class="row">
        	<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'pharmacy' ) ?>" class="tile tile-default">
                    <i class="fa fa-handshake-o"></i>
                    <h3><?php echo lang("nav:drug_realization") ?></h3>
                </a>
            </div>
            <div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'pharmacy/drug-payment' ) ?>" class="tile tile-success">
                    <i class="fa fa-money"></i>
                    <h3><?php echo lang("nav:drug_payment") ?></h3>
                </a>
            </div>
        </div>
	</div>
</div>
