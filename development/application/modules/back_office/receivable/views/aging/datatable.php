<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open( $export_url, ['name' => 'form_aging', 'target' => '_blank'] ) ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('aging:page'); ?></h3>
	</div>
	<div class="panel-body">
		<input type="hidden" id="post_data" name="post_data">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('aging:date_label') ?></label>
					<div class="col-lg-3">
						<input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-d") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
					<label class="col-lg-2 text-center control-label"><?php echo lang('aging:currency_label') ?></label>
					<div class="col-lg-5">
						<select name="currecy_id" id="currecy_id" class="form-control">
							<?php if( @$option_currency ): foreach( $option_currency as $key => $val): ?>
							<option value="<?php echo $key ?>"><?php echo $val ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('aging:customer_label') ?></label>
					<div class="col-lg-3">
						<input type="hidden" id="customer_id" name="customer_id" class="form-control" value="" />
						<input type="text" id="customer_code" name="customer_code" class="form-control" readonly />
					</div>
					<div class="col-md-7 input-group">
						<input type="text" id="customer_name" name="customer_name" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo @$lookup_customers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" ><i class="fa fa-gear"></i></a>
							<a href="javascript:;" title="" id="btn-clear-customer"  class="btn btn-danger" ><i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('aging:type_label') ?></label>
					<div class="col-lg-10">
						<select name="type_id" id="type_id" class="form-control">
							<option value=""><?php echo lang('global:select-all') ?></option>
							<?php if( @$option_type ): foreach( $option_type as $key => $val): ?>
							<option value="<?php echo $key ?>"><?php echo $val ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<a href="javascript:;" id="btn-search" class="btn btn-block btn-success"><b><i class="fa fa-search"></i> <?php echo lang("buttons:search") ?></b></a>
					</div>
					<div class="col-md-6">
						<a href="javascript:;" id="btn-export" class="btn btn-block btn-primary"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-md-12">
						<table id="dt-aging" class="table table-bordered">
							<thead>
								<tr>
									<th rowspan="2"></th>
									<th rowspan="2"><?php echo lang('aging:customer_label') ?></th>
									<th rowspan="2"><?php echo lang('aging:amount_label') ?></th>
									<th rowspan="2"><?php echo lang('aging:not_due_label') ?></th>
									<th colspan="6" class="text-center"><?php echo lang('aging:due_label') ?></th>
								</tr>
								<tr>
									<th><?php echo lang('aging:1_30_label') ?></th>
									<th><?php echo lang('aging:31_60_label') ?></th>
									<th><?php echo lang('aging:61_90_label') ?></th>
									<th><?php echo lang('aging:91_180_label') ?></th>
									<th><?php echo lang('aging:181_365_label') ?></th>
									<th><?php echo lang('aging:1_year_label') ?></th>
								</tr>
								<tr>
									<th colspan="2" class="text-right"><?php echo lang('aging:grand_total_label') ?></th>
									<th class="total"></th>
									<th class="not_due"></th>
									<th class="in_1_30"></th>
									<th class="in_31_60"></th>
									<th class="in_61_90"></th>
									<th class="in_91_180"></th>
									<th class="in_181_365"></th>
									<th class="in_1_year"></th>
								</tr>
							</thead>        
							<tbody>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2" class="text-right"><?php echo lang('aging:grand_total_label') ?></th>
									<th class="total"></th>
									<th class="not_due"></th>
									<th class="in_1_30"></th>
									<th class="in_31_60"></th>
									<th class="in_61_90"></th>
									<th class="in_91_180"></th>
									<th class="in_181_365"></th>
									<th class="in_1_year"></th>
								</tr>
								<tr>
									<th colspan="2"></th>
									<th><?php echo lang('aging:amount_label') ?></th>
									<th><?php echo lang('aging:not_due_label') ?></th>
									<th><?php echo lang('aging:1_30_label') ?></th>
									<th><?php echo lang('aging:31_60_label') ?></th>
									<th><?php echo lang('aging:61_90_label') ?></th>
									<th><?php echo lang('aging:91_180_label') ?></th>
									<th><?php echo lang('aging:181_365_label') ?></th>
									<th><?php echo lang('aging:1_year_label') ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		var datatable_actions = {
				init: function(){
					
					$("#btn-export").on("click", function(){
							
						var _form = $("form[name=\"form_aging\"]");
						var _form_post = $("#post_data");
						var post_data = {};
						
						post_data = {
							total : $(".total").html(),
							not_due : $(".not_due").html(),
							in_1_30 : $(".in_1_30").html(),
							in_31_60 : $(".in_31_60").html(),
							in_61_90 : $(".in_61_90").html(),
							in_91_180 : $(".in_91_180").html(),
							in_181_365 : $(".in_181_365").html(),
							in_1_year : $(".in_1_year").html(),
							date_start : $("#date_start").val(),
							collection : {}
						};
						
						var collection = $( "#dt-aging" ).DataTable().rows().data();
						collection.each(function(value, index){
							post_data['collection'][index] = value;
						});
						_form_post.val( JSON.stringify(post_data) );
						
						_form.submit();
							
					});

					$("#btn-clear-customer").on("click", function(){
						$('#customer_id').val('');
						$('#customer_code').val('');
						$('#customer_name').val('');
					});
				
					},
				calculate_balance: function(){
				
					var sum_total = 0,
						sum_not_due = 0,
						sum_in_1_30 = 0,
						sum_in_31_60 = 0,
						sum_in_61_90 = 0,
						sum_in_91_180 = 0,
						sum_in_181_365 = 0,
						sum_in_1_year = 0;
						
					var collection = $( "#dt-aging" ).DataTable().rows().data();
					collection.each(function(value, index){
						sum_total = sum_total + mask_number.currency_remove( value.Jumlah );
						sum_not_due = sum_not_due + mask_number.currency_remove( value.BelumTempo );
						sum_in_1_30 = sum_in_1_30 + mask_number.currency_remove( value.antara30 );
						sum_in_31_60 = sum_in_31_60 + mask_number.currency_remove( value.antara60 );
						sum_in_61_90 = sum_in_61_90 + mask_number.currency_remove( value.antara90 );
						sum_in_91_180 = sum_in_91_180 + mask_number.currency_remove( value.antara180 );
						sum_in_181_365 = sum_in_181_365 + mask_number.currency_remove( value.antara365 );
						sum_in_1_year = sum_in_1_year + mask_number.currency_remove( value.diatas1th );
						
					});
					
					
					$(".total").html( mask_number.currency_add( sum_total ));
					$(".not_due").html( mask_number.currency_add( sum_not_due ));
					$(".in_1_30").html( mask_number.currency_add( sum_in_1_30));
					$(".in_31_60").html( mask_number.currency_add( sum_in_31_60 ));
					$(".in_61_90").html( mask_number.currency_add( sum_in_61_90 ));
					$(".in_91_180").html( mask_number.currency_add( sum_in_91_180 ));
					$(".in_181_365").html( mask_number.currency_add( sum_in_181_365 ));
					$(".in_1_year").html( mask_number.currency_add( sum_in_1_year ));
				}
			};
		$.fn.extend({
				DataTableGeneralLedgerPostings: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 30, 50, 100],
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("receivable/aging/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.date_start = $("#date_start").val();
										params.type_id = $("#type_id").val();
										params.currecy_id = $("#currecy_id").val();
										params.customer_id = $("#customer_id").val();
									}
								},
							fnDrawCallback: function( settings ){ 
									dev_layout_alpha_content.init(dev_layout_alpha_settings); 
									datatable_actions.calculate_balance();
								},
							fnRowCallback : function( nRow, aData, iDisplayIndex ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);						
									
									var index = iDisplayIndex + 1;
									$('td:eq(0)',nRow).html('<b>'+ index +'</b>');
									return nRow;				
								},
							columns: [
									{ 
										data: "Customer_ID",
										className: "text-center",
									},
									{ 
										data: "Kode_Customer",
										render: function ( val, type, row ){
												return val +" - "+ row.Nama_Customer
											}
									},
									{ 
										data: "Jumlah", 
										width: "120px",
										className: "text-right",
										render: function ( val, type, row ){
												return mask_number.currency_add( val );
											}
									},
									{ 
										data: "BelumTempo", 
										width: "120px",
										className: "text-right",
										render: function ( val, type, row ){
												return mask_number.currency_add( val );
											}
									},
									{ 
										data: "antara30",
										width: "120px",
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									{ 
										data: "antara60",
										width: "120px",
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									{ 
										data: "antara90",
										width: "120px",
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									{ 
										data: "antara180",
										width: "120px",
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									{ 
										data: "antara365",
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									{ 
										data: "diatas1th",
										width: "120px",
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									
								],
								createdRow: function ( row, data, index ){
									dev_layout_alpha_content.init(dev_layout_alpha_settings);	
								}				
						} );
						
					$( "#dt-aging_length select, #dt-aging_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				datatable_actions.init();
            	$( "#dt-aging" ).DataTableGeneralLedgerPostings();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-aging" ).DataTable().ajax.reload();
				});
				
			});
	})( jQuery );
//]]>
</script>