<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>

<?php /*?><?php if(config_item('enable_languages') == 'TRUE'): ?>
<ul class="dev-lang-navigation">
    <h4><span class="label"><?php echo lang('languages')?></h4>
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
<?php endif ?><?php */?>

<ul class="dev-page-navigation">
    <li class="title"><?php echo lang("nav:users") ?></li>
    <li<?php if(in_array(@$page, array("", "welcome", "dashboard"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url() ?>"><i class="fa fa-lg fa-desktop"></i> <?php echo lang("nav:dashboard") ?></a>
    </li>
    
    <li class="title"><?php echo lang("nav:servings") ?></li>
    <li<?php if(in_array(@$page, array("general"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/outpatient' ) ?>"><i class="fa fa-lg fa-stethoscope"></i> <b><?php echo lang("nav:examination") ?></b></a></li>
    
    <li class="title"><?php echo lang("nav:transaction") ?></li>
    <li<?php if(in_array(@$page, array("inquiry"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inquiry/request-list/outpatient' ) ?>"><i class="fa fa-lg fa-circle-thin"></i> <?php echo lang("nav:inquiry") ?></a></li>
    <li<?php if(in_array(@$page, array("stock_opname"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inquiry/stock-opname-list/outpatient' ) ?>"><i class="fa fa-lg fa-circle-thin"></i> <?php echo lang("nav:stock_opname") ?></a></li>
    <li<?php if(in_array(@$page, array("item_usage"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/item-usage/outpatient' ) ?>"><i class="fa fa-lg fa-circle-thin"></i> <?php echo lang("nav:item_usage") ?></a></li>
    
 	<li class="title"><?php echo lang("nav:reports") ?></li>
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;" data-toggle="ajax-modal"><i class="fa fa-file-text-o"></i> <span><?php echo lang("nav:stock_report") ?></span></a>
        <ul>                                
			<li<?php if(in_array(@$page, array("report_warehouse_cards"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/reports/warehouse-cards/outpatient' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-lg fa-file-text"></i> <?php echo lang("nav:report_warehouse_cards") ?></a></li>
			<li<?php if(in_array(@$page, array("report_recap_stocks"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/reports/recap-stocks/outpatient' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-lg fa-file-text"></i> <?php echo lang("nav:report_recap_stocks") ?></a></li>
			<li<?php if(in_array(@$page, array("report_stock_opname"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/reports/stock-opname/outpatient' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-lg fa-file-text"></i> <?php echo lang("nav:report_stock_opname") ?></a></li>
		</ul>
	</li>
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;" data-toggle="ajax-modal"><i class="fa fa-file-text-o"></i> <span><?php echo lang("nav:performance_report") ?></span></a>
        <ul>                                
			<li<?php if(in_array(@$page, array("report_unit_performance"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/reports/unit-performance/outpatient' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-lg fa-file-text"></i> <?php echo lang("nav:unit_performance_report") ?></a></li>
		</ul>
	</li>
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;" data-toggle="ajax-modal"><i class="fa fa-file-text-o"></i> <span><?php echo lang("nav:turnover_report") ?></span></a>
        <ul>                                
			<li<?php if(in_array(@$page, array("report_turnover"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'poly/reports/turnover' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-lg fa-file-text"></i> <?php echo lang("nav:turnover_report") ?></a></li>
		</ul>
	</li>
	
</ul>


