<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!isset($active_menu)){ $active_menu = ''; }
?>
        		<div class="logopanel">
                    <h1><span>[</span> Kulhen <span>]</span></h1>
                </div>
                
                <div class="leftpanelinner">
                	<div class="visible-xs hidden-sm hidden-md hidden-lg">   
                        <div class="media userlogged">
                            <img src="{{ base_theme }}/bracketadmin/images/no-user-avatar.jpg" alt="" class="media-object">
                            <div class="media-body">
                                <h4>John Doe</h4>
                                <span>"Life is so..."</span>
                            </div>
                        </div>

                        <h5 class="sidebartitle actitle">Account</h5>
                        <ul class="nav nav-pills nav-stacked nav-bracket mb30">
                            <li><a href="javascript:;" data-action-url="<?php echo base_url("system/users/edit") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:my_profile'); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo lang('nav:my_profile'); ?></a></li>
                            <li><a href="<?php echo base_url("help/ticket") ?>"><i class="glyphicon glyphicon-question-sign"></i> <?php echo lang('nav:help'); ?></a></li>
                            <li><a href="javascript:;" data-action-url="<?php echo base_url("auth/logout") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> <?php echo lang('nav:logout'); ?></a></li>
                        </ul>
                    </div>

                    <h5 class="sidebartitle"><?php echo lang('nav_title:panel'); ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li><a href="<?php echo base_url(''); ?>"><i class="fa fa-dashboard"></i> <span><?php echo lang('nav:dashboard'); ?></span></a></li>
                    </ul>
                    
                    <h5 class="sidebartitle"><?php echo lang('nav_title:bpjs'); ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li class="nav-parent">
                        	<a href=""><i class="fa fa-clipboard" aria-hidden="true"></i> <span><?php echo lang('bpjs:sep'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo base_url('bpjs/sep/sep'); ?>"><i class="fa fa-qrcode" aria-hidden="true"></i> <?php echo lang('bpjs:sep'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/sep/sep_claim'); ?>"><i class="fa fa-line-chart" aria-hidden="true"></i> <?php echo lang('bpjs:claim_status'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/sep/sep_visit'); ?>"><i class="fa fa-exchange" aria-hidden="true"></i> <?php echo lang('bpjs:visits'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/sep/sep_service'); ?>"><i class="fa fa-heartbeat" aria-hidden="true"></i> <?php echo lang('bpjs:services'); ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                        	<a href=""><i class="fa fa-bed" aria-hidden="true"></i> <span><?php echo lang('bpjs:rooms'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo base_url('bpjs/room/room_type'); ?>"><i class="fa fa-bed" aria-hidden="true"></i> <?php echo lang('bpjs:room_types'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/room/room_availability'); ?>"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> <?php echo lang('bpjs:room_availabilities'); ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                        	<a href=""><i class="fa fa-database" aria-hidden="true"></i> <span><?php echo lang('bpjs:reference'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo base_url('bpjs/reference/card'); ?>"><i class="fa fa-address-card" aria-hidden="true"></i> <?php echo lang('bpjs:cards'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/reference/provider'); ?>"><i class="fa fa-hospital-o" aria-hidden="true"></i> <?php echo lang('bpjs:providers'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/reference/poly'); ?>"><i class="fa fa-heart-o" aria-hidden="true"></i> <?php echo lang('bpjs:poly'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/reference/icd'); ?>"><i class="fa fa-stethoscope" aria-hidden="true"></i> <?php echo lang('bpjs:diagnosis'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/reference/room_class'); ?>"><i class="fa fa-th-large" aria-hidden="true"></i> <?php echo lang('bpjs:room_classes'); ?></a></li>
                                <li><a href="<?php echo base_url('bpjs/reference/referral'); ?>"><i class="fa fa-exchange" aria-hidden="true"></i> <?php echo lang('bpjs:referrals'); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    
                    <?php /*?><?php if ('vendor' == $this->login_user->access): ?>
                    <h5 class="sidebartitle"><?php echo lang('nav_title:restful') ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li class="nav-parent">
                        	<a href="javascript:;"><i class="fa fa-compress"></i> <span><?php echo lang('nav:restful') ?></span></a>
                    		<ul class="children">
                                <li class="<?php if(in_array($active_menu, array('restful_access'))){ echo 'active'; } ?>"><a href="<?php echo base_url('restful/restful_access')?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo lang('restful:access') ?></a></li>
                                <li class="<?php if(in_array($active_menu, array('restful_limits'))){ echo 'active'; } ?>"><a href="<?php echo base_url('restful/restful_limits')?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo lang('restful:limits') ?></a></li>
                                <li class="<?php if(in_array($active_menu, array('restful_keys'))){ echo 'active'; } ?>"><a href="<?php echo base_url('restful/restful_keys')?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo lang('restful:keys') ?></a></li>
                                <li class="<?php if(in_array($active_menu, array('restful_uris'))){ echo 'active'; } ?>"><a href="<?php echo base_url('restful/restful_system_uris')?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo lang('restful:uris') ?></a></li>
                                <li class="<?php if(in_array($active_menu, array('restful_controllers'))){ echo 'active'; } ?>"><a href="<?php echo base_url('restful/restful_system_controllers')?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo lang('restful:controllers') ?></a></li>
                                <li class="<?php if(in_array($active_menu, array('restful_logs'))){ echo 'active'; } ?>"><a href="<?php echo base_url('restful/restful_logs')?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo lang('restful:logs') ?></a></li>
                            </ul>
                    	</li>
                    </ul>
                    <?php endif; ?><?php */?>
                    
                    <?php /*?><?php if ('vendor' == $this->login_user->access): ?>
                    <h5 class="sidebartitle"><?php echo lang('nav_title:system') ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li><a href="<?php echo base_url('system/users'); ?>"><i class="fa fa-users"></i> <span><?php echo lang('nav:users'); ?></span></a></li>
                    	<li><a href="<?php echo base_url('system/configs'); ?>"><i class="fa fa-cog"></i> <span><?php echo lang('nav:configs'); ?></span></a></li>
                    </ul>
                    <?php endif; ?><?php */?>
                </div>
