<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_credit_details" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("general_cashier:account_number_label") ?></th>
                        <th><?php echo lang("general_cashier:account_name_label") ?></th>                 
                        <th><?php echo lang("general_cashier:value_label") ?></th>
                        <th><?php echo lang("general_cashier:description_label") ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    	<a  href="<?php echo @$lookup_accounts ?>" title="<?php echo lang( "buttons:add" ) ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				add: function( factur_number, fn, scope ){
						$.post("<?php echo $create_url ?>", {"f": {"factur_number": factur_number}}, function( response, status, xhr ){
								if( "error" == response.status ){
									//$("<div>" + response.error + "</div>").dialog({title: "Error", resizable: false, modal: true, buttons: {"OK": function(){$( this ).dialog( "close" )}}});
									return false
								}
								
								if( $.isFunction(fn) ){
									fn.call( scope || _datatable )
								}
							})
					},				
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							case 1:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ trId)
								} catch(ex){}
							break;

							case 2:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ trId)
								} catch(ex){}
							break;							



							case 3:
								if (data.integration_source == "AP" || data.integration_source == "AR")
								{
									rowIndex = _datatable.row( row ).index();
									data = _datatable.row( rowIndex ).data();
									form_ajax_modal.show("<?php echo @$form_voucher_invoice ?>/dt_credit_details/"+ data.integration_source +"/"+ rowIndex)
								} else {
								
									var _input = $( "<input type=\"text\" value=\"" + (data.value || '' ) + "\" style=\"width:100%\" class=\"form-control\">" );
									this.empty().append( _input );
									
									console.log(data);
									
									_input.trigger( "focus" );
									_input.on( "blur", function(e){
											e.preventDefault();
											try{
												data.value = value_money = this.value || 0.00;
												data.value_money = Number(value_money).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
												
												_datatable.row( row ).data( data );
												_datatable_actions.calculate_total_credit();
												
											} catch(ex){}
										});
								}
								
							break;
							
							case 4:
								var _input = $( "<input type=\"text\" value=\"" + (data.description || "") + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.description = this.value || "";
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_total_credit();
											
										} catch(ex){}
									});
							break;

						}
					},
				store: function( params, fn, scope ){
						$.post("<?php echo $update_url ?>", {"f": params}, function( response, status, xhr ){
								if( "error" == response.status ){
									//$("<div>" + response.error + "</div>").dialog({title: "Error", resizable: false, modal: true, buttons: {"OK": function(){$( this ).dialog( "close" )}}});
									return false
								}
								
								_datatable_actions.calculate_balance();
								
								if( $.isFunction(fn) ){
									fn.call( scope || _datatable )
								}
							})
					},				
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				store_data_credit: function( row, data, index ){
					
					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\"data_credit\"]" );
					
					var retrieve_credit = _object_data_details.data("credit");			
					
					retrieve_credit[data.account_id] = data;
					
					_object_data_details.data( "credit", retrieve_credit );
					
					//console.log(_object_data_details.data( "credit"));
					
				},
				calculate_total_credit: function(){
					
					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\"data_credit\"]" );
					var _input_total_debit = _form_gc.find("input[id=\"total_debit\"]");
					var _input_total_credit = _form_gc.find("input[id=\"total_credit\"]");
					var _form_submit = _form_gc.find( "button[id=\"btn-submit\"]" );
					var _text_balance = _form_gc.find( "h3[id=\"text_balance\"]" );
					
					var retrieve_credit = _object_data_details.data("credit");
					var retrieve_headers = _object_data_details.data("headers");
					
					try {
						
						var table_data = $("#dt_credit_details").DataTable().rows().data();
					
						total_credit = 0;
						total_credit_money = 0;

						table_data.each(function (value, index) {

							var data_credit = retrieve_credit[value.account_id];		
							var sub_credit = 0;
							
							if ( value.integration_source == "AR" || value.integration_source == "AP" )
							{
								var data_headers = retrieve_headers[value.account_id];		
								
								$.each(data_headers, function (k, v) {
															
									sub_credit = sub_credit + Number(v.value);
									
								});		
							} else {
								
								sub_credit = Number(value.value);
							}
							
							total_credit = total_credit + sub_credit;
							total_credit_money = total_credit.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")

							sub_credit_money = sub_credit.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
							
							value.value = sub_credit;
							value.value_money = sub_credit_money;
							
							$("#dt_credit_details").DataTable().row( index ).data( value );
							
							console.log("credit: ", value);

						});
						
						//console.log("total credit: ", _input_total_credit.size(), total_credit_money);
						_input_total_credit.val( total_credit_money );

						if ( _input_total_credit.val() == _input_total_debit.val() && _input_total_debit.val() != 0 && _input_total_credit.val() != 0  )
						{
							_text_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_text_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			

						
					} catch(ex){console.log(ex)}
										
				},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_payable\"]" );
						var _text_balance = _form.find( "h3[id=\"factur_value\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_credit = 0, 
							tol_credit = 0, 
							tol_balance = 0;
						
						
						var table_data = _datatable.rows().data();
					
						table_data.each(function (value, index) {
							if (value.normal_pos == "D")
							{
								tol_credit = tol_credit + Number(value.value);
							} else {
								tol_credit = tol_credit + Number(value.value);
							}
						});

						
						tol_balance = tol_credit - tol_credit;
						console.log(tol_balance);
						_text_balance.html("Rp. "+ tol_balance.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						
						if (tol_balance > 0)
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
				dt_credit_details: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: <?php echo (isset($is_edit)) ? "true" : "false" ?>,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (isset($is_edit)) : ?>
								ajax: {
										url: "<?php echo $populate_url ?>",
										type: "POST",
										data: function( params ){},
										dataSrc: function( response ){
												
												_datatable_populate = response.data || [];
												return _datatable_populate;
											}
									},
								<?php endif; ?>
								columns: [
										{ 
											data: "id", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "account_number", className: "text-left", },
										{ data: "account_name", className: "text-left",  },
										{ data: "value_money", className: "text-left",  },
										{ data: "description", className: "text-left" },
										
									],
								columnDefs  : [
										{
											"targets": ["value","account_id","integration_source"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									_datatable_actions.calculate_total_credit();
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){

									_datatable_actions.store_data_credit(row, data, index);

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
							
						$( "#dt_credit_details_length select, #dt_credit_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_credit_details" ).dt_credit_details();
				
								
			});
	})( jQuery );
//]]>
</script>

 