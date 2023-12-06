<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( base_url("general-ledger/balance-sheet/export") )?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('balance_sheets:trial_balance_heading'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-2"><?php echo lang('balance_sheets:period') ?> <span class="text-danger">*</span></label>
					<div class="col-md-6">
						<div class="input-group">
							<input type="text" id="date_start" name="date" class="datepicker form-control" value="<?php echo date("Y-m-d") ?>" data-date-min-date="<?php echo config_item("Tanggal Mulai System") ?>" required="required"/>
							<div class="input-group-addon">s/d</div>
							<input type="text" id="date_until" name="date" class="datepicker form-control" value="<?php echo date("Y-m-d") ?>" data-date-min-date="<?php echo config_item("Tanggal Mulai System") ?>" required="required"/>
						</div>
					</div>
					<div class="col-md-3">
						<a href="javascitp:;" id="trial-balance-refresh" class="btn btn-success"><i class="fa fa-refresh fa-lg"></i> <b><?php echo lang('buttons:refresh')?></b></a>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-12 text-right">
						<button type="submit" formtarget="_blank" class="btn btn-primary"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print")?></b></button>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<table id="trial-balance" class="table table-hover" width="100%">
					<thead>
						<tr>
							<th>Deskripsi</th>
							<th>Saldo Awal</th>
							<th>Debet</th>
							<th>Kredit</th>
							<th>Saldo Akhir</th>
						</tr>
					</thead>        
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php echo form_close()?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
					
		$.fn.extend({
				dataTableTrialBalance: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: false,
							//lengthMenu: [ 15, 30, 60, 120 ],
							ordering: false,
							//order: false,
							searching: true,
							info: true,
							responsive: true,
							fixedHeader: {
								header: true,
								footer: true
							},
							ajax: {
									url: "<?php echo $trial_balance_collection ?>",
									type: "POST",
									data: function( params ){
											params.date_start = $('#date_start').val();
											params.date_until = $('#date_until').val();
										}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							createdRow: function( row, data, dataIndex ) {
								if(data.Induk == 1)
								{
									switch(data.LevelKe)
									{
										case 1:
											$(row).addClass('danger');
											break;
										case 2:
											$(row).addClass('info');
											break;
										case 3:
											$(row).addClass('success');
											break;
										case 4:
											$(row).addClass('warning');
											break;
									}
									
								}
							},
							columnDefs: [
								{
									targets: 0,
									createdCell:  function (td, cellData, rowData, row, col) {
										$(td).attr('style', 'white-space:pre'); 
									}
								}
							],
							columns: [
									{ 
										data: "Akun_No", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<b>" + val + '  ' + row.AkunName + "</b>" 
											}
									},
									{ 
										data: "SaldoAwal",
										className: "text-right",
										render: function(val, type, row){
												return row.Induk == 1 ? '' : mask_number.currency_add(val)
											}
									},
									{ 
										data: "Debet",
										className: "text-right",
										render: function(val, type, row){
												return row.Induk == 1 ? '' : mask_number.currency_add(val)
											}
									},
									{ 
										data: "Kredit",
										className: "text-right",
										render: function(val, type, row){
												return row.Induk == 1 ? '' : mask_number.currency_add(val)
											}
									},
									{ 
										data: "SaldoAwal",
										className: "text-right",
										render: function(val, type, row){
												if(row.NormalPos == 'D')
													var saldoAkhir = parseFloat(row.SaldoAwal) + parseFloat(row.Debet) - parseFloat(row.Kredit);
												if(row.NormalPos == 'K')	
													var saldoAkhir = parseFloat(row.SaldoAwal) + parseFloat(row.Kredit) - parseFloat(row.Debet);
													
												return row.Induk == 1 ? '' : mask_number.currency_add(saldoAkhir);
											}
									},
								]
						} );
						
					$( "#trial-balance_length select, #trial-balance_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
						
		$( document ).ready(function(e) {
				$('#trial-balance-refresh').on('click', function(){
					$('#trial-balance').DataTable().ajax.reload();
				});
				
				$('#trial-balance').dataTableTrialBalance();		
						
			});
			
	})( jQuery );
//]]>
</script>