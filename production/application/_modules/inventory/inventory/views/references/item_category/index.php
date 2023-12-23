<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open(site_url("{$nameroutes}/mass_action"), [
		'id' => 'form_crud__list', 
		'name' => 'form_crud__list', 
		'rule' => 'form' , 
		'class' => ''
	]); ?>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
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
                                    <a href="javascript:;" 
                                    	title="<?php echo lang('action:add'); ?>" 
                                        data-act="ajax-modal" 
                                        data-title="<?php echo lang('action:add'); ?>" 
                                        data-action-url="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        	<i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;" data-mass="delete">
                                        <i class="fa fa-trash-o"></i> <?php echo lang('action:delete') ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo site_url('inventory/references/item_subcategory'); ?>">
                                        <i class="fa fa-folder-o"></i> <?php echo lang('nav:subcategory') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:item_category_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
                <table id="dt_ref_item_category" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="min-width:30px;width:30px;text-align:center;">
                                <?php echo form_checkbox([
                                        'name' => 'check',
                                        'checked' => FALSE,
                                        'class' => 'checkbox checkth'
                                    ]); ?>
                            </th>
                            <th><?php echo lang('label:code') ?></th>
                            <th><?php echo lang('label:category') ?></th>
                            <th><?php echo lang('label:cn_factur') ?> (%)</th>
                            <th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>        
                    <tbody>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr>
                            <th style="min-width:30px;width:30px;text-align:center;">
                                <?php echo form_checkbox([
                                        'name' => 'check',
                                        'checked' => FALSE,
                                        'class' => 'checkbox checkft'
                                    ]); ?>
                            </th>
                            <th><?php echo lang('label:code') ?></th>
                            <th><?php echo lang('label:category') ?></th>
                            <th><?php echo lang('label:cn_factur') ?> (%)</th>
                            <th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list"]');
				
				$( "#dt_ref_item_category" ).DataTable({
						order: [[1, 'asc']],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/collection") ?>",
								type: "POST",
								data: function( params ){}
							},
						columns: [
								{orderable: false, searchable: false, render: checkbox},
								null,
								null,
								null,
								{orderable: false, searchable: false}
							]
					});
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>


