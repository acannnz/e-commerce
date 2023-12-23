<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open() ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('cash_bank_income:page'); ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("general-cashier/cash-bank-income/non-invoices/create") ?>" class="btn btn-info" title="Pembayaran Baru"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('cash_bank_income:periode_label') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="date_end" name="date_end" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('cash_bank_income:type_label') ?></label>
					<select id="Type_Transaksi" name="Type_Transaksi" class="form-control">
						<option value=""><?php echo lang('global:select-none')?></option>
						<option value="BKM">Kas Masuk</option>
						<option value="BBM">Bank Masuk</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('buttons:search') ?></label>
					<input type="text" id="search_text" name="search_text" class="form-control">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<a href="javascript:;" id="btn-search" class="btn btn-primary btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:search") ?></b></a>
				</div>
			</div>
		</div>
	
		<?php echo form_close()?>
		<div class="table-responsive">
			<table id="dt-receivable-invoices" class="table" width="100%">
				<thead>
					<tr>
						<th><?php echo lang('cash_bank_income:date_label') ?></th>
						<th><?php echo lang('cash_bank_income:evidence_number_label') ?></th>
						<th><?php echo lang('cash_bank_income:value_label') ?></th>
						<th><?php echo lang('cash_bank_income:description_label') ?></th>
						<th><?php echo lang('cash_bank_income:account_label') ?></th>
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
		$.fn.extend({
				DataTableReceivableFacturs: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							lengthChange: false,
							lengthMenu: [ 50, 75, 100, 150 ],
							order: [[0, 'desc']],
							searching: false,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("general-cashier/cash-bank-income/non-invoices/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.f = {
											date_start : $("#date_start").val(),
											date_end : $("#date_end").val(),
											Type_Transaksi : $("#Type_Transaksi").val(),
											search_text : $("#search_text").val(),
										}
									}
								},
							columns: [
									{ 
										data: "Tgl_Transaksi",
										className: "text-center"
									},
									{ 
										data: "No_Bukti", 
										width: "200px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Nilai", 
										className: "text-right", 
										render: function ( val, type, row, meta){
												return mask_number.currency_add( val )
											}
									},
									{ 
										data: "Keterangan", 
										render: function( val ){
												return val ? val.substr(0, 45) : val;
											} 
									},
									{ 
										data: "Akun_Name", 
									},
									{ 
										data: "No_Bukti",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){		
												buttons = "<div class=\"btn-group\" role=\"group\" aria-label=\"\">";
													buttons += "<a href=\"<?php echo base_url("general-cashier/cash-bank-income/non-invoices/edit") ?>/?No_Bukti=" + encodeURIComponent(val) + "\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:view" ) ?></a>";
													buttons += row.Status_Batal == 1 ? "<button type=\"button\" class=\"btn btn-danger btn-xs\"><b><?php echo lang("buttons:cancel")?></b></button>" : '';
												buttons += "</div>";
												return buttons
											}
									}
								],
							fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
						} );
						
					$( "#dt-receivable-invoices_length select, #dt-receivable-invoices_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-receivable-invoices" ).DataTableReceivableFacturs();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-receivable-invoices" ).DataTable().ajax.reload();
				});
				
				$("#btn-clear-customer").on("click", function(e){
					e.preventDefault();
					
					$("#Customer_ID, #Kode_Customer, #Nama_Customer").val( "" );
				});
				
			});
	})( jQuery );
//]]>
</script>