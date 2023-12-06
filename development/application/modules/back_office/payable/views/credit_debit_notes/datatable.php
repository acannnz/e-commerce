<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open() ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('credit_debit_notes:page'); ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("payable/credit-debit-note/create")?>"  class="btn btn-info pull-right"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('credit_debit_notes:periode_label') ?></label>
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
					<label class="control-label"><?php echo lang('types:type_label')?></label>
					<select id="type_id" name="type_id" class="form-control">
						<option value=""><?php echo lang('global:select-all') ?></option>
						<?php if( !empty($options_type)): foreach( $options_type as $k => $v ): ?>
						<option value="<?php echo $k ?>"><?php echo $v ?></option>
						<?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('credit_debit_notes:supplier_label') ?></label>
					<div class="input-group">
						<input type="hidden" id="Supplier_ID" name="Supplier_ID" class="form-control" value="" />
						<input type="text" id="Nama_Supplier" name="Nama_Supplier" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo @$lookup_suppliers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" ><i class="fa fa-gear"></i></a>
							<a href="javascript:;" title="" id="btn-clear-supplier"  class="btn btn-danger" ><i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<a href="javascript:;" id="btn-search" class="btn btn-primary btn-block"><b><i class="fa fa-search"></i> <?php echo lang("credit_debit_notes:find_credit_debit_note_list_label") ?></b></a>
				</div>
			</div>
		</div>
		
		<div class="table-responsive">
			<table id="dt-payable-credit_debit_notes" class="table table-stripped table-sm" width="100%">
				<thead>
					<tr>
						<th><?php echo lang('credit_debit_notes:date_label') ?></th>
						<th><?php echo lang('credit_debit_notes:evidence_number_label') ?></th>
						<th><?php echo lang('credit_debit_notes:supplier_label') ?></th>
						<th><?php echo lang('credit_debit_notes:value_label') ?></th>
						<th><?php echo lang('credit_debit_notes:project_label') ?></th>
						<th><?php echo lang('credit_debit_notes:division_label') ?></th>
						<th></th>
					</tr>
				</thead>        
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTableReceivableCredit_debit_notes: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 50, 75, 100, 150 ],
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("payable/credit-debit-note/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.f = {
											date_start : $("#date_start").val(),
											date_end : $("#date_end").val(),
											type_id : $("#type_id").val(),
											supplier_id : $("#Supplier_ID").val(),
										}
									}
								},
							columns: [
									{ 
										data: "Tgl_Voucher",
										className: "text-center"
									},
									{ 
										data: "No_Voucher", 
										width: "140px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Nama_Supplier", 
										render: function( val ){
												return val ? val.substr(0, 45) : val;
											} 
									},
									{ 
										data: "Nilai", 
										className: "text-right", 
										render: function ( val, type, row, meta){
												return mask_number.currency_add(val);
											}
									},
									{ 
										data: "Nama_Proyek",
									},
									{ 
										data: "Nama_Divisi", 
									},
									{ 
										data: "No_Voucher",
										orderable: false,
										width: '150px',
										render: function ( val, type, row ){		
												buttons = "<div class=\"btn-group\" role=\"group\" aria-label=\"\">";
													buttons += "<a href=\"<?php echo base_url("payable/credit-debit-note/edit") ?>/?No_Voucher=" + encodeURIComponent(val) + "\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-eye\"></i> <?php echo lang( "buttons:view" ) ?></a>";
													buttons += row.Cancel_Voucher == 1 ? "<button type=\"button\" class=\"btn btn-danger btn-xs\"><b><?php echo lang("buttons:cancel")?></b></button>" : '';
												buttons += "</div>";
												return buttons
											}
									}
								],
								fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
						} );
						
					$( "#dt-payable-credit_debit_notes_length select, #dt-payable-credit_debit_notes_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-payable-credit_debit_notes" ).DataTableReceivableCredit_debit_notes();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-payable-credit_debit_notes" ).DataTable().ajax.reload();
				});
				
				$("#btn-clear-supplier").on("click", function(e){
					e.preventDefault();
					
					$("#Supplier_ID, #Kode_Supplier, #Nama_Supplier").val( "" );
				});
				
			});
	})( jQuery );
//]]>
</script>