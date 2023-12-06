<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_invoices" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("credit_debit_notes:invoice_number_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:date_label") ?></th>
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
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							default 4:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_invoice_detail ?>/?No_Invoice="+ encodeURIComponent(data.No_Invoice) )
								} catch(ex){}
							break;
							
							/*case 3:
								var _input = $( "<input type=\"text\" value=\"" + (data.increase || "") + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.increase = this.value || 0;
											data.increase_money = Number(data.increase).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") || 0;
											
											if ( data.increase > 0 )
											{
												data.decrease = 0;
												data.decrease_money = 0;
											}

											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance( data, row );
											
										} catch(ex){}
									});
							break;

							case 4:
								var _input = $( "<input type=\"text\" value=\"" + (data.decrease || '' ) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.decrease = this.value || 0;
											data.decrease_money = Number(data.decrease).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") || 0;
											if( data.decrease > 0 )
											{
												data.increase = 0;
												data.increase_money = 0;
											}
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance( data, row );
											
										} catch(ex){}
									});
							break;*/
						}
					},
				get_invoice_detail: function( row, data ){
					
						$.get("<?php echo $invoice_detail_url ?>", {'No_Invoice' : data.No_Invoice }, function( response, status, xhr ){

							var response = $.parseJSON(response);
	
							if( response.status == "error"){
								$.alert_error(response.message);
								return false
							}
							
							// Set invoice detail (collection) to WebStroge
							webStroge.sessionSetItem( data.No_Invoice, response.collection );
						});				
					},
				calculate_balance: function(params, scope){
						
						var _form = $( "form[name=\"form_debit_credit_note\"]" );
						var _text_value = _form.find( "h2[id=\"credit_debit_note_value\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						
						params.balance = Number(params.original_value) + Number(params.increase) - Number(params.decrease);
						params.balance_money = Number(params.balance).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")

						_datatable.row( scope ).data( params );

						_text_value.html("Rp. "+ (Number(params.increase) + Number(params.decrease)).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						
						
						if (Number(params.balance) > 0)
						{
							_text_value.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_text_value.addClass("text-danger");
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
								columns: [
										{ 
											data: "No_Invoice", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "No_Invoice", className: "text-center", },
										{ data: "Tgl_Invoice", className: "text-center",  },
										{ data: "Nilai", className: "text-right" },
										{ data: "Debit", className: "text-right",  },
										{ data: "Kredit", className: "text-right", },
										{ data: "Saldo", className: "text-right",  },
										{ 
											data: "Keterangan", 
											className: "text-left",  
											render: function( val){
												return val.substr(0, 30);
											}
										},
										
									],
								columnDefs  : [
										{
											"targets": ["JenisPiutang_ID"],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
									
										_datatable_actions.get_invoice_detail( row, data )
										
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
				
				var timer = 0;
				$("form[name=\"form_debit_credit_note\"]").on("submit", function(e){
					e.preventDefault();
					// untuk 
					if (timer) {
						clearTimeout(timer);
					}
					
					timer = setTimeout(postCreditDebitNotes, 600); 
						
				});
				
				function postCreditDebitNotes(){
					
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
							"transaction_type_id" : table_data.increase > 0 ? 205 : 206
					};
					
					data_post.remain = table_data.balance;
					
					data_post.nota_detail = { 
							"invoice_number" : $("#invoice_number").val(),
							"evidence_number" : table_data.invoice_number,
							"original_value" : table_data.remain,
							"debit" : table_data.increase,
							"credit" : table_data.decrease,
							"transaction_type_id" : table_data.increase > 0 ? 205 : 206,
							"transaction_date" : $("#invoice_date").val(),
							"description" : $("#description").val()
					}

					data_post.invoice_detail = { 
							"invoice_number" : table_data.invoice_number,
							"evidence_number" : $("#invoice_number").val(),
							"debit" : table_data.increase,
							"credit" : table_data.decrease,
							"transaction_type_id" : table_data.increase > 0 ? 205 : 206,
							"transaction_date" : $("#invoice_date").val(),
							"description" : $("#description").val()
					}
										
					data_post.card_receivable = {
							"type_id" : table_data.type_id,
							"customer_id" : $("#customer_id").val(),
							"invoice_number" : table_data.invoice_number,
							"evidence_number" : $("#invoice_number").val(),
							"factur_number" : $("#invoice_number").val(),
							"debit" : table_data.increase,
							"credit" : table_data.decrease,
							"date" : $("#invoice_date").val(),
							"description" : $("#description").val()
					}
				
					$.post($("form[name=\"form_debit_credit_note\"]").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( response.status == "error"){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success("<?php echo lang('global:created_successfully')?>");
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("receivable/debit_credit_note/edit"); ?>/"+ id ;
							
							}, 2000 );
						
					});				
				}
								
			});
	})( jQuery );
//]]>
</script>

 