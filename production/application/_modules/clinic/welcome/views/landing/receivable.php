<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-xs-12">
    	<div class="row">
        	<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'receivable/factur' ) ?>" class="tile tile-default">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:receivable_factur") ?></h3>
                </a>
            </div>
            <div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'receivable/invoices' ) ?>" class="tile tile-success">
                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:receivable_invoice") ?></h3>
                </a>
            </div>
            <div class="col-sm-12 col-xs-12">
                <a href="<?php echo base_url( 'receivable/credit-debit-note' ) ?>" class="tile tile-primary">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:receivable_credit_debit_notes") ?></h3>
                </a>
            </div>
        </div>
    </div>    
</div>