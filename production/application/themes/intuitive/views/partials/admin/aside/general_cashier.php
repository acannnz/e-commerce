<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>

<?php if(config_item('enable_languages') == 'TRUE'): ?>
<ul class="dev-lang-navigation">
    <h4><span class="label"><?php echo lang('languages')?></span></h4>
    <div class="btn-group">
        <button type="button" class="btn btn-icon btn-danger dropdown-toggle" data-toggle="dropdown" title="<?php echo lang('languages')?>">
            <i class="fa fa-globe"></i>
        </button>
        <ul class="dropdown-menu text-left">
            <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
            <li>
                <a href="<?php echo base_url()?>set_language?lang=<?php echo $lang->name?>" title="<?php echo ucwords(str_replace("_"," ", $lang->name))?>">
                    <img src="<?php echo base_url()?>resource/images/flags/<?php echo $lang->icon ?>.gif" alt="<?php echo ucwords(str_replace("_"," ", $lang->name))?>"  /> <?php echo ucwords(str_replace("_"," ", $lang->name))?>
                </a>
            </li>
            <?php endif; endforeach; ?>
        </ul>
    </div>
</ul>
<?php endif ?>

<ul class="dev-page-navigation">
    <li class="title"><?php echo lang("nav") ?></li>
    <li<?php if(in_array(@$page, array("", "welcome", "dashboard"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url() ?>"><i class="fa fa-desktop"></i> <span><?php echo lang("nav:dashboard") ?></span></a>
    </li>
    <li class="title"><?php echo lang("nav:transaction") ?></li>
    <li<?php if(in_array(@$page, array("cash_bank_income"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-envelope-open"></i> <span><?php echo lang("nav:cash_bank_income") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("cash_bank_income_invoice"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('general-cashier/cash-bank-income/invoices') ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:cash_bank_income_invoice") ?></span></a>
            <li<?php if(in_array(@$page, array("general_cashier_posting_cancels"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-cashier/cash-bank-income/non-invoices' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:cash_bank_income_non_invoice") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("cash_bank_expense"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-credit-card-alt"></i> <span><?php echo lang("nav:cash_bank_expense") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("cash_bank_expense_voucher"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('general-cashier/cash-bank-expense/vouchers') ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:cash_bank_expense_voucher") ?></span></a>
            <li<?php if(in_array(@$page, array("cash_bank_expense_non_voucher"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-cashier/cash-bank-expense/non-vouchers' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:cash_bank_expense_non_voucher") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("cash_bank_mutation"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("general-cashier/cash-bank-mutation")?>"><i class="fa fa-exchange"></i> <span><?php echo lang("nav:cash_bank_mutation") ?></span></a>
    </li>
	
	<li class="title"><?php echo lang("nav:equipment") ?></li>
    <li<?php if(in_array(@$page, array("general_cashier_posting","general_cashier_posting_cancel"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-clone"></i> <span><?php echo lang("nav:general_cashier_posting") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("general_cashier_posting"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('general-cashier/postings') ?>"><i class="fa fa-random"></i> <span><?php echo lang("nav:general_cashier_posting") ?></span></a>
            <li<?php if(in_array(@$page, array("general_cashier_posting_cancels"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-cashier/postings/cancel' ) ?>" data-toggle="ajax-modal"><i class="fa fa-reply-all"></i> <span><?php echo lang("nav:general_cashier_posting_cancel") ?></span></a></li>
        </ul>
    </li>
    
    <li class="title"><?php echo lang("global:report") ?></li>
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-print"></i> <span><?php echo lang("global:report") ?></span></a>
    </li>    
</ul>


