<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  				
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                	<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add') ?></a>
                                </li>
                                <!-- <li class="divider"></li>
                                <li>
                                    <a href="javascript:;" data-mass="delete">
                                        <i class="fa fa-trash-o"></i> <?php echo lang('action:delete') ?>
                                    </a>
                                </li> -->
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:mutation_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
            	<div class="row">
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a href="#post-open" data-toggle="tab"><i class="fa fa-refresh"></i> <strong><?php echo lang("buttons:open")?></strong></a></li>
						<li class=""><a href="#post-realization" data-toggle="tab"><i class="fa fa-refresh"></i> <strong><?php echo lang("buttons:realization")?></strong></a></li>
					</ul>
					<div class="tab-content">
						<div id="post-open" class="tab-pane active">
							<?php echo $view_datatable_open ?>
						</div>
						<div id="post-realization" class="tab-pane">
							<?php echo $view_datatable_realization ?>
						</div>
					</div>	
               	</div>
			</div>
        </div>
    </div>
</div>

