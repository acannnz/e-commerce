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
                    
                    <h5 class="sidebartitle"><?php echo lang('nav:heading_inventory'); ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li>
							<a href="<?php echo site_url('verification/transactions/revenue_recognition'); ?>"><i class="fa fa-exchange" aria-hidden="true"></i> <span><?php echo lang('nav:revenue_recognition')?></span></a>
                        </li>
                        <li>
							<a href="<?php echo site_url('verification/transactions/revenue_recognition/view'); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo "View Pengakuan Pendapatan"?></span></a>
                        </li>
                        <li class="nav-parent">
                        	<a href=""><i class="fa fa-random" aria-hidden="true"></i> <span><?php echo 'Posting'; ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo site_url('verification/behaviors/posting'); ?>"><i class="fa fa-circle-o"></i> Posting Keuangan</a></li>
                                <li><a href="<?php echo site_url('verification/behaviors/posting/cancel'); ?>"><i class="fa fa-circle-o"></i> Batalkan Posting Keuangan</a></li>                                
                            </ul>
                        </li>
						<li class="nav-parent">
                        	<a href=""><i class="fa fa-print" aria-hidden="true"></i> <span><?php echo 'Laporan'; ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo site_url('verification/report/honor'); ?>"><i class="fa fa-circle-o"></i> Honor Dokter</a></li>
                            </ul>
                        </li>
                    	<li>
							<a href="<?php echo site_url('system/config'); ?>"><i class="fa fa-gears" aria-hidden="true"></i> <span><?php echo lang('nav:setup')?></span></a>
                        </li>
                        <?php /*?><li class="nav-parent">
                        	<a href=""><i class="fa fa-pencil" aria-hidden="true"></i> <span><?php echo lang('nav:preferences'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo site_url('verification/preferences/inventory_config'); ?>"><i class="fa fa-circle-o"></i> Setup Awal Barang</a></li>
                                <li><a href="<?php echo site_url('verification/preferences/system_config'); ?>"><i class="fa fa-circle-o"></i> Setup Awal Sistem</a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo site_url('verification/reports'); ?>"><i class="fa fa-clipboard"></i> <span><?php echo lang('nav:reports'); ?></span></a></li>
                        <li><a href="<?php echo site_url('verification/help'); ?>"><i class="fa fa-life-ring"></i> <span><?php echo lang('nav:help'); ?></span></a></li><?php */?>
                    </ul>
                </div>
