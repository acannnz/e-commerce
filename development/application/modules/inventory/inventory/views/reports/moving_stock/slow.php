<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title">Laporan Slow Moving Stok</h3>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row form-group">
							<table id="dt_slow_moving" class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Kode Barang</th>
										<th>Nama Barang</th>
										<th>Satuan</th>
										<th>Qty</th>
										<th>Transaksi Terakhir</th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th>Kode Barang</th>
										<th>Nama Barang</th>
										<th>Satuan</th>
										<th>Qty</th>
										<th>Transaksi Terakhir</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
(function( $ ){
		$( document ).ready(function(e) {
				
				var _datatable = $( "#dt_slow_moving" );
				//function code for custom search
				$( "#dt_slow_moving" ).DataTable({
					processing: true,
					serverSide: false,								
					paginate: true,
					ordering: true,
					lengthMenu: [ 15, 25, 50, 100 ],
					order: [[0, 'desc']],
					searching: true,
					info: true,
					responsive: true,
					ajax: {
							url: "<?php echo site_url("{$nameroutes}/datatable_collection/1") ?>",
							type: "POST",
							data: function(params){
							}
						},
					columns: [
							{	
								data: 'Kode_Barang', 
							},
							{
								data: 'Nama_Barang'
							},
							{
								data: 'Satuan', 
							},
							{
								data: 'Qty', 
							},
							{
								data: 'TglTransaksi_Terakhir', 
								className: 'text-center',
								render: function(val){
									if(val == null || val == '') return '';
									return `<span style="display:none">${val}</span>${moment(val).format('DD-MMM-YYYY')}`;
								}
							},
						]
				});
									
			});
	})( jQuery );
</script>


