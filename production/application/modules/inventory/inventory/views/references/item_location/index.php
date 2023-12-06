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
					
                </div>
                <h3 class="panel-title"><?php echo lang('heading:item_location_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
                <table id="dt_ref_item_location" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo 'Lokasi'; ?></th>
                            <th><?php echo 'Kode Item'; ?></th>
                            <th><?php echo 'Nama Item'; ?></th>
                            <th><?php echo 'Satuan'; ?></th>
                            <th><?php echo 'Jenis'; ?></th>
                            <th><?php echo 'Qty. Stock'; ?></th>
                            <th><?php echo 'Min. Stock'; ?></th>
                            <th><?php echo 'Max. Stock'; ?></th>
                            <th><?php echo 'Death Stock'; ?></th>
                            <th><?php echo 'Diperbarui'; ?></th>
                            <th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>        
                    <tbody>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr>
                            <th><?php echo 'Lokasi'; ?></th>
                            <th><?php echo 'Kode Item'; ?></th>
                            <th><?php echo 'Nama Item'; ?></th>
                            <th><?php echo 'Satuan'; ?></th>
                            <th><?php echo 'Jenis'; ?></th>
                            <th><?php echo 'Qty. Stock'; ?></th>
                            <th><?php echo 'Min. Stock'; ?></th>
                            <th><?php echo 'Max. Stock'; ?></th>
                            <th><?php echo 'Death Stock'; ?></th>
                            <th><?php echo 'Diperbarui'; ?></th>
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
				
				$( "#dt_ref_item_location" ).DataTable({
						order: [[1, 'asc']],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/collection") ?>",
								type: "POST",
								data: function( params ){}
							},
						columns: [
								{data: 'Section_Name', defaultContent: 'General'},
								{data: 'Kode_Barang'},
								{data: 'Nama_Barang'},
								{data: 'Satuan'},
								{data: 'Nama_Jenis'},
								{data: 'Qty_Stok', orderable: true, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'Min_Stok', orderable: true, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'Max_Stok', orderable: true, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'Death_Stok', orderable: true, searchable: false, defaultContent: 0, class: 'text-center'},
								{data: 'LastUpdate'},
								{data: 'Actions', orderable: false}
							]
					});
			});
	})( jQuery );
</script>


