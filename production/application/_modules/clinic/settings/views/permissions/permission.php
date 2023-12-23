<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_permission", "name" => "form_settings_permission")); ?>
<div class="row">
	<div class="col-lg-4 col-md-12">
    	<div class="page-subtitle">
            <h3><?php echo lang( 'permissions:profile_subtitle' ) ?></h3>
            <p><?php echo lang( 'permissions:profile_subtitle_helper' ) ?></p>
        </div> 
        <div>
        	<span class="label label-info"><strong>N:</strong> <?php echo @$profile->fullname ?></span><br><br>
            <span class="label label-info"><strong>U:</strong> <?php echo @$account->username ?></span><br><br>
            <span class="label label-info"><strong>E:</strong> <?php echo @$account->email ?></span><br><br>
            <span class="label label-info"><strong>R:</strong> <?php echo @$role->role ?></span>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="page-subtitle">
            <h3><?php echo lang( 'permissions:permission_subtitle' ) ?></h3>
            <p><?php echo lang( 'permissions:permission_subtitle_helper' ) ?></p>
        </div> 
    	<?php foreach ($permissions as $key => $p): ?>
        <div class="form-group">
            <div class="checkbox checkbox-danger">
                <input type="hidden" value="off" name="<?php echo $p->name ?>" />
                <input type="checkbox" id="<?php echo $p->name ?>" name="<?php echo $p->name ?>" value="on"<?php if( array_key_exists($p->name, $user_permissions) ): ?> checked<?php endif ?>>
                <label for="<?php echo $p->name ?>"><i class="fa fa-code"></i> <b><?php echo ucfirst($p->name)?></b> - <small><?php echo $p->description?></small></label>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <?php endforeach ?>
    </div>
    <div class="col-lg-4 col-md-12">
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12 col-md-12">
    	<button class="btn btn-warning"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save_changes') ?></button>
    </div>
</div>
<?php echo form_close() ?>
