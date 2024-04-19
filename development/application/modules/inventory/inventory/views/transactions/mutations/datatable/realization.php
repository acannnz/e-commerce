<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open(site_url("{$nameroutes}/mass_action"), [
		'id' => 'form_crud__list_realization', 
		'name' => 'form_crud__list_realization', 
		'rule' => 'form' , 
		'class' => ''
	]); ?>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('search:for_date_from_label') ?></label>
			<div class="col-md-9">
				<input type="text" name="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
			</div>
		</div>
		<div class="form-group">
			<?php echo form_label(lang('label:from'), 'location_from', ['class' => 'control-label col-md-3']) ?>
			<div class="col-md-9">
				<select class="form-control" name="location_from">
					<?php if( !empty($dropdown_section_from)): foreach($dropdown_section_from as $k => $v): ?>
					<option value="<?php echo $k ?>"><?php echo $v ?></option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('search:for_date_till_label') ?></label>
			<div class="col-md-9">
				<input type="text" name="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
			</div>
		</div>
		<div class="form-group">
			<?php echo form_label(lang('label:to'), 'location_to', ['class' => 'control-label col-md-3']) ?>
			<div class="col-md-9">
				<select class="form-control" name="location_to">
					<option value=""><?php echo lang('global:select-all')?></option>
					<?php if( !empty($dropdown_section_to)): foreach($dropdown_section_to as $k => $v): ?>
					<option value="<?php echo $k ?>"><?php echo $v ?></option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<button name="btn_search" type="button" class="btn btn-success btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
		</div>
	</div>
</div>
<hr/>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<table id="dt_trans_mutation_realization_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="min-width:30px;width:30px;text-align:center;">
							<?php echo form_checkbox([
									'name' => 'check',
									'checked' => FALSE,
									'class' => 'checkbox checkth'
								]); ?>
						</th>
						<th><?php echo lang('label:no_evidence') ?></th>
						<th><?php echo lang('label:date') ?></th>
						<th><?php echo lang('label:section_from') ?></th>
						<th><?php echo lang('label:section_to') ?></th>
						<th><?php echo lang('label:description')?></th>
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
						<th><?php echo lang('label:no_evidence') ?></th>
						<th><?php echo lang('label:date') ?></th>
						<th><?php echo lang('label:section_from') ?></th>
						<th><?php echo lang('label:section_to') ?></th>
						<th><?php echo lang('label:description')?></th>
						<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list_realization"]');
				_form.find("button[name=\"btn_search\"]").on("click", function(e){
						$( "#dt_trans_mutation_realization_list" ).DataTable().ajax.reload();
				});
				
				$( "#dt_trans_mutation_realization_list" ).DataTable({
						processing: true,
						serverSide: true,								
						paginate: true,
						ordering: true,
						order: [[1, 'desc']],
						searching: true,
						info: true,
						responsive: true,
						lengthMenu: [ 20, 50, 100 ],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/datatable_collection/0") ?>",
								type: "POST",
								data: function(params){
									params.date_from = _form.find("input[name=\"for_date_from\"]").val();
									params.date_till = _form.find("input[name=\"for_date_till\"]").val();
									params.location_from = _form.find("select[name=\"location_from\"]").val();
									params.location_to = _form.find("select[name=\"location_to\"]").val();								
								}
							},
						columns: [
								{orderable: false, searchable: false, render: checkbox},
								{ "data": "No_Bukti"
								},
								{data: "Tgl_Mutasi",render: function ( data, type, row ) {
										return (moment(data).format("DD/MM/YYYY"));
									  return data;
									  }},
								{data: "NamaLokasiAsal"},
								{data: "NamaLokasiTujuan"},
								{data: "Keterangan"},
								{ 
									data: "No_Bukti",
									className: "",
									orderable: false,
									width: "90px",
									render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"<?php echo lang('action:edit') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('action:edit') ?> </a>";
											buttons += "</div>";
											
											return buttons
										}
								}
							]
					});
			});
	})( jQuery );
</script>


