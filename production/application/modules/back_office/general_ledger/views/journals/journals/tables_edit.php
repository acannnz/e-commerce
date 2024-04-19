<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_journal_details" class="table table-bordered table-sm" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("journals:account_number_label") ?></th>
                        <th><?php echo lang("journals:account_name_label") ?></th>
                        <th><?php echo lang("journals:debit_label") ?></th>
                        <th><?php echo lang("journals:credit_label") ?></th>                        
                        <th><?php echo lang("journals:notes_label") ?></th>                        
                        <th><?php echo lang("journals:section_label") ?></th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if ( $journal->state == 0 ) : ?>
<div class="row">
    <div class="col-md-12">
    	<a id="btn_add_account" href="javascript:;" title="<?php echo lang( "buttons:add" ) ?>" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				add: function( No_Bukti, fn, scope ){
						$.post("<?php echo $create_url ?>", {"f": {"No_Bukti": No_Bukti}}, function( response, status, xhr ){
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
						<?php if ( $journal->state == 0 ) : ?>
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
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ data.id +"/<?php echo $No_Bukti ?>")
								} catch(ex){}
							break;

							case 2:
								try{
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ data.id +"/<?php echo $No_Bukti ?>")
								} catch(ex){}
							break;
							
							case 3:
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Debit || 0) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Debit = this.value || 0;
											data.Kredit = data.Kredit || 0;
											data.detail_id = data.id;
											data.id = data.account_id;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
											_datatable_actions.store( data, function(){ _datatable.ajax.reload() }, row )
											
										} catch(ex){}
									});
							break;
							
							case 4:
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Kredit || 0) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Debit = data.Debit || 0;
											data.Kredit = this.value || 0;
											data.detail_id = data.id;
											data.id = data.account_id;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
											_datatable_actions.store( data, function(){ _datatable.ajax.reload() }, row )

										} catch(ex){}
									});
							break;

							case 5:
								var _input = $( "<input type=\"text\" value=\"" + (data.Keterangan || "-") + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											
											data.Keterangan = this.value || "-";
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
																						
											_datatable_actions.store( data, function(){ _datatable.ajax.reload() }, row )										} catch(ex){}
									});
							break;

							case 6:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"\" selected><?php echo lang('global:select-none') ?></option>\n</select>" );
								this.empty().append( _input );
								
								$.each(<?php print_r(json_encode($option_section, JSON_NUMERIC_CHECK)); ?>, function(index, value){
										var _option = jQuery( "<option></option>" );
										_option.val( index );
										_option.text( value );
										
										_input.append( _option );
									});
									
								_input.val( data.SectionID);
								
								_input.trigger( "focus" );
								
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{

											data.SectionID =  $( e.target ).find( "option:selected" ).val() || 0;
											data.SectionName =  $( e.target ).find( "option:selected" ).html() || '';
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});

							break;
							
						}
						<?php endif; ?>
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
						
						var _form = $( "form[name=\"form_general_ledger\"]" );
						var _form_debit = _form.find( "input[id=\"Debit\"]" );
						var _form_credit = _form.find( "input[id=\"Kredit\"]" );
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
				dt_journal_details: function(){
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
										{ 
											data: "Akun_No", 
											className: "", 
											width: "15%",
											render: function( val, type, row, meta ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ data: "Akun_Name", className: "text-left" },
										{ data: "Debit", className: "text-left", width: "10%" },
										{ data: "Kredit", className: "text-left", width: "10%" },
										{ data: "Keterangan", className: "text-left", width: "20%" },
										{ data: "SectionName", className: "text-left", width: "15%" },
									],
								columnDefs  : [
										{
											"targets": ["Akun_ID", "SectionID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
									<?php if ($item->Posted == 0 ) :?>
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
											});
										<?php endif; ?>
										_datatable_actions.calculate_balance();
									
	
									}
							} );
							
						$( "#dt_journal_details_length select, #dt_journal_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_journal_details" ).dt_journal_details();
				
				$( "#btn_add_account" ).on("click", function(e){ 
						e.preventDefault();	
						_datatable_actions.add("<?php echo $No_Bukti ?>", function(){
								this.ajax.reload()
							});
						
				});
												
			});
	})( jQuery );
//]]>
</script>

 