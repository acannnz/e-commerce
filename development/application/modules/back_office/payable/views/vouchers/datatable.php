<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo form_open() ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('vouchers:list_heading'); ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("payable/vouchers/create")?>"  class="btn btn-info pull-right"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('vouchers:periode_label') ?></label>
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
					<label class="control-label"><?php echo lang('vouchers:supplier_label') ?></label>
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
					<label class="col-md-12 control-label"><?php echo lang('buttons:search') ?></label>
					<input type="text" id="search_text" name="search_text" class="form-control"/>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<div class="row">
						<div class="col-md-4">
							<div class="radio" style="margin-top:0">
								<input type="radio" id="all_data" name="view_state" value="all" checked="checked"><label for="all_data"><?php echo lang('global:check-all') ?></label>
							</div>                    
						</div>
						<div class="col-md-4">
							<div class="radio" style="margin-top:0">
								<input type="radio" id="active_data" name="view_state" value="active" ><label for="active_data"><?php echo lang('global:active_status') ?></label>
							</div>                    
						</div>
						<div class="col-md-4">
							<div class="radio" style="margin-top:0">
								<input type="radio" id="cancel_data" name="view_state" value="cancel" ><label for="cancel_data"><?php echo lang('global:cancel_status') ?></label>
							</div>                    
						</div>
					</div>
					
					<a href="javascript:;" id="btn-search" class="btn btn-primary btn-block"><b><i class="fa fa-search"></i> <?php echo lang("vouchers:find_voucher_list_label") ?></b></a>
					
				</div>
			</div>
		</div>
		<?php echo form_close()?>
		<div class="table-responsive">
			<table id="dt-payable-vouchers" class="table table-sm" width="100%">
				<thead>
					<tr>
						<th><?php echo lang('vouchers:date_label') ?></th>
						<th><?php echo lang('vouchers:voucher_number_label') ?></th>
						<th><?php echo lang('vouchers:supplier_label') ?></th>
						<th><?php echo lang('vouchers:value_label') ?></th>
						<th><?php echo lang('vouchers:remain_label') ?></th>
						<th><?php echo lang('vouchers:proyek_label') ?></th>
						<th><?php echo lang('vouchers:division_label') ?></th>
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
							serverSide: false,								
							paginate: true,
							ordering: true,
							lengthChange: false,
							lengthMenu: [ 50, 75, 100, 150 ],
							order: [[0, 'desc']],
							searching: false,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("payable/vouchers/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.f = {
											date_start : $("#date_start").val(),
											date_end : $("#date_end").val(),
											Supplier_ID : $("#Supplier_ID").val(),
											search_text : $("#search_text").val(),
											view_state : $("input[name=\"view_state\"]:checked").val() || '',
										}
									}
								},
							columns: [
									{ 
										data: "Tgl_Voucher",
										width: '90px',
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
										data: "Sisa", 
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
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){		
												buttons = "<div class=\"btn-group\" role=\"group\" aria-label=\"\">";
													buttons += "<a href=\"<?php echo base_url("payable/vouchers/edit") ?>/?No_Voucher=" + encodeURIComponent(val) + "\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:view" ) ?></a>";
													buttons += row.Cancel_Voucher == 1 ? "<a href=\"javascript:;\" class=\"btn btn-danger btn-xs\"><?php echo lang("buttons:cancel")?></a>" : '';
												buttons += "</div>";
												return buttons
											}
									}
								],
							fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
						} );
						
					$( "#dt-payable-vouchers_length select, #dt-payable-vouchers_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-payable-vouchers" ).DataTableReceivableFacturs();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-payable-vouchers" ).DataTable().ajax.reload();
				});
				
				$("#btn-clear-supplier").on("click", function(e){
					e.preventDefault();
					
					$("#Supplier_ID, #Kode_Supplier, #Nama_Supplier").val( "" );
				});
				
			});
	})( jQuery );
//]]>
</script>