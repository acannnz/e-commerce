<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title"><?php echo lang('heading:goods_receipt_list'); ?></h3>
					</div>
					<div class="col-md-6">
						<div class="panel-bars">
							<ul class="btn-bars">
								<li class="dropdown">
									<a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
										<i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
									</a>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a href="<?php echo site_url("{$nameroutes}/create_Penerimaan") ?>"><i class="fa fa-plus"></i> Tanpa Order</a>
										</li>
										<li>
											<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> Dengan Order</a>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
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
