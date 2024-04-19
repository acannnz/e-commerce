<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_trans_purchase_request_item" class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>                        
                        <th>Konversi</th>                        
                        <th>Kategori</th>                        
                        <th>Satuan</th>                        
                        <th>Min Stok</th>                        
                        <th>Max Stok</th>                        
                        <th>Qty sistem</th>
                        <th>Qty</th>
                        <th>Harga @</th>
                        <th>Jumlah</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$lookup_service ?>" id="add_charge" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a>
</div>
<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list"]');
				$( "#reset" ).on("click", function(e){
						$( "#dt_trans_purchase_request" ).DataTable().ajax.reload()
				});
				//function code for custom search
				
				$( "#dt_trans_purchase_request_item" ).DataTable({
						order: [[2, 'asc']],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/table_item_collection") ?>",
								type: "POST",
								data: function(params){									
									}
							},
							
						columns: [
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null
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