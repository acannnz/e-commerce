<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?= form_open(site_url("{$nameroutes}/mass_action"), [
		'id' => 'form_crud__list_realization', 
		'name' => 'form_crud__list_realization', 
		'rule' => 'form' , 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">  
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title"><?= 'Daftar Penerimaan Bonus' ?></h3>
					</div>
					<div class="col-md-6">
						<div class="panel-bars">
							<ul class="btn-bars">
								<li class="dropdown">
									<a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
										<i class="fa fa-bars fa-lg tip" data-placement="left" title="<?= lang("actions") ?>"></i>
									</a>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a href="<?= site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?= lang('action:add') ?></a>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
            <div class="panel-body table-responsive">
				<div class="table-responseive">
					<div class="row form-group">
						<label class="col-md-1 control-label"><?= lang('search:for_date_from_label') ?></label>
						<div class="col-md-2">
							<input type="text" name="for_date_from" class="form-control searchable datepicker" value="<?= date("Y-m-d")?>" />
						</div>
						<label class="col-md-1 control-label text-center"><?= lang('search:for_date_till_label') ?></label>
						<div class="col-md-2">
							<input type="text" name="for_date_till" class="form-control searchable datepicker" value="<?= date("Y-m-d") ?>" />
						</div>
						<div class="col-lg-3">
							<?= form_dropdown('Gudang_ID', $dropdown_section, '', ['class'=>'form-control select','id'=>'Gudang_ID']); ?>
						</div>
						<div class="col-md-3">
							<button name="btn_search" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?= lang("buttons:refresh")?></b></button>
						</div>
					</div>
					<table id="dt_gift_receive" class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th style="min-width:30px;width:30px;text-align:center;">
									<?= form_checkbox([
											'name' => 'check',
											'checked' => FALSE,
											'class' => 'checkbox checkth'
										]); ?>
								</th>
								<th><?= lang('label:date') ?></th>
								<th><?= lang('label:no_do') ?></th>
								<th><?= lang('label:supplier') ?></th>
								<th><?= 'Nilai' ?></th>
								<th><?= lang('global:status') ?></th>
								<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
							</tr>
						</thead>        
						<tbody>
						</tbody>
						<tfoot class="dtFilter">
							<tr>
								<th style="min-width:30px;width:30px;text-align:center;">
									<?= form_checkbox([
											'name' => 'check',
											'checked' => FALSE,
											'class' => 'checkbox checkth'
										]); ?>
								</th>
								<th><?= lang('label:date') ?></th>
								<th><?= lang('label:no_do') ?></th>
								<th><?= lang('label:supplier') ?></th>
								<th><?= 'Nilai' ?></th>
								<th><?= lang('global:status') ?></th>
								<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
							</tr>
						</tfoot>
					</table>
				</div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list_realization"]');
				_form.find("button[name=\"btn_search\"]").on("click", function(e){
						$( "#dt_gift_receive" ).DataTable().ajax.reload();
				});
				
				$( "#dt_gift_receive" ).DataTable({
						processing: true,
						serverSide: true,								
						paginate: true,
						ordering: true,
						//lengthMenu: [ 50, 75, 100, 150 ],
						order: [[1, 'desc']],
						searching: true,
						info: true,
						responsive: true,
						ajax: {
								url: "<?= site_url("{$nameroutes}/datatable_collection") ?>",
								type: "POST",
								data: function(params){
									params.date_from   = _form.find("input[name=\"for_date_from\"]").val();
									params.date_till   = _form.find("input[name=\"for_date_till\"]").val();
									params.location_id  = _form.find("select[name=\"Gudang_ID\"]").val();
								}
							},
						columns: [
								{orderable: false, searchable: false, render: checkbox},
								{ 
									"data": 'Tgl_Bonus',
									render: function ( data, type, row ) {
										return (moment(data).format("DD/MM/YYYY"));
									  }
								},
								{data: 'No_DO'},
								{data: 'Nama_Supplier'},
								{
									data: 'Total_Nilai',
									className: 'text-center',
									render: function ( val, type, row ){
										return mask_number.currency_add(val);
									}
								},
								{
									data: 'Batal',
									className: 'text-center',
									render: function ( val, type, row ){
										if(val == 1){
											return `<span class="label label-danger">Batal</span>`;
										}else{
											return `<span class="label label-success">Sukses</span>`;
										}
									}
								},
								{ 
									data: 'No_Bonus',
									className: "",
									orderable: false,
									width: "90px",
									render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a data-action-url=\"<?= base_url("{$nameroutes}/view") ?>/" + val + "\" data-modal-lg=\"1\" data-title=\"<?= lang('buttons:view') ?>\" data-act=\"ajax-modal\" title=\"<?= lang('buttons:view') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-eye\"></i> <?= lang('buttons:view') ?> </a>";
											buttons += "</div>";
											
											return buttons
										}
								}
							]
					});
			});
	})( jQuery );
</script>