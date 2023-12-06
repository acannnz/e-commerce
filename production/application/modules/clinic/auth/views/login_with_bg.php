<?php
$login = array(
		'name'	=> 'login',
		'class'	=> 'form-control uname',
		'placeholder' => 'Username',
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
		'placeholder' => 'Password',
		'id'	=> 'inputPassword',
		'size'	=> 30,
		'class' => 'form-control pword'
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
<style>
	.bg-bubbles {
		position: unset;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1;
	}

	.bg-bubbles li {
		position: absolute;
		list-style: none;
		display: block;
		width: 40px;
		height: 40px;
		background-color: rgba(255, 255, 255, 0.15);
		bottom: -160px;
		-webkit-animation: square 25s infinite;
		animation: square 25s infinite;
		-webkit-transition-timing-function: linear;
		transition-timing-function: linear;
	}

	.bg-bubbles li:nth-child(1) {
		left: 5%;
	}

	.bg-bubbles li:nth-child(2) {
		left: 2%;
		width: 70px;
		height: 70px;
		-webkit-animation-delay: 2s;
		animation-delay: 2s;
		-webkit-animation-duration: 17s;
		animation-duration: 17s;
	}

	.bg-bubbles li:nth-child(3) {
		left: 20%;
		-webkit-animation-delay: 4s;
		animation-delay: 4s;
	}

	.bg-bubbles li:nth-child(4) {
		left: 10%;
		width: 60px;
		height: 60px;
		-webkit-animation-duration: 22s;
		animation-duration: 22s;
		background-color: rgba(255, 255, 255, 0.25);
	}

	.bg-bubbles li:nth-child(5) {
		left: 50%;
	}

	.bg-bubbles li:nth-child(6) {
		left: 80%;
		width: 120px;
		height: 120px;
		-webkit-animation-delay: 3s;
		animation-delay: 3s;
		background-color: rgba(255, 255, 255, 0.2);
	}

	.bg-bubbles li:nth-child(7) {
		left: 25%;
		width: 120px;
		height: 120px;
		-webkit-animation-delay: 7s;
		animation-delay: 7s;
	}

	.bg-bubbles li:nth-child(8) {
		left: 55%;
		width: 20px;
		height: 20px;
		-webkit-animation-delay: 15s;
		animation-delay: 15s;
		-webkit-animation-duration: 40s;
		animation-duration: 40s;
	}

	.bg-bubbles li:nth-child(9) {
		left: 25%;
		width: 10px;
		height: 10px;
		-webkit-animation-delay: 2s;
		animation-delay: 2s;
		-webkit-animation-duration: 40s;
		animation-duration: 40s;
		background-color: rgba(255, 255, 255, 0.3);
	}

	.bg-bubbles li:nth-child(10) {
		left: 90%;
		width: 160px;
		height: 160px;
		-webkit-animation-delay: 11s;
		animation-delay: 11s;
	}

	@-webkit-keyframes square {
		0% {
			-webkit-transform: translateY(0);
			transform: translateY(0);
		}

		100% {
			-webkit-transform: translateY(-700px) rotate(600deg);
			transform: translateY(-700px) rotate(600deg);
		}
	}

	@keyframes square {
		0% {
			-webkit-transform: translateY(0);
			transform: translateY(0);
		}

		100% {
			-webkit-transform: translateY(-700px) rotate(600deg);
			transform: translateY(-700px) rotate(600deg);
		}
	}
</style>
<?php echo form_open('login', array('class' => '')); ?>
	<?php
		if($this->session->flashdata('message')){ ?>
		<?php if ($this->session->flashdata('response_status') == 'success') { $alert_type = 'success'; }else{ $alert_type = 'danger'; } ?>
		<div class="alert alert-<?php echo $alert_type?>"> 
			<button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-info-sign"></i>
			<?php echo $this->session->flashdata('message');?>
		</div>
	<?php } ?>  
	<div class="form-group">
    	<div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <?php echo form_input([
					'name'	=> 'login',
					'class'	=> 'form-control',
					'placeholder' => 'Username',
					'value' => set_value('login'),
					'maxlength'	=> 80,
					'size'	=> 30
				]); ?>            
    	</div>
    </div>
    <div class="form-group">
    	<div class="input-group">
            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
            <?php echo form_password([
					'name'	=> 'password',
					'placeholder' => 'Password',
					'id'	=> 'inputPassword',
					'size'	=> 30,
					'class' => 'form-control'
				]); ?>            
    	</div>
    </div>
	<?php if(!empty($option_shift)): ?>
    <div class="form-group">
		<div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			<select id="shift_id" name="shift_id" class="form-control" style="color:#999999 !important" required>
				<option value=""><i class="fa fa-lock"></i> Pilih Shift</option>
				<?php foreach($option_shift as $row): ?>
				<option value="<?php echo $row->IDShift ?>"><?php echo $row->Deskripsi ?></option>
				<?php endforeach; ?>
			</select>
		</div>
    </div>
	<?php endif; ?>
	
	<?php /*
    <div class="form-group">
		<?php echo form_input($login); ?>            
    </div>
    <div class="form-group">
		<?php echo form_password($password); ?>            
    </div>
	<?php if(!empty($option_shift)): ?>
    <div class="form-group">
        <select id="shift_id" name="shift_id" class="form-control" required>
            <option value=""><i class="fa fa-lock"></i> Pilih Shift</option>
            <?php foreach($option_shift as $row): ?>
            <option value="<?php echo $row->IDShift ?>"><?php echo $row->Deskripsi ?></option>
            <?php endforeach; ?>
        </select>
    </div>
	<?php endif; ?>
	*/ ?>
    <div class="form-group no-border margin-top-10">
        <button type="submit" class="btn btn-success btn-block"><?php echo lang('user:signin')?></button>
    </div>
    
    <?php /*?><div class="form-group" style="border-bottom:none;">
        <div class="checkbox checkbox-inline">
            <?php echo form_checkbox($remember); ?> <label for="remember" style="color:#ccc;"><?php echo lang('this_is_my_computer')?></label>
        </div>
    </div><?php */?>
    <?php /*?><p><a href="<?php echo base_url()?>auth/forgot_password" class=""><?php echo lang('forgot_password')?></a></p><?php */?> 
    
<?php echo form_close(); ?>

<?php /*?><?php if (config_item('allow_client_registration') == 'TRUE'): ?>
<div class="title"><?php echo lang('do_not_have_an_account')?></div>
<div class="form-group no-border">
	<a href="<?php echo base_url()?>auth/register/" class="btn btn-warning btn-block"><?php echo lang('get_your_account')?></a>
</div>
<?php endif ?><?php */?>

<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>