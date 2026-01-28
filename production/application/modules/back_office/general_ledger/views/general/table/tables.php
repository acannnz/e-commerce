<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_general_ledger_details" class="table table-sm table-bordered table-hover" width="100%">
                <thead>
                	<tr>
                        <th><?php echo lang("general_ledger:journal_date_label") ?></th>
                        <th><?php echo lang("general_ledger:journal_number_label") ?></th>
                        <th><?php echo lang("general_ledger:account_number_label") ?></th>
                        <th><?php echo lang("general_ledger:account_name_label") ?></th>
                        <th><?php echo lang("general_ledger:debit_label") ?></th>
                        <th><?php echo lang("general_ledger:credit_label") ?></th>                        
                        <?php /*?><th><?php echo lang("general_ledger:proyek_label") ?></th>                        <?php */?>
                        <th><?php echo lang("general_ledger:notes_label") ?></th>    
						<th><?php echo lang("general_ledger:journal_number_label") ?></th>                    
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
								deferRender: true,
								serverSide: false,								
								paginate: false,
								paging: false,
								//lengthMenu: [ 30, 45, 75, 100 ],
								ordering: false,
								searching: true,
								info: true,
								autoWidth: false,
								responsive: true,
								//scrollY: "500px",
								//scrollCollapse: true,
								data:[],
								columns: [
										{ 
											data: "Tanggal", 
											className: " text-center", 
											render: function( val, type, row ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ 
											data: "NoBukti", 
											className: " text-center", 
											render: function( val, type, row ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ data: "NoAkun"},
										{ data: "NamaAkun"},
										{ 
											data: "Debit", 
										},
										{ 
											data: "Kredit", 
										},
										/*{ 
											data: "Proyek", 
										},*/
										{ data: "Keterangan", },
										{ 
											data: "NoBuktiHide", 
											className: " text-center", 
											render: function( val, type, row ){ 
												return "<b>"+ val +"</b>";
											} 
										},
									],
								columnDefs: [{
									  targets: 7,
									  searchable: true,
									  visible: false
								}],
								/*createdRow: function( row, data, dataIndex ) {
									if(data.NoBukti != '')
									{
										$(row).addClass('warning');
									}
									
									var filterData = $( "#dt_general_ledger_details" ).DataTable().column(7).data().filter( function ( value, index )
									{
										if (value == data.NoBukti || value == data.NoBuktiHide)
										{
											$( "#dt_general_ledger_details" ).DataTable().row( index ).show();
										}
									});
								},*/
								fnRowCallback : function( nRow, aData, iDisplayIndex ) {
									
										/*var index = iDisplayIndex + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;*/
					
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
						params = {
							'date_start' : $("#date_start").val(),
							'date_till' : $("#date_till").val(),
							'Currency_ID' : $("#Currency_ID").val(),
							'journal_type' : $("#journal_type").val(),
						}
						
						$.get('<?php echo @$populate_url ?>', params, function( response, status, xhr ){
							
							//console.log(response.ending_value);
														
							$("#dt_general_ledger_details").DataTable().clear();							
							$("#dt_general_ledger_details").DataTable().rows.add( response.collection ).draw();
							
							$("#debit").val( response.debit );
							$("#credit").val( response.credit );
							$("#balance").val( response.balance );
						})	

					});
					
				
				
								
			});
	})( jQuery );
//]]>
</script>

 