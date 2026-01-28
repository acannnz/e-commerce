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
                    
                    <h5 class="sidebartitle"><?php echo lang('nav:family_folder'); ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                        <li><a href="<?php echo site_url('folder/family'); ?>"><i class="fa fa-group"></i> <span><?php echo lang('nav:family_manage'); ?></span></a></li>
                        <li><a href="<?php echo site_url('folder/family/reports'); ?>"><i class="fa fa-clipboard"></i> <span><?php echo lang('nav:reports'); ?></span></a></li>
                        <li><a href="<?php echo site_url('folder/family/help'); ?>"><i class="fa fa-life-ring"></i> <span><?php echo lang('nav:help'); ?></span></a></li>
                    </ul>
                </div>
