<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-xs-12">
    	<div class="row">
        	<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'general-cashier/cash-bank-income/invoices' ) ?>" class="tile tile-default">
                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:cash_bank_income_invoice") ?></h3>
                </a>
            </div>
			<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'general-cashier/cash-bank-expense/vouchers' ) ?>" class="tile tile-success">
                    <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:cash_bank_expense_voucher") ?></h3>
                </a>
            </div>
			<div class="col-sm-12 col-xs-12">
                <a href="<?php echo base_url( 'general-cashier/cash-bank-mutation') ?>" class="tile tile-primary">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:cash_bank_mutation") ?></h3>
                </a>
            </div>
        </div>
    </div>    
</div>
