<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="table-responsive">
    <table id="table-activities" class="table">
        <thead>
            <tr>	
                <th><?php echo lang('activity_date')?></th>	
                <th><?php echo lang('user')?></th>			
                <th><?php echo lang('module')?></th>
                <th><?php echo lang('activity')?> </th>                
            </tr> 
        </thead> 
        <tbody>
            <?php if (!empty($activities)) { foreach ($activities as $key => $a) { ?>
            <tr>
                <td><i class="fa fa-clock-o"></i> <?php echo $a->activity_date?></td>
                <td><?php echo Applib::get_table_field(Applib::$profile_table,array('user_id'=>$a->user),'fullname')?></td>
                <td><?php echo strtoupper($a->module)?></td>
                <td>
                    <?php 
                    if (lang($a->activity) != '') {
                        if (!empty($a->value1)) {
                            if (!empty($a->value2)){
                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>', '<em>'.$a->value2.'</em>');
                            } else {
                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>');
                            }
                        } else { echo lang($a->activity); }
                    } else { echo $a->activity; } 
                    ?> 
                </td>            
            </tr>
            <?php } } ?>
    	</tbody>
    </table>
</div>