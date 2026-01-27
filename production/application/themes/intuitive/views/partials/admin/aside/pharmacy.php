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
            <?php if(!empty($languages)):foreach($languages as $lang) : if ($lang->active == 1) : ?>
            <li>
                <a href="<?php echo base_url()?>set_language?lang=<?php echo $lang->name?>" title="<?php echo ucwords(str_replace("_"," ", $lang->name))?>">
                    <img src="<?php echo base_url()?>resource/images/flags/<?php echo $lang->icon ?>.gif" alt="<?php echo ucwords(str_replace("_"," ", $lang->name))?>"  /> <?php echo ucwords(str_replace("_"," ", $lang->name))?>
                </a>
            </li>
            <?php endif; endforeach; endif; ?>
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
    <li<?php if(in_array(@$page, array("selling"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy' ) ?>"><i class="fa fa-file-text-o"></i> <span><?php echo lang("nav:drug_realization") ?></span></a></li>
    <?php /*?><li<?php if(in_array(@$page, array("drug_payment"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/drug-payment' ) ?>"><i class="fa fa-shopping-cart"></i> <span><?php echo lang("nav:drug_payment") ?></span></a></li><?php */?>
	<li<?php if(in_array(@$page, array("retur"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/retur' ) ?>"><i class="fa fa-reply-all"></i> <span><?php echo lang("nav:return_drug_realization") ?></span></a></li>
    <?php if(config_item('use_clerk') == 'TRUE'): ?>
        <li<?php if(in_array(@$page, array("clerk"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'clerk' ) ?>"><i class="fa fa-fax"></i> <span><?php echo 'Clerk' ?></span></a></li>
    <?php endif; ?>
	<li class="title"><?php echo lang("nav:tools") ?></li>
	<li<?php if(in_array(@$page, array("inquiry"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inquiry/request-list/pharmacy' ) ?>"><i class="fa fa-mail-forward"></i> <span><?php echo lang("nav:inquiry") ?></span></a></li>
    <li<?php if(in_array(@$page, array("mutation"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inquiry/mutation-list/pharmacy' ) ?>"><i class="fa fa-exchange"></i> <span><?php echo lang("nav:mutation") ?></span></a></li>
    <li<?php if(in_array(@$page, array("mutation_return"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inquiry/mutation-return-list/pharmacy' ) ?>"><i class="fa fa-mail-reply"></i> <span><?php echo lang("nav:mutation_return") ?></span></a></li>
    <li<?php if(in_array(@$page, array("stock_opname"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inquiry/stock-opname-list/pharmacy' ) ?>"><i class="fa fa-sort-numeric-asc"></i> <span><?php echo lang("nav:stock_opname") ?></span></a></li>
    
    <li class="title"><?php echo lang("global:report") ?></li>
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;" data-toggle="ajax-modal"><i class="fa fa-file-text-o"></i> <span><?php echo lang("global:report") ?></span></a>
        <ul>                          
			<li<?php if(in_array(@$page, array("recap_transactions"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/recap-transactions' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:report_recap_transactions") ?></span></a></li>    
			<li<?php if(in_array(@$page, array("daily_stock_recap"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/daily-stock-recap' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo "Rekap Stok Harian" ?></span></a></li>    
            <li<?php if(in_array(@$page, array("report_used_drugs"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/used-drugs' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo "Penggunaan Obat" ?></span></a></li>  
			<!-- <li<?php if(in_array(@$page, array("report_doctor_drug_incentives"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/doctor-drug-incentives' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo "Insentif Obat Dokter" ?></span></a></li> -->
            <li<?php if(in_array(@$page, array("report_warehouse_cards"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/warehouse-cards' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:report_warehouse_cards") ?></span></a></li>
            <li<?php if(in_array(@$page, array("report_recap_stocks"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/recap-stocks' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:report_recap_stocks") ?></span></a></li>
            <li<?php if(in_array(@$page, array("report_stock_opname"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'pharmacy/reports/stock-opname' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-circle-o"></i> <span><?php echo lang("nav:report_stock_opname") ?></span></a></li>
        </ul>
    </li>
</ul>


