<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_debit_details" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("general_cashier:description_label") ?></th>
                        <th><?php echo lang("general_cashier:value_label") ?></th>
                        <th><?php echo lang("general_cashier:account_number_label") ?></th>
                        <th><?php echo lang("general_cashier:account_name_label") ?></th>                 
                        <th><?php echo lang("general_cashier:normal_pos_label") ?></th>                 
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
						<?php if ( !$is_edit ) : ?>
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							
							case 1:
								var _input = $( "<input type=\"text\" value=\"" + (data.description || "") + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.description = this.value || "";
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
											_datatable_actions.store( data, function(){ _datatable.ajax.reload() }, row )
											
										} catch(ex){}
									});
							break;

							case 2:
								var _input = $( "<input type=\"number\" value=\"" + Number(data.value || 0.00) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.value = this.value || 0;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
											_datatable_actions.store( data, function(){ _datatable.ajax.reload() }, row )
											
										} catch(ex){}
									});
							break;
						<?php endif; ?>
						<?php if ( !$factur->posted && !$factur->close_book ) :?>
							case 3:
								try{
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ data.id +"/<?php echo $factur->id ?>")
								} catch(ex){}
							break;

							case 4:
								try{
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ data.id +"/<?php echo $factur->id ?>")
								} catch(ex){}
							break;
						<?php endif; ?>
							
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
						$.post("<?php echo $delete_url ?>", {"f": params}, function( response, status, xhr ){
								if( "error" == response.status ){
									//$("<div>" + response.error + "</div>").dialog({title: "Error", resizable: false, modal: true, buttons: {"OK": function(){$( this ).dialog( "close" )}}});
									return false
								}
								
								_datatable.row( scope )
										.remove()
										.draw(false);
								
								_datatable_actions.calculate_balance();
								
								if( $.isFunction(fn) ){
									fn.call( scope || _datatable )
								}
							})
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_payable\"]" );
						var _form_debit = _form.find( "input[id=\"debit\"]" );
						var _form_credit = _form.find( "input[id=\"credit\"]" );
						var _form_balance = _form.find( "input[id=\"balance\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_debit = 0, 
							tol_credit = 0, 
							tol_balance = 0;
						
						var rows = _datatable.rows().nodes();
						
						for( var i=0; i<rows.length; i++ )
						{
							
							tol_debit = tol_debit + Number($(rows[i]).find("td:eq(3)").html());
							tol_credit = tol_credit + Number($(rows[i]).find("td:eq(4)").html());
							
						}
						
						tol_balance = tol_debit - tol_credit;

						_form_debit.val(tol_debit);	
						_form_credit.val(tol_credit);
						_form_balance.val(tol_balance);
						
						if (tol_balance == 0)
						{
							_form_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_form_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
					
			};
		
		$.fn.extend({
				dt_debit_details: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: true,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								ajax: {
										url: "<?php echo $populate_url ?>",
										type: "POST",
										data: function( params ){},
										dataSrc: function( response ){
												
												_datatable_populate = response.data || [];
												return _datatable_populate;
											}
									},
								columns: [
										{ 
											data: "id", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "description", className: "text-left" },
										{ 
											data: "value_money", 
											className: "text-left",  
											render: function ( val, type, row, meta){
													return Number(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
												}
										},
										{ data: "account_number", className: "text-left", },
										{ data: "account_name", className: "text-left",  },
										{ data: "normal_pos", className: "text-left",  },

									],
								columnDefs  : [
										{
											"targets": ["account_id","value"],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										<?php if ( !$factur->posted && !$factur->close_book ) :?>
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											<?php if ( !@$is_edit ) :?>
												$( row ).on( "click", "a.btn-remove", function(e){
														e.preventDefault();												
														var elem = $( e.target );
														
														if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
															_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
														}
													});
											<?php endif; ?>
										<?php endif; ?>
										_datatable_actions.calculate_balance();
	
									}
							} );
							
						$( "#dt_debit_details_length select, #dt_debit_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_debit_details" ).dt_debit_details();
				

				$("form[name=\"form_payable\"]").on("submit", function(e){
					e.preventDefault();	

					var data_post = $(this).serializeArray();
					
					console.log(data_post);
				
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}
						
						$.alert_success("<?php echo lang('global:updated_successfully')?>");
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("payable/general_cashier/edit"); ?>/"+ id ;
							
							}, 3000 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 