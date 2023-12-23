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
	<div class="col-md-12">
                <div class="row form-group">
                    <label class="col-md-1 control-label"><?php echo lang('search:for_date_from_label') ?></label>
                    <div class="col-md-2">
                        <input type="text" name="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
                    </div>
                    <label class="col-md-1 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
                    <div class="col-md-2">
                        <input type="text" name="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
                    </div>
                    <div class="col-lg-3">
                    	<?php 
						echo form_dropdown('Gudang_ID', $dropdown_section, '', ['class'=>'form-control select','id'=>'Gudang_ID']);
						?>
                    </div>
                    <div class="col-md-3">
                    	<button name="btn_search" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
                	</div>
                </div>
			<div class="row">
            	<div class="table-responsive">
                <table id="dt_trans_goods_receipt_realization" class="table table-bordered table-hover" width="100%" cellspacing="0">
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
                            <th><?php echo lang('label:no_do') ?></th>
                            <th><?php echo lang('label:supplier') ?></th>
							<th><?php echo lang('label:description') ?></th>
							<th><?php echo lang('global:status') ?></th>
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
                            <th><?php echo lang('label:date') ?></th>
                            <th><?php echo lang('label:no_do') ?></th>
							<th><?php echo lang('label:supplier') ?></th>
    						<th><?php echo lang('label:description') ?></th>
    						<th><?php echo lang('global:status') ?></th>
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
            	var _form = $('form[name="form_crud__list_realization"]');
				_form.find("button[name=\"btn_search\"]").on("click", function(e){
						$( "#dt_trans_goods_receipt_realization" ).DataTable().ajax.reload();
				});
				//function code for custom search
				
				$( "#dt_trans_goods_receipt_realization" ).DataTable({
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
								url: "<?php echo site_url("{$nameroutes}/datatable_collection/0") ?>",
								type: "POST",
								data: function(params){
									params.date_from   = _form.find("input[name=\"for_date_from\"]").val();
									params.date_till   = _form.find("input[name=\"for_date_till\"]").val();
									params.Gudang_ID  = _form.find("select[name=\"Gudang_ID\"]").val();
								}
							},
						columns: [
								{orderable: false, searchable: false, render: checkbox},
								{ 
									"data": 'Tgl_Penerimaan',
									render: function ( data, type, row ) {
										return (moment(data).format("DD/MM/YYYY"));
									  }
								},
								{data: 'No_Penerimaan'},
								{data: 'Nama_Supplier'},
								{data: 'Keterangan'},
								{
									data: 'Status_Batal',
									className: 'text-center',
									render: function ( val, type, row ){
										return val == 1 ? "<span class=\"label label-danger\"><?php echo lang('buttons:cancel')?></span>" : ''
									}
								},
								{ 
									data: 'Penerimaan_ID',
									className: "text-center",
									orderable: false,
									width: "70px",
									render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"<?php echo lang('action:edit') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-eye\"></i> <?php echo 'Lihat' ?> </a>";
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


