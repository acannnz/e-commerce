<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>

<ul class="dev-page-navigation">
    <li class="title"><?php echo lang("nav") ?></li>
    <li class="active">
        <a href="<?php echo base_url() ?>"><i class="fa fa-desktop"></i> <span><?php echo lang("nav:dashboard") ?></span></a>
    </li>
    <li class="title"><?php echo lang("nav:transaction") ?></li>
    <li><a href="<?php echo base_url( 'reservations' ) ?>"><i class="fa fa-plus-square"></i> <span><?php echo lang("nav:reservation") ?></span></a></li>
    <li><a href="<?php echo base_url( 'registrations' ) ?>"><i class="fa fa-tasks"></i> <span><?php echo lang("nav:registration") ?></span></a></li>
    <li><a href="<?php echo base_url( 'examinations' ) ?>"><i class="fa fa-stethoscope"></i> <span><?php echo lang("nav:examination") ?></span></a></li>
    <li class="title"><?php echo lang("global:common_title") ?></li>
    <li>
        <a href="<?php echo base_url( 'common/patients' ) ?>"><i class="fa fa-user-md"></i> <span><?php echo lang("nav:patients") ?></span></a>
        <ul>                                
            <li><a href="<?php echo base_url( 'common/patients' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_patients") ?></span></a></li>
            <li><a href="<?php echo base_url( 'common/patients/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_patient") ?></span></a></li>
        	<li><a href="<?php echo base_url( 'common/patient-types' ) ?>" data-toggle="ajax-modal"><i class="fa fa-folder-o"></i> <span><?php echo lang("nav:patient_types") ?></span></a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo base_url( 'common/services' ) ?>"><i class="fa  fa-ticket"></i> <span><?php echo lang("nav:services") ?></span></a>
        <ul>                                
            <li><a href="<?php echo base_url( 'common/services' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_services") ?></span></a></li>
            <li><a href="<?php echo base_url( 'common/services/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_service") ?></span></a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo base_url( 'common/icd' ) ?>"><i class="fa fa-tags"></i> <span><?php echo lang("nav:icd") ?></span></a>
        <ul>                                
            <li><a href="<?php echo base_url( 'common/icd' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_icd") ?></span></a></li>
            <li><a href="<?php echo base_url( 'common/icd/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_icd") ?></span></a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo base_url( 'common/zones' ) ?>"><i class="fa fa-map-marker"></i> <span><?php echo lang("nav:zones") ?></span></a>
        <ul>                                
            <li>
            	<a href="<?php echo base_url( 'common/zones/country' ) ?>"><?php echo lang("nav:zone_countries") ?></a>
            	<ul>                                
                    <li><a href="<?php echo base_url( 'common/zones/country' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_countries") ?></span></a></li>
                    <li><a href="<?php echo base_url( 'common/zones/country/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_country") ?></span></a></li>
                </ul>
            </li>
            <li>
            	<a href="<?php echo base_url( 'common/zones/province' ) ?>"><?php echo lang("nav:zone_provinces") ?></a>
            	<ul>                                
                    <li><a href="<?php echo base_url( 'common/zones/province' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_provinces") ?></span></a></li>
                    <li><a href="<?php echo base_url( 'common/zones/province/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_province") ?></span></a></li>
                </ul>
            </li>
            <li>
            	<a href="<?php echo base_url( 'common/zones/county' ) ?>"><?php echo lang("nav:zone_counties") ?></a>
            	<ul>                                
                    <li><a href="<?php echo base_url( 'common/zones/county' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_counties") ?></span></a></li>
                    <li><a href="<?php echo base_url( 'common/zones/county/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_county") ?></span></a></li>
                </ul>
            </li>
            <li>
            	<a href="<?php echo base_url( 'common/zones/district' ) ?>"><?php echo lang("nav:zone_districts") ?></a>
            	<ul>                                
                    <li><a href="<?php echo base_url( 'common/zones/district' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_districts") ?></span></a></li>
                    <li><a href="<?php echo base_url( 'common/zones/district/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_district") ?></span></a></li>
                </ul>
            </li>
            <li>
            	<a href="<?php echo base_url( 'common/zones/area' ) ?>"><?php echo lang("nav:zone_areas") ?></a>
            	<ul>                                
                    <li><a href="<?php echo base_url( 'common/zones/area' ) ?>"><i class="fa fa-search"></i> <span><?php echo lang("nav:search_areas") ?></span></a></li>
                    <li><a href="<?php echo base_url( 'common/zones/area/create' ) ?>" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <span><?php echo lang("nav:submit_area") ?></span></a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="title"><?php echo lang("global:system_title") ?></li>
    <li>
        <a href="<?php echo base_url('settings') ?>"><i class="fa fa-gears"></i> <span><?php echo lang("nav:settings") ?></span></a>
        <ul>
            <li><a href="<?php echo base_url('settings') ?>?settings=general"><?php echo lang('company_details')?></a></li>
            <li><a href="<?php echo base_url('settings') ?>?settings=system"><?php echo lang('system_settings')?></a></li>
            <li><a href="<?php echo base_url('settings') ?>?settings=email"><?php echo lang('email_settings')?></a></li>
            <li><a href="<?php echo base_url('settings') ?>?settings=templates"><?php echo lang('email_templates')?></a></li>
            <li><a href="<?php echo base_url('settings') ?>?settings=permissions"><?php echo lang('staff_permissions')?></a></li>
            <?php /*?><li><a href="<?php echo base_url('settings') ?>?settings=departments"><?php echo lang('departments')?></a></li><?php */?>
            <li><a href="<?php echo base_url('settings') ?>?settings=theme"><?php echo lang('theme_settings')?></a></li>
            <?php /*?><li><a href="<?php echo base_url('settings') ?>?settings=custom_fields"><?php echo lang('fields')?></a></li><?php */?>
            <li><a href="<?php echo base_url('settings') ?>?settings=translations"><?php echo lang('translations')?></a></li>                          
        </ul>
    </li>
    <li><a href="<?php echo base_url('users/accounts') ?>"><i class="fa fa-users"></i> <span><?php echo lang("nav:users") ?></span></a></li>
    <li class="title"><?php echo lang("global:user_title") ?></li>
    <li><a href="<?php echo base_url('profile/settings') ?>"><i class="fa fa-barcode"></i> <span><?php echo lang("nav:my_profile") ?></span></a></li>
    <li><a href="<?php echo base_url('profile/activities') ?>"><i class="fa fa-undo"></i> <span><?php echo lang("nav:activities") ?> (<?php echo (int) @num_activities; ?>)</span></a></li>
    <li><a href="<?php echo base_url('logout') ?>"><i class="fa fa-power-off"></i> <span><?php echo lang("nav:logout") ?></span></a></li>
</ul>

