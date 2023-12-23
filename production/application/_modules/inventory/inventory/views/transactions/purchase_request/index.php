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
                <h3 class="panel-title"><?php echo lang('heading:purchase_request_list'); ?></h3>
            </div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<label class="col-md-1 control-label"><?php echo lang('search:for_date_from_label') ?></label>
						<div class="col-md-2">
							<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
						</div>
						<label class="col-md-1 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
						<div class="col-md-2">
							<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
						</div>
						<div class="col-lg-3">
							<?php 
							echo form_dropdown('Gudang_ID',$dropdown_section,'',['class'=>'form-control select','id'=>'Gudang_ID']);
							?>
						</div>
						<div class="col-md-3">
							<button id="reset" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
						</div>
					</div>
				</div>
			</div>

            <div class="panel-body table-responsive">
                <table id="dt_trans_purchase_request" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="min-width:30px;width:30px;text-align:center;">
                                <?php echo form_checkbox([
                                        'name' => 'check',
                                        'checked' => FALSE,
                                        'class' => 'checkbox checkth'
                                    ]); ?>
                            </th>
                            <th><?php echo lang('label:date') ?></th>
                            <th><?php echo lang('label:request_number') ?></th>
                            <th><?php echo lang('label:date_request') ?></th>
							<th><?php echo lang('label:supplier') ?></th>
                            <th><?php echo lang('label:description') ?></th>
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
                            <th><?php echo lang('label:date') ?></th>
                            <th><?php echo lang('label:request_number') ?></th>
							<th><?php echo lang('label:date_request') ?></th>
							<th><?php echo lang('label:supplier') ?></th>
							<th><?php echo lang('label:description') ?></th>
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
				$( "#reset" ).on("click", function(e){
					$( "#dt_trans_purchase_request" ).DataTable().ajax.reload()
				});
				//function code for custom search
				$( "#dt_trans_purchase_request" ).DataTable({
						order: [[2, 'asc']],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
								type: "POST",
								data: function(params){
									params.date_from = $("#for_date_from").val();
									params.date_till = $("#for_date_till").val();
									params.Gudang_ID = $("#Gudang_ID").val();
									
									}
							},
						columns: [
								{orderable: false, searchable: false, render: checkbox},
								{ 
									data: 'Tgl_Permintaan',
									render: function ( val, type, row ) {
										return (moment(val).format("DD/MM/YYYY"));
									  }
								},

								{
									data : 'No_Permintaan'
								},
								{ data: 'Tgl_Dibutuhkan',
									render: function ( data, type, row ) {
										return (moment(data).format("DD/MM/YYYY"));
									  return data;
									  }
								},
								{
									data : 'Nama_Supplier'
								},
								{
									data : 'Keterangan'
								},
								{ 
									data: 'Permintaan_ID',
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
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>


