<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_mutation_details" class="table table-bordered table-striped table-sm" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("invoices:factur_number_label") ?></th>
                        <th><?php echo lang("invoices:date_label") ?></th>
                        <th><?php echo lang("invoices:debit_label") ?></th>
                        <th><?php echo lang("invoices:credit_label") ?></th>
                        <th><?php echo lang("invoices:description_label") ?></th>                 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    	<div class="form-group">
        	<label class="col-md-3 text-center"><?php echo lang("invoices:mutation_total_label")?></label>
            <div class="col-md-9">
            	<input type="text" id="mutation_total" class="form-control" readonly="readonly" />
            </div>
        </div>
	</div>
    <div class="col-md-6">
    	<div class="form-group">
        	<label class="col-md-3 text-center"><?php echo lang("invoices:mutation_remain_label")?></label>
            <div class="col-md-9">
            	<input type="text" id="mutation_remain" class="form-control" readonly="readonly" />
            </div>
        </div>
    </div>
</div>
<hr>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {		
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_receivable\"]" );
						var _tol_mutation = _form.find( "input[id=\"mutation_total\"]" );
						var _tol_invoice = _form.find( "input[id=\"Nilai\"]" );
						var _tol_mutation = _form.find( "input[id=\"mutation_total\"]" );
						var _tol_remain = _form.find( "input[id=\"mutation_remain\"]" );
						var tol_mutation = 0;
						
						var table_data = $( "#dt_mutation_details" ).DataTable().rows().data();
						table_data.each(function (value, index) {
							tol_mutation = tol_mutation + Number(value.Debit) - Number(value.Kredit);
						});
						
						_tol_mutation.val( tol_mutation.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );	
						
						tol_remain = Number( _tol_invoice.val() ) + tol_mutation;
						_tol_remain.val( tol_remain.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );	
						
					},
					
					
			};
		
		$.fn.extend({
				dt_mutation_details: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode($collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ data: "No_Bukti", className: "text-center",  },
										{ data: "No_Bukti", className: "text-center",  },
										{ 
											data: "Tgl_transaksi", 
											className: "text-center", 
											render: function( val ){
													return val.substr(0, 10);
												}
										},
										{ data: "Debit", className: "text-right",  },
										{ data: "Kredit", className: "text-right",  },
										{ data: "Keterangan", className: "text-left" },
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								fnRowCallback : function( nRow, aData, iDisplayIndex ) {
									
										var index = iDisplayIndex + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;
					
									},
								createdRow: function ( row, data, index ){
										_datatable_actions.calculate_balance();
								}
							} );
							
						$( "#dt_mutation_details_length select, #dt_mutation_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_mutation_details" ).dt_mutation_details();				
				
			});
	})( jQuery );
//]]>
</script>

 