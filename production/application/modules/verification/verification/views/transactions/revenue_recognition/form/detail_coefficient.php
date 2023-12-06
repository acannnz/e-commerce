<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="form-group">
	
	</div>
	<div class="form-group">
		<div class="table-responsive">
			<table id="dt_detail_coefficient" class="table table-sm table-bordered" width="100%">
				<thead>
					<tr>
						<th><?php echo lang('label:officer')?></th>                        
						<th><?php echo lang('label:description')?></th>                    
						<th><?php echo lang('label:component')?></th>    
						<th><?php echo lang('label:amount')?></th>    
						<th><?php echo lang('label:group')?></th>    
						<th><?php echo lang('label:weight')?></th>
						<th><?php echo lang('label:coefficient')?></th>    
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
		var _datatable;
		
		var _datatable_actions = {
				calculate_balance: function(){
					var _form = $( "form[name=\"form_create_purchase_request\"]" );
					var _form_balance = _form.find( "input[id=\"grand_total\"]" );
						
					var tol_balance = 0;
					
					var collection = $( "#dt_trans_purchase_request_detail" ).DataTable().rows().data();
					
					collection.each(function(value, index){
						
						tol_balance = tol_balance + mask_number.currency_remove( value.Jumlah_Total );
							
					});
					
					_form_balance.val( mask_number.currency_add( tol_balance ) );
				}
			};
		
		$.fn.extend({
				dt_detail_coefficient: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: true,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "DokterID", 
											render: function( val, type, row ){
												return row.DokterID +'-'+ row.Nama_Supplier
											}
										},
										{ data: "Keterangan", },
										{ data: "Komponen", },
										{ data: "Jumlah", className: "text-right", },
										{ data: "Kelompok" },
										{ data: "Bobot", className: "text-right", },
										{ data: "Koefesien", className: "text-right", },
									],
								createdRow: function ( row, data, index ){
										//_datatable_actions.calculate_balance();
									},
							} );
							
						$( "#dt_detail_coefficient_length select, #dt_detail_coefficient_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
			$( "#dt_detail_coefficient" ).dt_detail_coefficient();
			
		});

	})( jQuery );
//]]>
</script>