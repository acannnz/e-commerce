<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info"><?php echo lang('edit_profile_text')?></h3>
            <p><small>(<?php echo $this->simple_login->get_username()?>)</small></p>
        </div>
        <div class="col-md-6">
            <a href="<?php echo base_url("") ?>" title="<?php echo lang('buttons:back') ?>" class="btn btn-default pull-right"><i class="fa fa-chevron-left"></i> <span><?php echo lang('buttons:back') ?></span></a>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
    	<?php $this->load->view( "profile/profile/profile" ) ?>
        <?php /*$this->load->view( "profile/profile/avatar" )*/ ?>        
    </div>
    <div class="col-md-4">
    	<?php $this->load->view( "profile/profile/username" ) ?>
        <?php $this->load->view( "profile/profile/email" ) ?>        
    </div>
    <div class="col-md-4">
    	<?php $this->load->view( "profile/profile/password" ) ?>
    </div>
</div>





    