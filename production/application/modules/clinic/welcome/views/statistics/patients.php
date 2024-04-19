<?php if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="dev-col">
    <div class="dev-widget dev-widget-transparent">
        <h2><?php echo lang("reports:total_patients_subtitle") ?></h2>
        <p><?php echo lang("reports:total_patients_helper") ?></p>
        <div class="dev-stat">
        	<span class="counter"><?php echo (int) $total_patients_year ?></span> 
            <sup class="text-info">of <?php echo (int) $total_patients ?></sup>
        </div>
        <div class="progress progress-bar-xs">
        	<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo (float) $total_patients_percentage ?>%;"></div>
        </div>
    	<p><?php echo lang("reports:dashboard_patients_helper") ?></p> <a href="<?php echo base_url('common/patients') ?>" class="dev-drop"><?php echo lang('buttons:take_a_closer') ?> <span class="fa fa-angle-right pull-right"></span></a> 
    </div>
</div>

