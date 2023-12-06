<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('bank_cash_deposit:list_subtitle') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("cashier/bank-cash-deposit/create") ?>" title="<?php echo lang('buttons:create') ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:create') ?></span></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo 'Tanggal Dari' ?></label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
						<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="dt-bank-cash-deposit" class="table table-sm table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th><?php echo lang("bank_cash_deposit:evidence_number_label")?></th>
						<th><?php echo lang("bank_cash_deposit:date_label")?></th>
						<th><?php echo lang("bank_cash_deposit:description_label")?></th>
						<th><?php echo lang("bank_cash_deposit:value_label")?></th>
						<th><?php echo lang("bank_cash_deposit:account_label")?></th>
						<th><?php echo lang("bank_cash_deposit:user_label")?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
	var search_datatable = {
			init : function(){
					var timer = 0;
			
					$( ".searchable" ).on("keyup", function(e){
						e.preventDefault();
		
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 
						}
					});
	
					$( ".searchable_option, .check-searchable" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
							
					});
					
					$("#date_from, #date_till").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
	
					});
							
					$("#reset").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
					});
					
				},
			reload_table : function(){
					$( "#dt-bank-cash-deposit" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_NonInvoiceReceipt: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("cashier/bank-cash-deposit/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Jam", 
										render: function ( val, type, row ){
												return val.substr(0,19)
											}
									},
									{ 
										data: "Keterangan", 
										width: "250px",
										render: function( val ){
											return val.substr(0, 35)
										}
									},	
									{ 
										data: "Nilai", 
										className: "text-right",
										render: function ( val, type, row ){
												var val = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},															
									{ data: "akun_Name", width: null },
									
									{ data: "Nama_Singkat", width: null },
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("cashier/bank-cash-deposit/edit") ?>/" + val + "\" title=\"Lihat Detail\" class=\"btn btn-info btn-xs\"><b><i class=\"fa fa-eye\"></i> LIhat</b></a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-bank-cash-deposit_length select, #dt-bank-cash-deposit_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-bank-cash-deposit" ).DataTable_NonInvoiceReceipt();
				search_datatable.init();
			});
	})( jQuery );
//]]>
</script>