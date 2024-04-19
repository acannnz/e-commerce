<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
	<div class="col-sm-10 col-sm-offset-1 col-xs-12">
    	<div class="row">
			<?php foreach($user_role as $role):?>
        	<div class="col-sm-4 col-xs-12">
                <a href="<?php echo base_url( $role ) ?>" class="tile tile-default">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <h3><?php echo lang("nav:{$role}") ?></h3>
                </a> 
            </div>
			<?php endforeach;?>
        </div>
    </div>    
</div>
