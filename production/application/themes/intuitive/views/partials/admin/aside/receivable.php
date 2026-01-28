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
	 <li<?php if(in_array(@$page, array("receivable_factur",))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url('receivable/factur') ?>"><i class="fa fa-calendar-check-o"></i> <span><?php echo lang("nav:receivable_factur") ?></span></a>
    </li>
    <li<?php if(in_array(@$page, array("receivable_invoice",))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url('receivable/invoices') ?>"><i class="fa fa-envelope-open"></i> <span><?php echo lang("nav:receivable_invoice") ?></span></a>
    </li>
    <li<?php if(in_array(@$page, array("receivable_credit_debit_notes",))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url('receivable/credit-debit-note') ?>"><i class="fa fa-exchange"></i> <span><?php echo lang("nav:receivable_credit_debit_notes") ?></span></a>
    </li>
   
   	<li class="title"><?php echo lang("nav:references") ?></li>
	<li<?php if(in_array(@$page, array("receivable_types","receivable_types_create"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-folder"></i> <span><?php echo lang("nav:receivable_types") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("receivable_types"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'receivable/types' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_receivable_types") ?></span></a></li>
            <li<?php if(in_array(@$page, array("receivable_types_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'receivable/types/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_receivable_type") ?></span></a></li>
        </ul>
    </li>
	
	<li class="title"><?php echo lang("nav:preferences") ?></li>
	<li<?php if(in_array(@$page, array("beginning_balances"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("receivable/beginning-balance")?>"><i class="fa fa-sort-numeric-asc"></i> <span><?php echo lang("nav:beginning_balances") ?></span></a>
    </li>
	
	<li class="title"><?php echo lang("nav:equipments") ?></li>
    <li<?php if(in_array(@$page, array("receivable_posting","receivable_posting_cancel"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-clone"></i> <span><?php echo lang("nav:receivable_posting") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("receivable_posting"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('receivable/postings') ?>"><i class="fa fa-random"></i> <span><?php echo lang("nav:receivable_posting") ?></span></a>
            <li<?php if(in_array(@$page, array("receivable_posting_cancels"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'receivable/postings/cancel' ) ?>" data-toggle="ajax-modal"><i class="fa fa-reply-all"></i> <span><?php echo lang("nav:receivable_posting_cancel") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("receivable_close_books", "receivable_cancel_close_books"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-book"></i> <span><?php echo lang("nav:close_books") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("receivable_close_books"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'receivable/closing' ) ?>"><i class="fa fa-folder-o"></i> <span><?php echo lang("nav:close_books") ?></span></a></li>
            <li<?php if(in_array(@$page, array("receivable_cancel_close_books"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'receivable/closing/cancel' ) ?>" data-toggle="ajax-modal"><i class="fa fa-folder-open-o"></i> <span><?php echo lang("nav:cancel_close_books") ?></span></a></li>
        </ul>
    </li>
   
    <li class="title"><?php echo lang("global:report") ?></li>
	<li<?php if(in_array(@$page, array("receivable_aging"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("receivable/aging");?>"><i class="fa fa-clock-o"></i> <span><?php echo lang("nav:receivable_aging") ?></span></a>
    </li>    
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("receivable/reports");?>"><i class="fa fa-print"></i> <span><?php echo lang("global:report") ?></span></a>
    </li>  
</ul>


