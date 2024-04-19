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
    <li class="title"><?php echo lang("nav:users") ?></li>
    <li<?php if(in_array(@$page, array("", "welcome", "dashboard"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url() ?>"><i class="fa fa-lg fa-desktop"></i> <?php echo lang("nav:dashboard") ?></a>
    </li>
    
    <li class="title"><?php echo lang("nav:transactions") ?></li>
    <li<?php if(in_array(@$page, array("payable_factur",))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url('payable/factur') ?>"><i class="fa fa-calendar-check-o"></i> <?php echo lang("nav:payable_factur") ?></a>
    </li>
    <li<?php if(in_array(@$page, array("payable_voucher",))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url('payable/vouchers') ?>"><i class="fa fa-credit-card"></i> <?php echo lang("nav:payable_voucher") ?></a>
    </li>
    <li<?php if(in_array(@$page, array("payable_credit_debit_notes",))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url('payable/credit-debit-note') ?>"><i class="fa fa-exchange"></i> <?php echo lang("nav:payable_credit_debit_notes") ?></a>
    </li>
    
    <li class="title"><?php echo lang("nav:references") ?></li>
    <li<?php if(in_array(@$page, array("payable_types"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'payable/types' ) ?>"><i class="fa fa-folder"></i> <?php echo lang("nav:payable_types") ?></a></li>
    
    <li class="title"><?php echo lang("nav:preferences") ?></li>
    <li<?php if(in_array(@$page, array("beginning_balances"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("payable/beginning-balance")?>"><i class="fa fa-sort-numeric-asc"></i> <span><?php echo lang("nav:beginning_balances") ?></span></a>
    </li>
    
    <li class="title"><?php echo lang("nav:equipments") ?></li>
    <li<?php if(in_array(@$page, array("payable_posting","payable_posting_cancel"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-clone"></i> <?php echo lang("nav:payable_posting") ?></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("payable_posting"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('payable/postings') ?>"><?php echo lang("nav:payable_posting") ?></a>
            <li<?php if(in_array(@$page, array("payable_posting_cancels"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'payable/postings/cancel' ) ?>" data-toggle="ajax-modal"><?php echo lang("nav:payable_posting_cancel") ?></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("payable_close_books", "payable_cancel_close_books"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-book"></i> <?php echo lang("nav:close_books") ?></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("payable_close_books"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'payable/closing' ) ?>"><?php echo lang("nav:close_books") ?></a></li>
            <li<?php if(in_array(@$page, array("payable_cancel_close_books"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'payable/closing/cancel' ) ?>" data-toggle="ajax-modal"><?php echo lang("nav:cancel_close_books") ?></a></li>
        </ul>
    </li>
    
    <li class="title"><?php echo lang("global:report") ?></li>
	<li<?php if(in_array(@$page, array("payable_aging"))){echo " class=\"active\"";} ?>>
    	<a href="<?php echo base_url( 'payable/aging' ) ?>" data-toggle="ajax-modal"><i class="fa fa-clock-o"></i> <?php echo lang("nav:payable_aging") ?></a>
    </li>
    <li<?php if(in_array(@$page, array("payable_report_card"))){echo " class=\"active\"";} ?>>
    	<a href="<?php echo base_url( 'payable/reports' ) ?>" data-toggle="ajax-modal"><i class="fa fa-print"></i> <?php echo lang("global:report") ?></a>
    </li>
</ul>


