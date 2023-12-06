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
    <li<?php if(in_array(@$page, array("journal_transactions","journal_transaction_create"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-calendar-o"></i> <span><?php echo lang("nav:journal_transaction") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("journal_transactions"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/journals' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_journal_transactions") ?></span></a></li>
            <li<?php if(in_array(@$page, array("journal_transaction_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/journals/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_journal_transaction") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("general","general"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("general-ledger/general") ?>"><i class="fa fa-book"></i> <span><?php echo lang("nav:view_journal") ?></span></a>
    </li>
    <li<?php if(in_array(@$page, array("general_ledger","general_ledger_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("general-ledger") ?>"><i class="fa fa-balance-scale"></i> <span><?php echo lang("nav:ledger") ?></span></a>
    </li>
	
	<li class="title"><?php echo lang("nav:references") ?></li>     
	<li<?php if(in_array(@$page, array("accounting_account","accounting_account_tree","accounting_account_create"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-folder"></i> <span><?php echo lang("nav:accounts") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("account_tree"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/accounts/tree' ) ?>"><i class="fa fa-tree"></i> <span><?php echo lang("nav:tree_accounts") ?></span></a></li>
            <li<?php if(in_array(@$page, array("account_concept"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/account/concepts' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:account_concept") ?></span></a></li>
            <li<?php if(in_array(@$page, array("account_structure"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/account/concepts' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:account_structure") ?></span></a></li>
            <li<?php if(in_array(@$page, array("account_income_loss"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/account/income-loss-setup' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:account_income_loss") ?></span></a></li>
        </ul>
    </li>
	<li<?php if(in_array(@$page, array("cash_flow_groups"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-folder"></i> <span><?php echo lang("nav:cash_flow") ?></span></a>
		 <ul>                                
            <li<?php if(in_array(@$page, array("cash_flow_groups"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/cash-flow/setup' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:cash_flow_setup") ?></span></a></li>
            <li<?php if(in_array(@$page, array("cash_flow_groups"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/cash-flow/account' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:cash_flow_account") ?></span></a></li>
        </ul>
	</li>
	
	<li class="title"><?php echo lang("nav:preferences") ?></li>  
	<li<?php if(in_array(@$page, array("beginning_balances"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("general-ledger/beginning-balance")?>"><i class="fa fa-sort-numeric-asc"></i> <span><?php echo lang("nav:beginning_balances") ?></span></a>
    </li>
	
	<li class="title"><?php echo lang("nav:equipments") ?></li>  
    <li<?php if(in_array(@$page, array("close_books", "cancel_close_books"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-book"></i> <span><?php echo lang("nav:close_books") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("close_books"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/closing' ) ?>"><i class="fa fa-folder-o"></i> <span><?php echo lang("nav:close_books") ?></span></a></li>
            <li<?php if(in_array(@$page, array("cancel_close_books"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/closing/cancel' ) ?>" data-toggle="ajax-modal"><i class="fa fa-folder-open-o"></i> <span><?php echo lang("nav:cancel_close_books") ?></span></a></li>
        </ul>
    </li>    
	    
    <li class="title"><?php echo lang("global:report") ?></li>
	<li<?php if(in_array(@$page, array("balance_sheets", "trial_balance"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-balance-scale"></i> <span><?php echo lang("nav:balance_sheets") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("balance_sheets"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url("general-ledger/balance-sheet")?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:balance_sheets") ?></span></a></li>
            <li<?php if(in_array(@$page, array("trial_balance"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/balance-sheet/trial_balance' ) ?>" data-toggle="ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:trial_balance") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("income_loss, income_loss_quarterly, income_loss_annual"))){echo " class=\"active\"";} ?>>
        <a href="javasacript:;"><i class="fa fa-bar-chart"></i> <span><?php echo lang("nav:income_loss") ?></span></a>
		<ul>                                
            <li<?php if(in_array(@$page, array("income_loss"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/income-loss' ) ?>"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:income_loss") ?></span></a></li>
            <li<?php if(in_array(@$page, array("income_loss_quarterly"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/income-loss/quarterly' ) ?>" data-toggle="ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:income_loss_quarterly") ?></span></a></li>
			<li<?php if(in_array(@$page, array("income_loss_annual"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'general-ledger/income-loss/annual' ) ?>" data-toggle="ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:income_loss_annual") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("cash_flow_groups"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("general-ledger/cash-flow/report");?>"><i class="fa fa-line-chart"></i> <span><?php echo lang("nav:cash_flow_report") ?></span></a>
    </li>    
</ul>


