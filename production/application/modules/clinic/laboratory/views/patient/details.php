<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <h4 class="chart-details-title text-success"><?php echo lang("registrations:personal_subtitle") ?></h4>
        <hr>
		<dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:mr_number_label") ?> : </dt>
            <dd><?php echo ( @$item->mr_number ) ? @$item->mr_number : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:name_label") ?> : </dt>
            <dd><?php echo ( @$item->personal_name ) ? @$item->personal_name : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:gender_label") ?> : </dt>
            <dd><?php echo ( @$item->personal_gender ) ? lang("global:".strtolower(@$item->personal_gender)) : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:birth_date_label") ?> : </dt>
            <dd><?php echo ( @$item->personal_birth_date ) ? strftime(lang("global:format_date"), strtotime(@$item->personal_birth_date)) : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:age_label") ?> : </dt>
            <dd><?php echo (int) @$item->personal_age ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:nationality_label") ?> : </dt>
            <dd><?php echo ( @$item->personal_nationality ) ? @$item->personal_nationality : "n/a" ?></dd>
        </dl>
    </div>
    <div class="col-md-6 col-sm-12">
    	<h4 class="chart-details-title text-success"><?php echo lang("registrations:contact_subtitle") ?></h4>
        <hr>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:address_label") ?> : </dt>
            <dd><?php echo @format_address(@$item->personal_address, @$item->area_name, @$item->district_name, @$item->county_name) ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:phone_label") ?> : </dt>
            <dd><?php echo ( @$item->phone_number ) ? @$item->phone_number : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:email_label") ?> : </dt>
            <dd><?php echo ( @$item->email_address ) ? @$item->email_address : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:profession_label") ?> : </dt>
            <dd><?php echo ( @$item->personal_profession ) ? @$item->personal_profession : "n/a" ?></dd>
        </dl>
    </div>
</div>
<?php if( (1 <= @$item->state) && (in_array(@$user_role, array('doctor', 'nurse', 'admin'))) ): ?>
<div class="row">
    <div class="col-md-6 col-sm-12"></div>    
    <div class="col-md-3 col-sm-12"></div>
    <div class="col-md-3 col-sm-12">
        <?php if( 1 == @$item->state ): ?>
        <a href="<?php echo base_url("registrations/proceed/{$item->registration_number}") ?>" data-toggle="ajax-modal" title="<?php echo lang( "registrations:buttons_exam_helper" ) ?>" class="btn btn-block btn-success"><?php echo lang( "buttons:exam" ) ?></a>
    	<?php else: ?>
        <a href="<?php echo base_url("registrations/proceed/{$item->registration_number}") ?>" data-toggle="ajax-modal" title="<?php echo lang( "registrations:buttons_change_chart_helper" ) ?>" class="btn btn-block btn-warning"><?php echo lang( "buttons:change_chart" ) ?></a>
        <?php endif ?>
    </div>
</div>
<?php endif ?> 



