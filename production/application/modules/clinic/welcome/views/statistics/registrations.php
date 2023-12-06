<?php if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="dev-col">
    <div class="dev-widget dev-widget-transparent dev-widget-danger">
        <h2><?php echo lang("reports:total_registrations_subtitle") ?></h2>
        <p><?php echo lang("reports:total_registrations_helper") ?></p>
        <div class="dev-stat">
        	<span class="counter"><?php echo (int) $total_registrations_year ?></span> 
            <sup class="text-danger">of <?php echo (int) $total_registrations ?></sup>
        </div>
        <div class="progress progress-bar-xs">
        	<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo (int) $total_registrations_percentage ?>%;"></div>
        </div>
        <p><?php echo lang("reports:dashboard_registrations_helper") ?></p> <a href="<?php echo base_url('registrations') ?>" class="dev-drop">Take a closer look <span class="fa fa-angle-right pull-right"></span></a> 
	</div>
</div>