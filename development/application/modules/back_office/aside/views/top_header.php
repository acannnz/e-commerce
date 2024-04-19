
<header class="bg-dark header navbar navbar-fixed-top-xs">
	<div class="navbar-header">
		<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
			<i class="fa fa-bars"></i>
		</a>
		<a href="<?php echo base_url()?>" class="navbar-brand">

                    <?php $display = config_item('logo_or_icon'); ?>
			<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?php echo base_url()?>resource/images/<?php echo config_item('company_logo')?>" class="m-r-sm">
			<?php } elseif ($display == 'icon' || $display == 'icon_title') { ?>
			<i class="fa <?php echo config_item('site_icon')?>"></i>
			<?php } ?>
			<?php 
                        if ($display == 'logo_title' || $display == 'icon_title') {
                            if (config_item('company_name') != '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
                        }
                        ?>
		</a>
		<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
			<i class="fa fa-cog"></i>
		</a>
	</div>
	
	<ul class="nav navbar-nav navbar-right hidden-xs nav-user">
            
        <?php $role = $this->tank_auth->user_role($this->tank_auth->get_role_id()); ?>
        <?php $user_id = $this->tank_auth->get_user_id(); ?>
        <ul class="nav navbar-nav navbar-right hidden-xs nav-user">

            
            
		
		<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	<span class="thumb-sm avatar pull-left">

	<?php
	$user = $this->tank_auth->get_user_id();
	$user_email = Applib::login_info($user)->email;
	$gravatar_url = $this->applib->get_gravatar($user_email);
	 if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user),'use_gravatar') == 'Y'){ ?>
	<img src="<?php echo $gravatar_url?>" class="img-circle">
	<?php }else{ ?>
	<img src="<?php echo base_url()?>resource/avatar/<?php echo Applib::profile_info($user)->avatar?>" class="img-circle">
	<?php } ?>
	
	</span>
	<?php
	echo Applib::profile_info($user)->fullname 
	? Applib::profile_info($user)->fullname 
	: Applib::login_info($user)->username; 
	?> <b class="caret"></b>
</a>
<ul class="dropdown-menu animated fadeInRight">
	<li class="arrow top"></li>
	<li><a href="<?php echo base_url()?>profile/settings"><?php echo lang('settings')?></a></li>
	<li>
		<a href="<?php echo base_url()?>profile/activities">
			<span class="badge bg-danger pull-right">
			<?php echo $this->db->where('user',$user)->get(Applib::$activities_table)->num_rows(); ?>
			</span><?php echo lang('activities')?>
		</a>
	</li>
	<?php /*?><?php if ($role == 'admin'): ?>
	<li> <a href="<?php echo base_url()?>updates"><?php echo lang('updates')?></a> </li>
	<?php endif ?><?php */?>
	<li class="divider"></li>
	<li> <a href="<?php echo base_url()?>logout" ><?php echo lang('logout')?></a> </li>
</ul>
</li>
</ul>
</header>