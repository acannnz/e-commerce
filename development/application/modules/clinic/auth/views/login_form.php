<?php
$login = array(
		'name'	=> 'login',
		'class'	=> 'form-control input-lg',
		'placeholder' => lang('username'),
		'value' => set_value('login'),
		'maxlength'	=> 80,
		'size'	=> 30,
	);
if ($login_by_username AND $login_by_email) 
{
	$login_label = 'Email or Username';
} else if ($login_by_username) 
{
	$login_label = 'Username';
} else 
{
	$login_label = 'Email';
}
$password = array(
		'name'	=> 'password',
		'id'	=> 'inputPassword',
		'placeholder' => lang('password'),
		'size'	=> 30,
		'class' => 'form-control input-lg'
	);
$remember = array(
		'name'	=> 'remember',
		'id'	=> 'remember',
		'value'	=> 1,
		'checked'	=> set_value('remember'),
	);
$captcha = array(
		'name'	=> 'captcha',
		'id'	=> 'captcha',
		'maxlength'	=> 8,
	);
?>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">

    <div class="container aside-xxl">
    	<?php /*?><a class="navbar-brand block bg-light" href="<?php echo base_url()?>"><?php echo $this->config->item('company_name')?></a><?php */?>  
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center"> 
                <strong><?php echo lang('welcome_to')?> <?php echo $this->config->item('company_name')?></strong> <?php  echo modules::run('aside/flash_msg'); ?>  
            </header>
    
            <?php echo form_open($this->uri->uri_string(), array('class' => 'panel-body wrapper-lg')); ?>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('email_user')?></label>
                    <?php echo form_input($login); ?>
                    <span style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('password')?></label>
                    <?php echo form_password($password); ?>
                    <span style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
                </div>
        <?php if ($show_captcha) {
            if ($use_recaptcha) { ?>
        <tr>
            <td colspan="2">
                <div id="recaptcha_image"></div>
            </td>
            <td>
                <a href="javascript:Recaptcha.reload()"><?php echo lang('get_another_captcha')?></a>
                <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><?php echo lang('get_an_audio_captcha')?></a></div>
                <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')"><?php echo lang('get_an_image_captcha')?></a></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="recaptcha_only_if_image"><?php echo lang('enter_the_words_above')?></div>
                <div class="recaptcha_only_if_audio"><?php echo lang('enter_the_numbers_you_hear')?></div>
            </td>
            <td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
            <td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
            <?php echo $recaptcha_html; ?>
        </tr>
        <?php } else { ?>
        <tr>
            <td colspan="3">
                <p><?php echo lang('enter_the_code_exactly')?></p>
                <?php echo $captcha_html; ?>
            </td>
        </tr>
        <tr>
            <td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
            <td><?php echo form_input($captcha); ?></td>
            <td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
        </tr>
        <?php }
        } ?>
    
        <div class="checkbox">
            <label><?php echo form_checkbox($remember); ?> <?php echo lang('this_is_my_computer')?></a></label>
        </div>
        <a href="<?php echo base_url()?>auth/forgot_password" class="pull-right m-t-xs"><small><?php echo lang('forgot_password')?></small></a> 
        <button type="submit" class="btn btn-danger"><?php echo lang('sign_in')?></button>
        <div class="line line-dashed">
        </div> 
        <?php if (config_item('allow_client_registration') == 'TRUE'){ ?>
        <p class="text-muted text-center"><small><?php echo lang('do_not_have_an_account')?></small></p> 
        <a href="<?php echo base_url()?>auth/register/" class="btn btn-success btn-block"><?php echo lang('get_your_account')?></a>
        <?php } ?>
                
    <?php echo form_close(); ?>
    
     </section>
	</div> 
	</section>
