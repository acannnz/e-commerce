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
    <li<?php if(in_array(@$page, array("reservations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'reservations' ) ?>"><i class="fa fa-plus-square"></i> <span><?php echo lang("nav:reservation") ?></span></a></li>
    <li<?php if(in_array(@$page, array("registrations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'registrations' ) ?>"><i class="fa fa-tasks"></i> <span><?php echo lang("nav:registration") ?></span></a></li>
    <li<?php if(in_array(@$page, array("examinations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'examinations' ) ?>"><i class="fa fa-stethoscope"></i> <span><?php echo lang("nav:examination") ?></span></a></li>
    
    <li class="title"><?php echo lang("global:common_title") ?></li>
    <li<?php if(in_array(@$page, array("common_patient_types","common_patients_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url( 'common/patients' ) ?>"><i class="fa fa-user-md"></i> <span><?php echo lang("nav:patients") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("common_patients"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/patients' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_patients") ?></span></a></li>
            <li<?php if(in_array(@$page, array("common_patients_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/patients/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_patient") ?></span></a></li>
        	<li<?php if(in_array(@$page, array("common_patient_types"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/patient-types' ) ?>" data-toggle="ajax-modal"><i class="fa fa-folder-o"></i> <span><?php echo lang("nav:patient_types") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("common_services","common_services_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url( 'common/services' ) ?>"><i class="fa  fa-ticket"></i> <span><?php echo lang("nav:services") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("common_services"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/services' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_services") ?></span></a></li>
            <li<?php if(in_array(@$page, array("common_services_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/services/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_service") ?></span></a></li>
        </ul>
    </li>
    <?php if( 'TRUE' == $this->config->item( "enable_chart_drug" ) ): ?>
    <li<?php if(in_array(@$page, array("products_create","products_unit","products_unit_create","products_group","products_group_create","products_group_type","products_group_type_create","products_category","products_category_create","products_class","products_class_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url( 'inventory/products' ) ?>"><i class="fa fa-barcode"></i> <span><?php echo lang("nav:inventory") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("products","products_create"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/products' ) ?>"><?php echo lang("nav:products") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("products")) ){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_products") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("products_create")) ){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_products") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("products_class","products_class_create"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/products/class' ) ?>"><?php echo lang("nav:product_class") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("products_class"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/class' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_class") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("products_class_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/class/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_class") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("products_category","products_category_create"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/products/category' ) ?>"><?php echo lang("nav:product_category") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("products_category"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/category' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_category") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("products_category_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/category/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_category") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("products_group","products_group_create"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/products/group' ) ?>"><?php echo lang("nav:product_group") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("product_group"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/group' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_group") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("product_group_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/group/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_group") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("products_group_type","products_group_type_create"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/products/group-type' ) ?>"><?php echo lang("nav:product_group_type") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("products_group_type"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/group-type' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_group_type") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("products_group_type_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/group-type/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_group_type") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("products_unit","products_unit_create"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/products/unit' ) ?>"><?php echo lang("nav:product_unit") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("products_unit"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/unit' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_unit") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("products_unit_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/products/unit/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_unit") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("stock","stock_out"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url( 'inventory/stock-out' ) ?>"><?php echo lang("nav:stock") ?></a>
                <ul>                                
                    <li<?php if(in_array(@$page, array("stock_out"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'inventory/stock-out' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:stock_out") ?></span></a></li>
                </ul>
            </li>
        </ul>
    </li>
    <?php endif ?>
    <li<?php if(in_array(@$page, array("common_icd","common_icd_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url( 'common/icd' ) ?>"><i class="fa fa-tags"></i> <span><?php echo lang("nav:icd") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("common_icd"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/icd' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_icd") ?></span></a></li>
            <li<?php if(in_array(@$page, array("common_icd_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/icd/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_icd") ?></span></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("common_zones","common_zones_country","common_zones_country_create","common_zones_province","common_zones_province_create","common_zones_county","common_zones_county_create","common_zones_district","common_zones_district_create","common_zones_area","common_zones_area_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url( 'common/zones' ) ?>"><i class="fa fa-map-marker"></i> <span><?php echo lang("nav:zones") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("common_zones_country","common_zones_country_create"))){echo " class=\"active\"";} ?>>
            	<a href="<?php echo base_url( 'common/zones/country' ) ?>"><?php echo lang("nav:zone_countries") ?></a>
            	<ul>                                
                    <li<?php if(in_array(@$page, array("common_zones_country"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/country' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_countries") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("common_zones_country_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/country/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_country") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("common_zones_province","common_zones_province_create"))){echo " class=\"active\"";} ?>>
            	<a href="<?php echo base_url( 'common/zones/province' ) ?>"><?php echo lang("nav:zone_provinces") ?></a>
            	<ul>                                
                    <li<?php if(in_array(@$page, array("common_zones_province"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/province' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_provinces") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("common_zones_province_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/province/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_province") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("common_zones_county","common_zones_county_create"))){echo " class=\"active\"";} ?>>
            	<a href="<?php echo base_url( 'common/zones/county' ) ?>"><?php echo lang("nav:zone_counties") ?></a>
            	<ul>                                
                    <li<?php if(in_array(@$page, array("common_zones_county"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/county' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_counties") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("common_zones_county_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/county/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_county") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("common_zones_district","common_zones_district_create"))){echo " class=\"active\"";} ?>>
            	<a href="<?php echo base_url( 'common/zones/district' ) ?>"><?php echo lang("nav:zone_districts") ?></a>
            	<ul>                                
                    <li<?php if(in_array(@$page, array("common_zones_district"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/district' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_districts") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("common_zones_district_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/district/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_district") ?></span></a></li>
                </ul>
            </li>
            <li<?php if(in_array(@$page, array("common_zones_area","common_zones_area_create"))){echo " class=\"active\"";} ?>>
            	<a href="<?php echo base_url( 'common/zones/area' ) ?>"><?php echo lang("nav:zone_areas") ?></a>
            	<ul>                                
                    <li<?php if(in_array(@$page, array("common_zones_area"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/area' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_areas") ?></span></a></li>
                    <li<?php if(in_array(@$page, array("common_zones_area_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/zones/area/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_area") ?></span></a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("component_services","component_products"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-heart"></i> <span><?php echo lang("nav:components") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("component_services"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'components/services' ) ?>"><i class="fa fa-folder-o"></i> <span><?php echo lang("nav:comp_services") ?></span></a></li>
            <?php if( 'TRUE' == $this->config->item( "enable_chart_template" ) ): ?>
            <li<?php if(in_array(@$page, array("component_products"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'components/products' ) ?>"><i class="fa fa-folder-o"></i> <span><?php echo lang("nav:comp_products") ?></span></a></li>
        	<?php endif ?>
        </ul>
    </li>
    <?php if( 'TRUE' == $this->config->item( "enable_chart_template" ) ): ?>
    <li<?php if(in_array(@$page, array("common_chart_templates","common_chart_templates_create"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url( 'common/chart_template' ) ?>"><i class="fa fa-th-list"></i> <span><?php echo lang("nav:chart_templates") ?></span></a>
        <ul>                                
            <li<?php if(in_array(@$page, array("common_chart_templates"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/chart-templates' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_chart_templates") ?></span></a></li>
            <li<?php if(in_array(@$page, array("common_chart_templates_create"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/chart-templates/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_chart_template") ?></span></a></li>
        </ul>
    </li>
    <?php endif ?>
    <li class="title"><?php echo lang("global:report") ?></li>
    <li<?php if(in_array(@$page, array("reports"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url("reports");?>"><i class="fa fa-file-text-o"></i> <span><?php echo lang("global:report") ?></span></a>
    </li>
    <li class="title"><?php echo lang("global:system_title") ?></li>
    <?php /*?><li>
        <a href="<?php echo base_url('settings') ?>"><i class="fa fa-gears"></i> <span><?php echo lang("nav:settings") ?></span></a>
        <ul>
            <li><a href="<?php echo base_url('settings') ?>" title="<?php echo lang('company_details') ?>"><?php echo lang('company_details') ?></a></li>
            <li><a href="<?php echo base_url('settings/system') ?>" title="<?php echo lang('system_settings') ?>"><?php echo lang('system_settings') ?></a></li>
            <li><a href="<?php echo base_url('settings/email') ?>" title="<?php echo lang('email_settings') ?>"><?php echo lang('email_settings') ?></a></li>
            <li><a href="<?php echo base_url('settings/templates') ?>" title="<?php echo lang('email_templates') ?>"><?php echo lang('email_templates') ?></a></li>
            <li><a href="<?php echo base_url('settings/permissions') ?>" title="<?php echo lang('staff_permissions') ?>"><?php echo lang('staff_permissions') ?></a></li>
            <li><a href="<?php echo base_url('settings/departments') ?>" title="<?php echo lang('departments') ?>"><?php echo lang('departments') ?></a></li>
            <li><a href="<?php echo base_url('settings/theme') ?>" title="<?php echo lang('theme_settings') ?>"><?php echo lang('theme_settings') ?></a></li>
            <li><a href="<?php echo base_url('settings/custom_fields') ?>" title="<?php echo lang('fields') ?>"><?php echo lang('fields') ?></a></li>
            <li><a href="<?php echo base_url('settings/translations') ?>" title="<?php echo lang('translations') ?>"><?php echo lang('translations') ?></a></li>                          
            <li><a href="<?php echo base_url( 'settings/database' ) ?>" title="<?php echo lang('database_backup') ?>"><span class="text-danger"><?php echo lang('database_backup') ?></span></a></li>                        
        </ul>
    </li><?php */?>
    <li><a href="<?php echo base_url('settings') ?>"><i class="fa fa-gears"></i> <span><?php echo lang("nav:settings") ?></span></a></li>
    <li<?php if(in_array(@$page, array("users_accounts"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('users/accounts') ?>"><i class="fa fa-users"></i> <span><?php echo lang("nav:users") ?></span></a></li>
    <li<?php if(in_array(@$page, array("api_server_restful_keys","api_server_restful_access","api_server_restful_limits","api_server_restful_controllers","api_server_restful_uris"))){echo " class=\"active\"";} ?>>
    	<a href="javascript:;"><i class="fa fa-cloud"></i> <span><?php echo lang("nav:web_services") ?></span></a>
        <ul>
            <li<?php if(in_array(@$page, array("api_server_restful_keys","api_server_restful_access","api_server_restful_limits","api_server_restful_controllers","api_server_restful_uris"))){echo " class=\"active\"";} ?>>
                <a href="<?php echo base_url('api/server/restful-keys') ?>"><i class="fa fa-link"></i> <span><?php echo lang("nav:restful") ?></span></a>
                <ul>
                    <li<?php if(in_array(@$page, array("api_server_restful_keys"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('api/server/restful-keys') ?>" title="<?php echo lang('nav:restful_keys')?>"><i class="fa fa-users"></i> <span><?php echo lang('nav:restful_keys')?></span></a></li>
                    <li<?php if(in_array(@$page, array("api_server_restful_access"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('api/server/restful-access') ?>" title="<?php echo lang('nav:restful_access')?>"><i class="fa fa-asterisk"></i> <span><?php echo lang('nav:restful_access')?></span></a></li>
                    <li<?php if(in_array(@$page, array("api_server_restful_limits"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('api/server/restful-limits') ?>" title="<?php echo lang('nav:restful_limits')?>"><i class="fa fa-tachometer"></i> <span><?php echo lang('nav:restful_limits')?></span></a></li>
                    <li<?php if(in_array(@$page, array("api_server_restful_controllers"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('api/server/restful-system-controllers') ?>" title="<?php echo lang('nav:restful_controllers')?>"><i class="fa fa-bookmark"></i> <span><?php echo lang('nav:restful_controllers')?></span></a></li>
                    <li<?php if(in_array(@$page, array("api_server_restful_uris"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('api/server/restful-system-uris') ?>" title="<?php echo lang('nav:restful_uris')?>"><i class="fa fa-bookmark-o"></i> <span><?php echo lang('nav:restful_uris')?></span></a></li>
                    <li<?php if(in_array(@$page, array("api_server_restful_logs"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('api/server/restful-logs') ?>" title="<?php echo lang('nav:restful_logs')?>"><i class="fa fa-history"></i> <span><?php echo lang('nav:restful_logs')?></span></a></li>                        
                </ul>
            </li>
        </ul>
    </li>
    
    <li class="title"><?php echo lang("global:user_title") ?></li>
    <li<?php if(in_array(@$page, array("profile_settings"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('profile/settings') ?>"><i class="fa fa-user"></i> <span><?php echo lang("nav:my_profile") ?></span></a></li>
    <li<?php if(in_array(@$page, array("profile_activities"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url('profile/activities') ?>"><i class="fa fa-undo"></i> <span><?php echo lang("nav:activities") ?> (<?php echo (int) @num_activities; ?>)</span></a></li>
    <li><a href="<?php echo base_url('logout') ?>"><i class="fa fa-power-off"></i> <span><?php echo lang("nav:logout") ?></span></a></li>
</ul>


