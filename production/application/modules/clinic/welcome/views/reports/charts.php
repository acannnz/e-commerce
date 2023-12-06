<?php if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="widget-tabbed">
    <ul class="widget-tabs widget-tabs-three">
        <li class="active"><a href="#tasks"><?php echo lang("reports:tasks_tab") ?></a></li>                                        
        <li><a href="#charts"><?php echo lang("reports:charts_tab") ?></a></li>                                        
    </ul>                                    
    <div class="widget-tab list-tasks active" id="tasks">
        <?php if( $total_tasks ): ?>        
        <ul class="timeline-simple">
        	<?php foreach($task_items as $item): ?>
            <?php 
			$now = new DateTime("NOW");
			$age = $now->diff(new DateTime(strftime("%Y-%m-%d %H:%M:%S", $item->created_at)));
			if( $age->h ){ $age_text = sprintf(lang("reports:n_hours_ago"), $age->h); }
			else if( $age->i ){ $age_text = sprintf(lang("reports:n_minutes_ago"), $age->i); }
			else if( $age->s ){ $age_text = sprintf(lang("reports:n_seconds_ago"), $age->s); }
			?>
            <li class="<?php echo ((1 == $item->state) ? "warning" : ((2 == $item->state) ? "success" : "default")) ?>">
                <span class="timeline-simple-date"><?php echo $age_text ?></span>
                <a href="<?php echo ((2 == $item->state) ? base_url( "examinations/proceed/{$item->registration_number}" ) : "javascript:;") ?>" class="text-default"><strong><?php echo $item->registration_number ?></strong> - <?php echo $item->personal_name ?>, <?php echo lang("reports:".strtolower($item->personal_gender)) ?>, <?php echo (int) $item->personal_age ?>.</a>
            </li>
            <?php endforeach ?> 
        </ul>
        <?php else: ?>
        <div class="wrapper">
            <div class="alert alert-info alert-dismissible" role="alert">
                <strong>Heads up!</strong> <?php echo lang("reports:no_task_today") ?>
            </div>
        </div>
        <?php endif ?>
    </div>
    <div class="widget-tab" id="charts">
    	<?php if( $total_charts ): ?>                                                
        <ul class="timeline-simple">
            <?php foreach($chart_items as $item): ?>
            <?php 
			$now = new DateTime("NOW");
			$age = $now->diff(new DateTime(strftime("%Y-%m-%d %H:%M:%S", $item->created_at)));
			if( $age->h ){ $age_text = sprintf(lang("reports:n_hours_ago"), $age->h); }
			else if( $age->i ){ $age_text = sprintf(lang("reports:n_minutes_ago"), $age->i); }
			else if( $age->s ){ $age_text = sprintf(lang("reports:n_seconds_ago"), $age->s); }
			?>
            <li class="<?php echo ((1 == $item->state) ? "warning" : ((2 == $item->state) ? "success" : "default")) ?>">
                <span class="timeline-simple-date"><?php echo $age_text ?></span>
                <a href="<?php echo base_url( "charts/chart/edit_confirm" ) ?>?chart_num=<?php echo $item->chart_number ?>" data-toggle="ajax-modal" class="text-default"><strong><?php echo $item->chart_number ?></strong> - <?php echo $item->personal_name ?>, <?php echo lang("reports:".strtolower($item->personal_gender)) ?>, <?php echo (int) $item->personal_age ?>.</a>
            </li>
            <?php endforeach ?>
        </ul>
        <?php else: ?>
        <div class="wrapper">
        	<div class="alert alert-info alert-dismissible" role="alert">
				<strong>Heads up!</strong> <?php echo lang("reports:no_chart_today") ?>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>