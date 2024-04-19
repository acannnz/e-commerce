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
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title"><?php echo lang('heading:item_grading_list'); ?></h3>
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
						<div class="form-group">
							<table id="dt_item_grading" class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
											<?php echo form_checkbox([
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:patient_type') ?></th>
										<th><?php echo lang('label:class') ?></th>
										<th><?php echo lang('label:ktp') ?></th>
										<th><?php echo lang('label:price_range') ?></th>
										<th><?php echo lang('label:up') ?></th>
										<th><?php echo lang('label:group') ?></th>
										<th><?php echo lang('label:groups') ?></th>
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
										<th><?php echo lang('label:patient_type') ?></th>
										<th><?php echo lang('label:class') ?></th>
										<th><?php echo lang('label:ktp') ?></th>
										<th><?php echo lang('label:price_range') ?></th>
										<th><?php echo lang('label:up') ?></th>
										<th><?php echo lang('label:group') ?></th>
										<th><?php echo lang('label:groups') ?></th>
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
							order: [[1, 'desc']],
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
									{ data: 'JenisKerjasama',},
									{ data: 'NamaKelas',},
									{ 
										data: 'KTP',
										render: function( val, type, row ){
											return ( val ) ? '<?php echo lang('global:yes')?>' : '<?php echo lang('global:no')?>';
										}
									},
									{ 
										data: 'StartHarga',
										className: 'text-center',
										render: function( val, type, row ){
											return mask_number.currency_add( row.StartHarga ) +' - '+ mask_number.currency_add( row.EndHarga )
										}
									},
									{ 
										data: 'ProsentaseUp',
										className: 'text-right',
										render: function( val ){
											return val +'%';
										}
									},
									{ data: 'KelompokJenis',},
									{ data: 'Golongan',},
									{ 
										data: null,
										className: "",
										orderable: false,
										width: "150px",
										render: function ( val, type, row ){
												
												var params = $.param({
																TipePelayanan : row.TipePelayanan,
																JenisKerjasamaID : row.JenisKerjasamaID,
																KelasID : row.KelasID,
																KTP : row.KTP,
																StartHarga : row.StartHarga,
																KelompokJenis : row.KelompokJenis
															});
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/?" + params + "\" title=\"<?php echo lang('buttons:edit') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:edit') ?> </a>";
													buttons += "<a href=\"javascript:;\" data-action-url=\"<?php echo base_url("{$nameroutes}/delete") ?>/?" + params + "\" data-act=\"ajax-modal\" data-title=\"<?php echo lang('buttons:delete')?>\" title=\"<?php echo lang('buttons:delete') ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-trash\"></i></a>";
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
						$( "#dt_item_grading" ).DataTable().ajax.reload();
				});					
					
				$("#dt_item_grading").DataTableInit();
				
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>
