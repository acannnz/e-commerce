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
	<div class="col-md-offset-2 col-md-8">
		<div class="panel panel-default">
            <div class="panel-heading">  
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title"><?php echo lang('heading:gapah_list'); ?></h3>
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
											<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add') ?></a>
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
					<div class="col-md-12">
						<div class="form-gapah">
							<table id="dt_gapah" class="table table-bordered table-hover" width="100%" cellspacing="0">
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
										<th><?php echo lang('label:name') ?></th>
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
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:code') ?></th>
										<th><?php echo lang('label:name') ?></th>
										<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<script>
(function( $ ){
	
		$.fn.extend({
				DataTableInit: function(){
					
						var _this = this;
						//function code for custom search
						var _datatable = _this.DataTable( {		
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 10, 30, 75],
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
									type: "POST",
									data: function(params){
									}
								},
							columns: [
									{orderable: false, searchable: false, render: checkbox},
									{ 
										data: 'KodeGapah',
										className: 'text-center',
										render: function ( val, type, row ) {
											return "<b>"+ val +"</b>";
										  }
									},
									{ 
										data: 'Deskripsi', 
									},
									{ 
										data: 'KodeGapah',
										className: "",
										orderable: false,
										width: "150px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"<?php echo lang('buttons:edit') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:edit') ?> </a>";
													buttons += "<a href=\"javascript:;\" data-action-url=\"<?php echo base_url("{$nameroutes}/delete") ?>/" + val + "\" data-act=\"ajax-modal\" data-title=\"<?php echo lang('buttons:delete')?>\" title=\"<?php echo lang('buttons:delete') ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-trash\"></i></a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						});
							
					return _this;
				}

			});
							
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list"]');
				_form.find("button[name=\"btn_search\"]").on("click", function(e){
						$( "#dt_gapah" ).DataTable().ajax.reload();
				});					
					
				$("#dt_gapah").DataTableInit();
				
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>
