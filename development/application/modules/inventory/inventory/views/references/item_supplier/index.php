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
                                    <a href="javascript:;" 
                                    	title="<?php echo lang('action:add'); ?>" 
                                        data-act="ajax-modal" 
                                        data-title="<?php echo lang('action:add'); ?>" 
                                        data-action-url="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        	<i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:item_supplier_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
                <table id="dt_ref_item_supplier" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo 'ID Item'; ?></th>
                            <th><?php echo 'Item'; ?></th>
                            <th><?php echo 'ID Supplier'; ?></th>
                            <th><?php echo 'Supplier'; ?></th>
                            <th><?php echo 'H. Beli'; ?></th>
                            <th><?php echo 'T. Beli'; ?></th>
                            <th><?php echo 'K.S'; ?></th>
                            <th><?php echo 'Min. O'; ?></th>
                            <th><?php echo 'Min. S'; ?></th>
                            <th><?php echo 'Konv.'; ?></th>
                            <th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>        
                    <tbody>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr>
                            <th><?php echo 'ID Item'; ?></th>
                            <th><?php echo 'Item'; ?></th>
                            <th><?php echo 'ID Supplier'; ?></th>
                            <th><?php echo 'Supplier'; ?></th>
                            <th><?php echo 'H. Beli'; ?></th>
                            <th><?php echo 'T. Beli'; ?></th>
                            <th><?php echo 'K.S'; ?></th>
                            <th><?php echo 'Min. O'; ?></th>
                            <th><?php echo 'Min. S'; ?></th>
                            <th><?php echo 'Konv.'; ?></th>
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
				
				$( "#dt_ref_item_supplier" ).DataTable({
						order: [[1, 'asc']],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/collection") ?>",
								type: "POST",
								data: function( params ){}
							},
						columns: [
								{data: 'Kode_Barang'},
								{data: 'Nama_Barang'},
								{data: 'Kode_Supplier'},
								{data: 'Nama_Supplier'},
								{data: 'Harga', class: 'text-right'},
								{data: 'Tgl_Beli', class: 'text-right'},
								{data: 'Kerjasama', orderable: false, searchable: false, defaultContent: 'T', class: 'text-center'},
								{data: 'MinOrder', orderable: false, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'MinStok', orderable: false, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'Konversi', orderable: false, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'Actions', orderable: false, searchable: false, defaultContent: 1, class: 'text-center'}
							]
					});
			});
	})( jQuery );
</script>


