<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('beginning_balances:list_heading'); ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("payable/beginning-balance/create")?>" data-toggle="ajax-modal" class="btn btn-info pull-right"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<?php if (!empty($type_beginning_balance)): foreach($type_beginning_balance as $row): ?>
				<div class="col-md-12">
					<h4>GL: <?php echo $row->type_name ?>  <strong class="text-danger pull-right"><?php echo number_format( $row->value, 2, ".", ","); ?></strong></h4>
				</div>
				<?php endforeach; endif; ?>
			</div>
			<div class="col-md-6">
				<?php if (!empty($type_beginning_balance)): foreach($type_beginning_balance as $row): ?>
				<div class="col-md-12">
					<h4>AP: <?php echo $row->type_name ?>  <strong id="<?php echo $row->id ?>" class="text-danger pull-right">Rp. 0.00</strong></h4>
				</div>
				<?php endforeach; endif; ?>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-4 control-label"><?php echo lang('beginning_balances:payable_type_label')?></label>
					<div class="col-md-8">
						<select id="payable_type" name="f[payable_type]" class="form-control" required>
							<?php if (!empty($options_type)) : foreach($options_type as $k => $v) : ?>
							<option value="<?php echo @$k ?>" > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>    
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<a href="javascript:;" id="btn-refresh" title="<?php echo lang('buttons:refresh') ?>" class="btn btn-primary "><b><i class="fa fa-refresh"></i> <span><?php echo lang('buttons:refresh') ?></span></b></a>
				</div>    
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="dt-table-details" class="table" width="100%">
						<thead>
							<tr>
								<th><?php echo lang("beginning_balances:supplier_code_label") ?></th>
								<th><?php echo lang("beginning_balances:supplier_name_label") ?></th>
								<th><?php echo lang("beginning_balances:project_name_label") ?></th>
								<th><?php echo lang("beginning_balances:date_label") ?></th>
								<th><?php echo lang("beginning_balances:currency_label") ?></th>
								<th><?php echo lang("beginning_balances:value_label") ?></th>
								<th><?php echo lang("beginning_balances:division_name_label") ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable_details;
		
		var _datatable_details_populate;
		var _datatable_details_actions = {			
				init : function(){
					var timer = 0;
			
					$( "#payable_type" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( _datatable_details_actions.reload_table , 600 ); 
							
					});
												
					$("#btn-refresh").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( _datatable_details_actions.reload_table , 600 ); 
					});
					
				},
			reload_table : function(){
					$( "#dt-table-details" ).DataTable().ajax.reload();
				}					
			};
		
		$.fn.extend({
				dt_table_details: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable_details = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: true,
								paging: true,
								ordering: false,
								searching: true,
								info: false,
								autoWidth: true,
								responsive: true,
								lengthMenu: [ 30, 45, 75, 100 ],
								//scrollY: "600px",
								scrollCollapse: true,
								ajax: {
										url: "<?php echo $populate_url ?>",
										type: "POST",
										data: function( params ){
											params.payable_type = $("#payable_type").val();
										},
									},
								columns: [								
										{ 
											data: "Kode_Supplier", 
											className: "text-center", 
											render: function( val, type, row, meta ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ data: "Nama_Supplier", },
										{ data: "Nama_Proyek", },
										{ 
											data: "Tgl_Saldo", 
											className: "text-center",
											render: function(val){
												return val.substr(0,10)
											}
										},
										{ data: "Currency_Code", className: "text-center" },
										{ 
											data: "Nilai", 
											className: "text-left",
											render: function ( val, type, row){
												return mask_number.currency_add(val);
											}
										},
										{ data: "Nama_Divisi",  },
										{ 
											data: "Supplier_ID",
											className: "a-right actions",
											orderable: false,
											render: function ( val, type, row ){							
													params = $.param( row );
													var buttons = '<div class="btn-group">'
													buttons += "<a href=\"<?php echo base_url("payable/beginning-balance/edit") ?>/?" + params + "\" data-toggle=\"ajax-modal\"title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
													buttons += "<a href=\"<?php echo base_url("payable/beginning-balance/delete") ?>/?" + params + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></a>";
													buttons += '</div>'
													return buttons
												}
										}
									],
								drawCallback: function( settings ) {
									
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
	
								}

							} );
							
						$( "#dt-table-details_length select, #dt-table-details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
				_datatable_details_actions.init();
            	$( "#dt-table-details" ).dt_table_details();
				
			});
	})( jQuery );
//]]>
</script>

 