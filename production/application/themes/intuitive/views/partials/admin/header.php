<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<div class="dph-logo">
    <a href="<?php echo base_url("") ?>">
    	<i class="logo admin-logo"><?php echo config_item( 'company_name' ) ?> <?php echo config_item( 'version' ) ?></i>
        <em><?php echo config_item( 'company_name' ) ?></em>
    </a>
    <a class="dev-page-sidebar-collapse">
        <div class="dev-page-sidebar-collapse-icon">
            <span class="line-one"></span>
            <span class="line-two"></span>
            <span class="line-three"></span>
        </div>
    </a>
</div>

<ul class="dph-buttons pull-right">                    
    <?php /*?><li class="dph-button-stuck">
        <a href="javascript:;" class="dev-page-search-toggle tip">
            <div class="dev-page-search-toggle-icon">
                <span class="circle"></span>
                <span class="line"></span>
            </div>
        </a>
    </li><?php */?>    
	<?php if(!empty($this->session->userdata('outpatient'))): ?>
	<li class="dph-button-stuck">
    	<a href="<?php echo base_url('set-medics/outpatient') ?>" title="Pengaturan Petugas Medis Rawat Jalan" data-toggle="ajax-modal">
        	<i class="fa fa-wheelchair"></i>
        </a>
    </li>
	<?php endif; ?>
	<?php if(!empty($this->session->userdata('inpatient'))): ?>
    <li class="dph-button-stuck">
    	<a href="<?php echo base_url('set-medics/inpatient') ?>" title="Pengaturan Petugas Medis Rawat Inap" data-toggle="ajax-modal">
        	<i class="fa fa-bed"></i>
        </a>
    </li>
	<?php endif; ?>
	<?php if(!empty($this->session->userdata('laboratory'))): ?>
	<li class="dph-button-stuck">
    	<a href="<?php echo base_url('set-medics/laboratory') ?>" title="Pengaturan Petugas Medis Penunjang" data-toggle="ajax-modal">
        	<i class="fa fa-flask"></i>
        </a>
    </li>
	<?php endif; ?>
	<?php if(!empty($this->session->userdata('pharmacy'))): ?>
	<li class="dph-button-stuck">
    	<a href="<?php echo base_url('set-pharmacy') ?>" title="Pengaturan Farmasi" data-toggle="ajax-modal">
        	<i class="fa fa-medkit"></i>
        </a>
    </li>
	<?php endif; ?>
    <li class="dph-button-stuck">
    	<a href="<?php echo base_url('logout') ?>" title="<?php echo lang("nav:signout") ?>" data-toggle="ajax-modal">
        	<i class="fa fa-sign-out"></i>
        </a>
    </li>
                        
    <?php /*?><li class="dph-button-stuck">
        <a href="javascript:;" class="dev-page-rightbar-toggle">
            <div class="dev-page-rightbar-toggle-icon">
                <span class="line-one"></span>
                <span class="line-two"></span>
            </div>
        </a>
    </li><?php */?>
</ul>