<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_general_ledger_details" class="table table-sm table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("general_ledger:journal_date_label") ?></th>
                        <th><?php echo lang("general_ledger:journal_number_label") ?></th>
                        <th><?php echo lang("general_ledger:notes_label") ?></th>                        
                        <th><?php echo lang("general_ledger:debit_label") ?></th>
                        <th><?php echo lang("general_ledger:credit_label") ?></th>                        
                        <th><?php echo lang("general_ledger:balance_label") ?></th>                        
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
		
		var _datatable_populate;
		
		$.fn.extend({
				dt_general_ledger_details: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								paging: false,
								ordering: false,
								searching: true,
								info: true,
								autoWidth: false,
								responsive: true,
								//scrollY: "1000px",
								//scrollCollapse: true,
								data:{},
								columns: [
										{ 
											data: "Tanggal",
											className: "actions text-center", 
											
										},
										{ 
											data: "Tanggal", 
											className: "", 
											render: function( val, type, row, meta ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ data: "NoBukti", className: "text-left" },
										{ data: "Keterangan", className: "text-left", },
										{ 
											data: "Debit", 
											className: "text-left", 
										},
										{ 
											data: "Kredit", 
											className: "text-left", 
										},
										{ data: "Saldo", className: "text-left",},
										
									],
								fnRowCallback : function( nRow, aData, iDisplayIndex ) {
									
										var index = iDisplayIndex + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;
					
									},
								drawCallback : function( settings, iDisplayIndex, nRow ) {
										dev_layout_alpha_content.init(dev_layout_alpha_settings);

								},
								
							} );
							
						$( "#dt_general_ledger_details_length select, #dt_general_ledger_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_general_ledger_details" ).dt_general_ledger_details();
				
				
				$("#search_transaction").on("click", function (e){
						e.preventDefault();
						params = {};
						params['f'] = {
							'date_start' : $("#date_start").val(),
							'date_till' : $("#date_till").val(),
							'Currency_ID' : $("#Currency_ID").val(),
							'convertCurrency_ID' : $("#convertCurrency_ID").val(),
							'Akun_ID' : $("#Akun_ID").val() || 0,
						}
						
						$.get('<?php echo base_url("general-ledger/datatable_collection")?>', params, function( response, status, xhr ){
							
							//console.log(response.ending_value);
														
							$("#dt_general_ledger_details").DataTable().clear();							
							$("#dt_general_ledger_details").DataTable().rows.add( response.collection ).draw();
							
							$("#beginning_balance").val( response.beginning_value );
							$("#ending_balance").val( response.ending_value );
							$("#debit").val( response.debit_summary );
							$("#credit").val( response.credit_summary );
						})	

					});
					
				$("#Currency_ID").on("change", function(){
					
					if ( $(this).val() == 0 )
					{
						$("#convertCurrency_ID").val( <?php echo $currency_default ?> );
						$("#convertCurrency_ID").prop("required", true);
					} else {
						$("#convertCurrency_ID").val($(this).val());
						$("#convertCurrency_ID").prop("required", false);
					}
				});
				
								
			});
	})( jQuery );
//]]>
</script>

 