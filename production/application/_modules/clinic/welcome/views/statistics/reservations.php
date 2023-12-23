<?php if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="dev-col">
    <div class="dev-widget dev-widget-transparent dev-widget-success">
        <h2><?php echo lang("reports:total_reservations_subtitle") ?></h2>
        <p><?php echo lang("reports:total_reservations_helper") ?></p>
        <div class="dev-stat">
        	<span class="counter"><?php echo (int) $total_reservations_year ?></span> 
            <sup class="text-success">of <?php echo (int) $total_reservations ?></sup>
        </div>
        <div class="progress progress-bar-xs">
        	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo (float) $total_reservations_percentage ?>%;"></div>
        </div>
        <p><?php echo lang("reports:dashboard_reservations_helper") ?></p> <a href="<?php echo base_url('reservations') ?>" class="dev-drop"><?php echo lang('buttons:take_a_closer') ?> <span class="fa fa-angle-right pull-right"></span></a> 
    </div>
</div>

