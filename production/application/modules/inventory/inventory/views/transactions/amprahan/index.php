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
                <h3 class="panel-title"><?php echo lang('heading:amprahan_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="col-md-1 control-label"><?php echo lang('search:for_date_from_label') ?></label>
							<div class="col-md-2">
								<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
							</div>
							<label class="col-md-1 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
							<div class="col-md-2">
								<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
							</div>
							<div class="col-md-3">
								<button id="reset" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="cl-md-12">
						<div class="form-group">
							<table id="dt_trans_amprahan_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
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
										<th><?php echo lang('label:description') ?></th>
										<th><?php echo lang('label:state') ?></th>
										<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
										</th>
										<th><?php echo lang('label:no_evidence') ?></th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:section_from') ?></th>
										<th><?php echo lang('label:section_to') ?></th>
										<th><?php echo lang('label:description') ?></th>
										<th><?php echo lang('label:state') ?></th>
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
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list"]');
				$( "#reset" ).on("click", function(e){
						$( "#dt_trans_amprahan_list" ).DataTable().ajax.reload()
				});
				//function code for custom search
				
				$( "#dt_trans_amprahan_list" ).DataTable({
						processing: true,
						serverSide: true,								
						paginate: true,
						ordering: true,
						order: [[1, 'desc']],
						searching: true,
						info: true,
						responsive: true,
						lengthMenu: [ 30, 75, 100 ],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
								type: "POST",
								data: function(params){
									params.date_from = $("#for_date_from").val();
									params.date_till = $("#for_date_till").val();
									
								}
							},
						fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
						columns: [
									{orderable: false, searchable: false, render: checkbox},
									{ 
										data: "NoBukti", 
										className: "text-center",
										name: "a.NoBukti",
										width: "180px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tanggal", 
										class: "text-center",
										name: "a.Tanggal",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "SectionAsalName", 
										className: "",
										name: "b.SectionName",
									},							
									{ 
										data: "SectionTujuanName", 
										className: "",
										name: "c.SectionName",
									},							
									{ 
										data: "Keterangan", 
										render: function( val ){
											return val != '' ? val.substr(0, 10) : val;
										}
									},
									{ 
										data: "Realisasi", 
										class: 'text-center',
										render: function( val, type, row ){
											var label = '';
											if ( val == 1)
											{
												label = "<span class=\"label label-success\"> <?php echo lang('label:realization')?> </span>";
											} else if (row.Batal == 1) {
												label = "<span class=\"label label-danger\"> <?php echo lang('label:cancel')?> </span>";
											}
											
											return label;
											
										}
										
									},
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:edit')?></a>";
												buttons += "</div>";
												
												return buttons;
											}
									}								
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


