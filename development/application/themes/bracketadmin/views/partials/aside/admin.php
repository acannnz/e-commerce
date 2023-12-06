<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!isset($active_menu)){ $active_menu = ''; }
?>
<div class="logopanel text-center">
	<h1><span>[</span> <?php echo config_item('company_name') ?> <span>]</span></h1>
</div>

<div class="leftpanelinner">
	<div class="visible-xs ">   
		<div class="media userlogged">
			<img src="{{ base_theme }}/bracketadmin/images/no-user-avatar.jpg" alt="" class="media-object">
			<div class="media-body">
				<h4><?php echo $this->session->userdata('username')?></h4>
				<?php /*?><span>"Life is so..."</span><?php */?>
			</div>
		</div>

		<h5 class="sidebartitle actitle">Account</h5>
		<ul class="nav nav-pills nav-stacked nav-bracket mb30">
			<?php /*?><li><a href="javascript:;" data-action-url="<?php echo site_url("system/users/edit") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:my_profile'); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo lang('nav:my_profile'); ?></a></li>
			<li><a href="<?php echo site_url("help/ticket") ?>"><i class="glyphicon glyphicon-question-sign"></i> <?php echo lang('nav:help'); ?></a></li><?php */?>
			<li><a href="javascript:;" data-action-url="<?php echo site_url("auth/logout") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> <?php echo lang('nav:logout'); ?></a></li>
		</ul>
	</div>

	<h5 class="sidebartitle"><?php echo lang('nav:heading_panel'); ?></h5>
	<ul class="nav nav-pills nav-stacked nav-bracket">
		<li><a href="<?php echo site_url(''); ?>"><i class="fa fa-dashboard"></i> <span><?php echo lang('nav:dashboard'); ?></span></a></li>
	</ul>
	
	<h5 class="sidebartitle"><?php echo 'Setup Master'//lang('nav:family_folder'); ?></h5>
	<ul class="nav nav-pills nav-stacked nav-bracket">
		<li class="nav-parent">
			<a href=""><i class="fa fa-database" aria-hidden="true"></i> <span><?php echo 'Setup Jasa'//lang('nav:references'); ?></span></a>
			<ul class="children">
				<li><a href="<?php echo site_url('service'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Jasa'//lang('nav:family_manage'); ?></span></a></li>                        
				<li><a href="<?php echo site_url('service/service_group'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Grup Jasa'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/service_category'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Kategori Jasa'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/service_component'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Komponen Jasa'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/classes'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Kelas'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/devices'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Mapping Alat'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/icd'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'ICD'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/gapah'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Gapah'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('service/injury_type'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Jenis Luka'//lang('nav:family_manage'); ?></span></a></li>
			</ul>
		</li>
		<li class="nav-parent">
			<a href=""><i class="fa fa-building-o" aria-hidden="true"></i> <span><?php echo 'Setup Vendor'//lang('nav:references'); ?></span></a>
			<ul class="children">
				<li><a href="<?php echo site_url('vendor'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Vendor'//lang('nav:family_manage'); ?></span></a></li>                        
				<li><a href="<?php echo site_url('vendor/specialist'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Spesialis'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('vendor/category'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Kategori Vendor'//lang('nav:family_manage'); ?></span></a></li>
				<!-- <li><a href="<?php echo site_url('vendor/hobby'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Hobi'//lang('nav:family_manage'); ?></span></a></li> -->
				<li><a href="<?php echo site_url('vendor/merchan'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Merchan'//lang('nav:family_manage'); ?></span></a></li>	
			</ul>
		</li>
		
		<li class="nav-parent">
			<a href=""><i class="fa fa-handshake-o" aria-hidden="true"></i> <span><?php echo 'Setup Marketing'//lang('nav:references'); ?></span></a>
			<ul class="children">
				<li><a href="<?php echo site_url('marketing/customer'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Customer'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('marketing/category'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Kategori Customer'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('marketing/contract'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Kontrak Kerjasama'//lang('nav:family_manage'); ?></span></a></li>
			</ul>
		</li>
	
		<li class="nav-parent">
			<a href=""><i class="fa fa-cogs" aria-hidden="true"></i> <span><?php echo 'Setup Other'//lang('nav:references'); ?></span></a>
			<ul class="children">
				<li><a href="<?php echo site_url('others/service_type'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Jenis Layanan'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('others/section'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Section'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('others/section_group'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Kelompok Section'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('others/payment_type'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Tipe Pembayaran'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('others/discount'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Diskon'//lang('nav:family_manage'); ?></span></a></li>
				<li><a href="<?php echo site_url('others/schedule_time'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Jam Praktek'//lang('nav:family_manage'); ?></span></a></li>                        
				<?php /*?><li><a href="<?php echo site_url('schedules'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'Jadwal Praktek'//lang('nav:family_manage'); ?></span></a></li><?php */?>
				<li><a href="<?php echo site_url('others/user'); ?>"><i class="fa fa-circle"></i> <span><?php echo 'User'//lang('nav:family_manage'); ?></span></a></li>                        
			</ul>
		</li>
	</ul>
</div>
