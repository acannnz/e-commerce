<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-3">
        <div class="profile margin-bottom-0">
            <div class="profile-info">
                <?php if( $profile->personal_picture ): ?>
                <a href="javascript:;" class="thumbnail">
                	<img src="<?php echo base_url( "resource/patient_cooperation_cards/pictures" ) ?>/<?php echo $profile->personal_picture ?><?php echo (sprintf("?rand=%s", @time())) ?>">
                </a>
                <?php else: ?>
                <a href="javascript:;" class="thumbnail">
                	<img src="<?php echo base_url( "resource/patient_cooperation_cards/pictures" ) ?>/default_picture.jpg">
                </a>
                <?php endif ?>
                <a href="<?php echo base_url( "common/patient_cooperation_cards/picture/{$profile->id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang("buttons:update_picture") ?>" class="btn btn-block btn-primary"><?php echo lang("buttons:update_picture") ?></a>
            </div>
            <div class="profile-info text-left">
                <?php echo sprintf(lang("profile:completed"), $profile_completed) ?>
                <div class="progress progress-bar-xs">
                    <div style="width: <?php echo sprintf("%d%%", $profile_completed) ?>;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
    	<div class="page-subtitle">
            <i class="fa fa-user pull-left text-info"></i>
            <h3 class="text-info"><?php echo lang('patient_cooperation_cards:general_subtitle') ?></h3>
            <p><?php echo lang('patient_cooperation_cards:general_ov_subtitle_helper') ?></p>
        </div>
        <div class="row margin-bottom-30 profile-grid">
        	<div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:mr_number_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo @$profile->mr_number ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:type_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo @$profile->type_name ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:name_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo @$profile->personal_name ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:gender_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo lang(sprintf("gender:%s", strtolower(@$profile->personal_gender))) ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:birth_date_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo strftime(lang("global:format_date"), strtotime(@$profile->personal_birth_date)) ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:age_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->personal_age_y || @$profile->personal_age_m || @$profile->personal_age_d) ? sprintf(lang("age:details"), @$profile->personal_age_y, @$profile->personal_age_m, @$profile->personal_age_d) : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:nationality_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->personal_nationality) ? @$profile->personal_nationality : "n/a" ?></div>
                </div>
            </div>
        </div>
        
        <div class="page-subtitle">
            <i class="fa fa-map-marker pull-left text-info"></i>
            <h3 class="text-info"><?php echo lang('patient_cooperation_cards:address_subtitle') ?></h3>
            <p><?php echo lang('patient_cooperation_cards:address_ov_subtitle_helper') ?></p>
        </div>
        <div class="row margin-bottom-30 profile-grid">
        	<div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:address_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo @$profile->personal_address ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:area_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->area_name) ? @$profile->area_name : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:district_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->district_name) ? @$profile->district_name : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:county_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->county_name) ? @$profile->county_name : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:province_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->province_name) ? @$profile->province_name : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:country_label') ?> :</strong></div>
                    <div class="col-md-8">Indonesia</div>
                </div>
            </div>
        </div>
        
        <div class="page-subtitle">
            <i class="fa fa-phone pull-left text-info"></i>
            <h3 class="text-info"><?php echo lang('patient_cooperation_cards:contact_subtitle') ?></h3>
            <p><?php echo lang('patient_cooperation_cards:contact_ov_subtitle_helper') ?></p>
        </div>
        <div class="row margin-bottom-30 profile-grid">
        	<div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:phone_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->phone_number) ? @$profile->phone_number : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:email_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->email_address) ? @$profile->email_address : "n/a" ?></div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="row">
                	<div class="col-md-4 caption"><strong><?php echo lang('patient_cooperation_cards:profession_label') ?> :</strong></div>
                    <div class="col-md-8"><?php echo (@$profile->personal_profession) ? @$profile->personal_profession : "n/a" ?></div>
                </div>
            </div>
        </div>
    </div>
</div>