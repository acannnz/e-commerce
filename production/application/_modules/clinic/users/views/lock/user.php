<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="first-screen">
    <div class="clock">
        <div id="dev-clock"></div>
    </div>
    <div class="date"></div>
    <div class="lock-button">
        <a href="#" class="btn btn-default"><i class="fa fa-lock"></i></a>
    </div>
    {{ template.partials.footer }}
</div>
<div class="second-screen">
    <?php echo form_open( base_url("users/unlock"), array("id" => "form_user_lock", "name" => "form_user_lock") ) ?>
        <div class="photo">
            <img src="<?php echo base_url( "resource/images" ) ?>/<?php echo @$user->avatar ?>">
        </div>
        <div class="title"><?php echo @$user->fullname ?> <span><?php echo @$user->role_name ?></span></div>
        <div class="elements">
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="hidden" name="remember" value="1">
                        <?php /*?><?php if( 1 == $login_by_email ): ?><input type="hidden" name="login" value="<?php echo @$user->email ?>">
                        <?php else: ?><input type="hidden" name="login" value="<?php echo @$user->username ?>">                    
                        <?php endif ?><?php */?>
                        <input type="hidden" name="login" value="<?php echo @$user->username ?>">
                        <input type="password" name="password" class="form-control">
                    </div>                              
                </div>
            </div>f
            <div class="form-group text-center">
                <div class="col-md-8 col-md-offset-2">
                    <button type="submit" class="btn btn-danger btn-block"><?php echo lang("buttons:unlock") ?></button>
                </div>
            </div>
        </div>
        {{ template.partials.footer }}
    <?php echo form_close() ?>
</div>




