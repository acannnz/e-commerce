<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-xs-12">
    	<div class="row">
        	<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'reservations' ) ?>" class="tile tile-default">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:reservation") ?></h3>
                </a>
            </div>
            <div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'registrations' ) ?>" class="tile tile-success">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:registration") ?></h3>
                </a>
            </div>
            <div class="col-sm-12 col-xs-12">
                <a href="<?php echo base_url( 'cashier/general-payment' ) ?>" class="tile tile-primary">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:cashier") ?></h3>
                </a>
            </div>
        </div>
    </div>    
</div>
