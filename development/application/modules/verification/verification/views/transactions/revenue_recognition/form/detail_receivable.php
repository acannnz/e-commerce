<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="form-group">
	
	</div>
	<div class="form-group">
		<div class="table-responsive">
			<table id="dt_detail_receivable" class="table table-sm table-bordered" width="100%">
				<thead>
					<tr>
						<th><?php echo lang('label:no_journal_ar')?></th>                        
						<th><?php echo lang('label:customer')?></th>                                                
						<th><?php echo lang('label:account_name')?></th>                    
						<th><?php echo lang('label:value')?></th>    
						<th><?php echo lang('label:date_closing')?></th>                        
						<th><?php echo lang('label:description')?></th>                        
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
				dt_detail_receivable: function(){
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
										{ data: "NoBuktiAR", className: "text-center", },
										{ data: "Nama_Customer", className: "text-center", },
										{ data: "Akun_Name" },
										{ 
											data: "NilaiPiutang", 
											className: "text-right",
											render: function( val ){
												return mask_number.currency_add( val );
											}
										},
										{ data: "TglClosing"},
										{ data: "Keterangan"},
									],
								createdRow: function ( row, data, index ){
										//_datatable_actions.calculate_balance();
									},
							} );
							
						$( "#dt_detail_receivable_length select, #dt_detail_receivable_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
			$( "#dt_detail_receivable" ).dt_detail_receivable();
			
		});

	})( jQuery );
//]]>
</script>