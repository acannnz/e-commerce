<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

?>
			<a class="menutoggle"><i class="fa fa-bars"></i></a>

            <?php  /* echo form_open($search_action, ['id' => 'form_search', 'name' => 'form_search', 'rule' => 'form', 'class' => 'searchform']); ?>
            <input type="text" name="s[words]" class="form-control" placeholder="<?php echo lang('search_here'); ?>">
			<?php echo form_close() */ ?>
            
            <div class="header-right">
            	<ul class="headermenu">
                	<li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ base_theme }}/bracketadmin/images/no-user-avatar.jpg" alt="Nama User" />
                                <?php echo $this->session->userdata('username')?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <!-- <li><a href="javascript:;" data-action-url="<?php echo base_url("system/users/edit") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:my_profile'); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo lang('nav:my_profile'); ?></a></li> -->
                               <?php /*?> <li><a href="<?php echo base_url("help/ticket") ?>"><i class="glyphicon glyphicon-question-sign"></i> <?php echo lang('nav:help'); ?></a></li><?php */?>
                                <li><a href="javascript:;" data-action-url="<?php echo base_url("auth/logout") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> <?php echo lang('nav:logout'); ?></a></li>
                            </ul>
                        </div>
                    </li>
                    <!-- <li>
                        <button id="chatview" class="btn btn-default tp-icon chat-icon">
                            <i class="glyphicon glyphicon-comment"></i>
                        </button>
                    </li> -->
                </ul>
            </div>
        
        
