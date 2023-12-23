<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_invoices" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                        <th><?php echo lang("credit_debit_notes:date_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:invoice_number_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:original_value_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:increase_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:decrease_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:balance_label") ?></th>                 
                        <th><?php echo lang("credit_debit_notes:description_label") ?></th>                 
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
		var _datatable_actions = {		
				edit: function( row, data, index ){
						
						switch( this.index() ){
							
						}
					},
				calculate_balance: function(params, scope){
						
						var _form = $( "form[name=\"form_debit_credit_note\"]" );
						var _text_balance = _form.find( "h2[id=\"credit_debit_note_value\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						
						params.balance = Number(params.original_value) + Number(params.increase) - Number(params.decrease);
						params.balance_money = Number(params.balance).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")

						_datatable.row( scope ).data( params );

						_text_balance.html("Rp. "+ Number(params.balance).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						
						if (Number(params.balance) > 0)
						{
							_text_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_text_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
					
					
			};
		
		$.fn.extend({
				dt_invoices: function(){
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
								autoWidth: false,
								responsive: true,
								ajax: {
										url: "<?php echo $populate_url ?>",
										type: "POST",
										data: function( params ){
												
												params.invoice_id = $("#invoice_id").val() || "";
												params.evidence_number = $("#evidence_number").val() || "";
											
											},
										dataSrc: function( response ){
												
												_datatable_populate = response.data || [];
												return _datatable_populate;
											}
									},
								columns: [
										{ data: "invoice_date", className: "text-center",  },
										{ data: "invoice_number", className: "text-center", },
										{ data: "original_value_money", className: "text-right" },
										{ data: "increase_money", className: "text-right",  },
										{ data: "decrease_money", className: "text-right", },
										{ data: "balance_money", className: "text-right",  },
										{ 
											data: "description", 
											className: "text-left",  
											render: function( val){
												
												return val.substr(0, 30);
											}
										},
										
									],
								columnDefs  : [
										{
											"targets": ["type_id","original_value","increase", "decrease", "balance"],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									}
							} );
							
						$( "#dt_invoices_length select, #dt_invoices_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_invoices" ).dt_invoices();
				
				$("form[name=\"form_debit_credit_note\"]").on("submit", function(e){
					e.preventDefault();
					
					var data_post = {};
					
					var table_data = $( "#dt_invoices" ).DataTable().row(0).data();

					data_post.nota = {
							"invoice_date" : $("#invoice_date").val(),
							"invoice_number" : $("#invoice_number").val(),
							"customer_id" : $("#customer_id").val(),
							"type_id" : table_data.type_id,
							"account_id" : $("#account_id").val(),
							"due_date" : $("#invoice_date").val(),
							"description" : $("#description").val(),
							"value" : table_data.increase > 0 ? table_data.increase : table_data.decrease,
							"remain" : table_data.increase > 0 ? table_data.increase : table_data.decrease,
							"transaction_type_id" : table_data.increase > 0 ? 407 : 406
					};
					
					data_post.remain = table_data.balance;
					
					data_post.nota_detail = { 
							"invoice_number" : $("#invoice_number").val(),
							"evidence_number" : table_data.invoice_number,
							"original_value" : table_data.remain,
							"debit" : table_data.decrease,
							"credit" : table_data.increase,
							"transaction_type_id" : table_data.increase > 0 ? 407 : 406,
							"transaction_date" : $("#invoice_date").val(),
							"description" : $("#description").val()
					}

					data_post.invoice_detail = { 
							"invoice_number" : table_data.invoice_number,
							"evidence_number" : $("#invoice_number").val(),
							"debit" : table_data.decrease,
							"credit" : table_data.increase,
							"transaction_type_id" : table_data.increase > 0 ? 407 : 406,
							"transaction_date" : $("#invoice_date").val(),
							"description" : $("#description").val()
					}
										
					data_post.card_receivable = {
							"type_id" : table_data.type_id,
							"customer_id" : $("#customer_id").val(),
							"invoice_number" : table_data.invoice_number,
							"evidence_number" : $("#invoice_number").val(),
							"factur_number" : $("#invoice_number").val(),
							"debit" : table_data.decrease,
							"credit" : table_data.increase,
							"date" : $("#invoice_date").val(),
							"description" : $("#description").val()
					}
				
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( response.status == "error"){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success("<?php echo lang('global:created_successfully')?>");
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("receivable/debit_credit_note/view"); ?>/"+ id ;
							
							}, 3000 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 