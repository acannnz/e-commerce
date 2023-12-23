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
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>" 
                                    	title="<?php echo lang('action:add'); ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add'); ?></a>
                                    <?php /*?><a href="javascript:;" 
                                    	title="<?php echo lang('action:add'); ?>" 
                                        data-act="ajax-modal" 
                                        data-title="<?php echo lang('action:add'); ?>" 
                                        data-action-url="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        	<i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a><?php */?>
                                </li>
                                <!-- <li class="divider"></li> -->
                                <!-- <li>
                                    <a href="javascript:;" data-mass="delete">
                                        <i class="fa fa-trash-o"></i> <?php echo lang('action:delete') ?>
                                    </a>
                                </li> -->
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:item_list'); ?></h3>
            </div>
            <div class="panel-body">
            	<div class="well well-sm">
                	<div class="row">
                    	<div class="col-sm-6 col-xs-12">
                        	<div class="form-group">
								<?php echo form_label('Cari Berdasarkan', '', ['class' => 'control-label col-sm-12']) ?>
                                <div class="col-sm-4">
									<?php echo form_dropdown('s[filter]', $populate_filter, 'ALL', [
                                            'id' => 'input_parent', 
                                            'placeholder' => '',
                                            'required' => 'required', 
                                            'class' => 'form-control'
                                        ]); ?>
                            	</div>
                                <div class="col-sm-7">
                                	<?php echo form_input('s[words]', '', [
                                            'id' => 'input_code', 
                                            'placeholder' => 'Kata Kunci', 
                                            'required' => 'required',
                                            'class' => 'form-control'
                                        ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-xs-12">
                        	<div class="form-group">
								<?php echo form_label('Lokasi', '', ['class' => 'control-label col-sm-12']) ?>
                                <div class="col-sm-10">
									<?php echo form_dropdown('s[location]', $populate_section, 'ALL', [
                                            'id' => 'input_parent', 
                                            'placeholder' => '',
                                            'required' => 'required', 
                                            'class' => 'form-control'
                                        ]); ?>
                            	</div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                        	<div class="form-group">
								<?php echo form_label('Status', '', ['class' => 'control-label col-sm-12']) ?>
                                <div class="col-sm-12">
									<?php echo form_radio([
											'id' => 'checkbox_status_1',
											'name' => 's[status]',
											'value' => 1,
											'checked' => false,
											'class' => 'radio'
										]).' '.form_label('<b>Aktif</b>', 'checkbox_status_1'); ?>
                            		<?php echo form_radio([
											'id' => 'checkbox_status_2',
											'name' => 's[status]',
											'value' => 2,
											'checked' => false,
											'class' => 'radio'
										]).' '.form_label('<b>Non-Aktif</b>', 'checkbox_status_2'); ?>
                                	<?php echo form_radio([
											'id' => 'checkbox_status_3',
											'name' => 's[status]',
											'value' => 3,
											'checked' => true,
											'class' => 'radio'
										]).' '.form_label('<b>Semua</b>', 'checkbox_status_3'); ?>
									<?php echo form_radio([
											'id' => 'checkbox_status_4',
											'name' => 's[status]',
											'value' => 4,
											'checked' => false,
											'class' => 'radio'
										]).' '.form_label('<b>Konsinyasi</b>', 'checkbox_status_4'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dt_ref_item" class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="min-width:30px;width:30px;text-align:center;">
									<?php echo form_checkbox([
                                            'name' => 'check',
                                            'checked' => FALSE,
                                            'class' => 'checkbox checkth'
                                        ]); ?>
                                </th>
                                <th><?php echo 'Kode'; ?></th>
                                <th><?php echo 'Nama Barang'; ?></th>
                                <th><?php echo 'Kategori'; ?></th>
                                <th><?php echo 'Jenis'; ?></th>
                                <th><?php echo 'Satuan'; ?></th>
                                <th><?php echo 'Stok'; ?></th>
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
                                <th><?php echo 'Kode'; ?></th>
                                <th><?php echo 'Nama Barang'; ?></th>
                                <th><?php echo 'Kategori'; ?></th>
                                <th><?php echo 'Jenis'; ?></th>
                                <th><?php echo 'Satuan'; ?></th>
                                <th><?php echo 'Stok'; ?></th>
                                <th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
                            </tr>
                        </tfoot>
                    </table>
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
				
				_form.find('select[name="s[filter]"]').on('change', function(e){
						e.preventDefault();
						_form.find('input[name="s[words]"]').trigger('keyup');
					});
				_form.find('input[name="s[words]"]').on('keyup', function(e){
						e.preventDefault();	
						if (String($.trim($(this).val())).length >= 1){
							$("#dt_ref_item").DataTable().ajax.reload();
						}
					});
				_form.find('select[name="s[location]"]').on('change', function(e){
						e.preventDefault();
						$("#dt_ref_item").DataTable().ajax.reload();
					});
				_form.find('input[name="s[status]"]').on('ifChecked', function(e){
						e.preventDefault();
						$("#dt_ref_item").DataTable().ajax.reload();
					});
					
				$("#dt_ref_item").DataTable({
						order: [[1, 'asc']],
						searching: false,
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/collection") ?>",
								type: "POST",
								data: function(params){
										var FF = _form;
										if(FF.size()){
											var advanced_search = {};
											
											advanced_search["filter"] = String(FF.find("[name=\"s[filter]\"]").val() || "");
											advanced_search["words"] = String(FF.find("[name=\"s[words]\"]").val() || "");
											advanced_search["location"] = String(FF.find("[name=\"s[location]\"]").val() || "");
											advanced_search["status"] = String(FF.find("[name=\"s[status]\"]:checked").val() || "");
											
											params["advanced_search"] = advanced_search;
										}
									}
							},
						columns: [
								{data: 'Barang_ID', orderable: false, searchable: false, render: checkbox},
								{data: 'Kode_Barang'},
								{data: 'Nama_Barang', render: function(v){ return '<b>' + v + '</b>'; }},
								{data: 'Nama_Kategori'},
								{data: 'Kelompok_Jenis'},
								{data: 'Satuan_Stok'},
								{data: 'Qty_Stok', orderable: false, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'Actions', orderable: false, searchable: false, class: 'text-center'}
							]
					});
			});
	})( jQuery );
</script>


