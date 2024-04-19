<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-xs-12">
    	<div class="row">
        	<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'general-ledger/journals' ) ?>" class="tile tile-default">
                    <i class="fa fa-calendar-o" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:journal_transaction") ?></h3>
                </a>
            </div>
            <div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'general-ledger/general' ) ?>" class="tile tile-success">
                    <i class="fa fa-book" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:view_journal") ?></h3>
                </a>
            </div>
            <div class="col-sm-12 col-xs-12">
                <a href="<?php echo base_url( 'general-ledger' ) ?>" class="tile tile-primary">
                    <i class="fa fa-balance-scale" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:ledger") ?></h3>
                </a>
            </div>
        </div>
    </div>    
</div>