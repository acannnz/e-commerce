<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
	'id' => 'form_user',
	'name' => 'form_user',
	'rule' => 'form',
	'class' => ''
]); ?>
<div class="row">
    <div class="page-subtitle">
        <h3><i class="fa fa-user"></i> <?php echo 'Detail Profil Pengguna'?></h3>
    </div>
	<div class="col-md-4 offset-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                    <?php echo validation_errors(); ?>    
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('full_name')?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="f[Nama_Asli]" id="Nama_Asli" value="<?php echo @$user_auth->Nama_Asli?>" required>
                    </div>   
                    <div class="form-group">
                        <label class="control-label"><?php echo 'Nama Singkat' ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="f[Nama_Singkat]" id="Nama_Singkat" value="<?php echo @$user_auth->Nama_Singkat?>" required>
                    </div>   
            </div>
        </div>
    </div>
    <div class="col-md-4 offset-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label"><?php echo 'Username' ?></label>
                        <input type="text" class="form-control" name="f[Username]" id="Username" placeholder="<?php echo lang('username')?>" value="<?php echo @$user_auth->Username?>" required>
                    </div> 
                    <div class="form-group">
                        <label><?php echo lang('old_password')?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="c[OldPasswordWeb]" id="PasswordWebOld" placeholder="<?php echo lang('old_password')?>" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo 'Password Baru'?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="f[PasswordWeb]" id="PasswordWeb" placeholder="<?php echo 'Password Baru'?>" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('confirm_password')?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="c[PasswordWeb]" id="PasswordWebConfirm" placeholder="<?php echo lang('confirm_password')?>" required>
                    </div>
                    <div class="form-group">    
                        <button type="submit" class="btn btn-sm btn-danger pull-right"><i class="fa fa-check"></i> <?php echo lang('update_profile')?></button>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var _form = $("#form_user");

		$(document).ready(function(e) {

			_form.on("submit", function(e) {
				e.preventDefault();
				$.post(_form.prop("action"), _form.serializeArray(), function(response, status, xhr) {
					if ("error" == response.status) {
						$.alert_error(response.message);
						return false
					}
					$.alert_success(response.message);
					setTimeout(function() {
                        if(confirm('Anda akan logout, silahkan login ulang!')){
                            document.location.href = "<?php echo base_url('logout'); ?>";
                        }else{
                            document.location.href = "<?php echo base_url('logout'); ?>";
                        }
					}, 300);

				});
			});



		});

	})(jQuery);
	//]]>
</script>




    