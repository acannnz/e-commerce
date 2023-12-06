<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open("javascript:;", array("id" => "form_voucher", "name" => "form_voucher"))?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close close_form_voucher" >&times;</button> 
            <h4 class="modal-title"><?php echo lang('general_cashier:form_voucher_title') ?></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?php echo lang('general_cashier:account_number_label') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                        	<div class="input-group">
                            	<input type="hidden" id="account_id" />
								<div id="account_number" class="input-group-addon"></div>
								<div id="account_name" class="input-group-addon"></div>
                             	 <a href="javascript:;" class="btn btn-primary"><i class="fa fa-search"></i></a>
                            </div>                        
                    	</div>
                    </div>
                </div>
			</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?php echo lang('general_cashier:value_label') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" id="value" name="value" value="<?php echo @$item->value ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> placeholder="" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>        
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?php echo lang('general_cashier:description_label') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <textarea id="description_detail" name="description_detail" class="form-control" required><?php echo @$item->description ?></textarea>
                        </div>
                    </div>
                </div>
            </div>        
            
            <div class="table-responsive">
                <table id="dt_form_voucher" class="table table-bordered table-hover table-account" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo lang('general_cashier:voucher_number_label') ?></th>
                            <th><?php echo lang('general_cashier:supplier_label') ?></th>                
                            <th><?php echo lang('general_cashier:original_value_label') ?></th>                
                            <th><?php echo lang('general_cashier:value_label') ?></th>                
                            <th><?php echo lang('general_cashier:description_label') ?></th>                
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?php echo @$lookup_voucher_invoice ?>/dt_form_voucher/AP/"  title="<?php echo lang( "buttons:add" ) ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="javascript:;" id="apply_voucher" title="<?php echo lang( "buttons:apply" ) ?>" class="btn btn-primary"> <?php echo lang( "buttons:apply" ) ?></a>
                    <a href="javascript:;" title="<?php echo lang( "buttons:close" ) ?>" class="btn btn-default close_form_voucher"> <?php echo lang( "buttons:close" ) ?></a>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<?php echo lang('supplier:supplier_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">//<![CDATA[
(function( $ ){
	var _datatable;

	var rowIndex = "<?php echo $rowIndex; ?>";
	var table = "#<?php echo $table; ?>";
	var table_data = $( table ).DataTable().row( rowIndex ).data();
	var object_data_name_sparator = table.split("_");
	var object_data_name = "data_" + object_data_name_sparator[1];
	
	
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
						
						case 1:
							try{
								rowIndex = _datatable.row( row ).index();
								
								lookup_ajax_modal.show("<?php echo @$lookup_voucher_invoice ?>/dt_form_voucher/AP/"+ rowIndex);
							} catch(ex){}
						break;

						case 2:
							try{
								rowIndex = _datatable.row( row ).index();
								
								lookup_ajax_modal.show("<?php echo @$lookup_voucher_invoice ?>/dt_form_voucher/AP/"+ rowIndex);
							} catch(ex){}
						break;							

						case 4:
							try{
								rowIndex = _datatable.row( row ).index();
								data = _datatable.row( rowIndex ).data();
								lookup_ajax_modal.show("<?php echo @$lookup_voucher_invoice_details ?>/dt_form_voucher/AP/"+ rowIndex);
							} catch(ex){}
						break;
						
						case 5:
							var _input = $( "<input type=\"text\" value=\"" + (data.description || "") + "\" style=\"width:100%\" class=\"form-control\">" );
							this.empty().append( _input );
							
							_input.trigger( "focus" );
							_input.on( "blur", function(e){
									e.preventDefault();
									try{
										data.description = this.value || "";
										
										_datatable.row( row ).data( data );
										_datatable_actions.calculate_balance();
										
									} catch(ex){}
								});
						break;

					}
				},			
			add: function( ){
												
					_datatable.row.add(
						{
							"id" : 0,
							"voucher_number": "",
							"supplier_id" : 0,
							"supplier_name" : "",
							"original_value" : '',
							"original_value_money" : '',
							"value" : '',
							"value_money" : '',
							"description" : "",
						}
					).draw( true );
					
				},
			remove: function( params, fn, scope ){
					
					_datatable.row( scope )
							.remove()
							.draw(true);
							
					
				},
			store_data_headers: function( row, data, index ){
					
					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\""+ object_data_name +"\"]" );
					
					try {
						var retrieve_header = _object_data_details.data("headers");		
						
						retrieve_header[table_data.account_id] = {};
						
						retrieve_header[table_data.account_id][data.voucher_number] = data;
						
						
						// Store data
						_object_data_details.data( "headers", retrieve_header );
						$( table ).DataTable().order( [[ 0, 'asc' ]] ).draw(true);
						
						//console.log(_object_data_details.data( "headers"));
					} catch(ex){console.log(ex);}
					
				},
			retrieve_data_headers: function( ){
					
					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\""+ object_data_name +"\"]" );
					
					var _input_paid_voucher = $("#value");
					
					var retrieve_headers = _object_data_details.data("headers");
					
					//console.log(row);
					//console.log(_datatable);
					
					try {
						var value_paid = 0;
						var data_headers = retrieve_headers[table_data.account_id];
						$.each( data_headers, function( key, value){

							_datatable.row.add( value ).draw(false);
							value_paid = value_paid + Number(value.value);
							
						});

						_input_paid_voucher.val(value_paid.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));						
						$( table ).DataTable().order( [[ 0, 'asc' ]] ).draw(true);
						
					
					} catch(ex){ console.log(ex);}

				},		
			calculate_balance: function(params, fn, scope){
					
					var _form = $( "form[name=\"form_general_cashier\"]" );
										
					var table_data = _datatable.rows().data();
					total_paid = 0;
				
					table_data.each(function (value, index) {
						total_paid = total_paid + Number(value.value);
					});
					
					$("#value").val(total_paid.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));

					
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
			dt_form_voucher: function(){
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
										data: "id", 
										className: "actions text-center", 
										render: function( val, type, row, meta ){
												return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
											} 
									},
									{ data: "voucher_number", className: "text-center", },
									{ data: "supplier_name", className: "text-left", },
									{ data: "original_value_money", className: "text-left",  },
									{ data: "value_money", className: "text-left",  },
									{ data: "description", className: "text-left" },
									
								],
							columnDefs  : [
									{
										"targets": ["debit_account_id","supplier_id","original_value","value"],
										"visible": false,
										"searchable": false
									}
								],
							drawCallback: function( settings ) {
								dev_layout_alpha_content.init(dev_layout_alpha_settings);
							},
							initComplete: function(settings) {
							//	_datatable_actions.retrieve_data_headers();
							},
							createdRow: function ( row, data, index ){
									
									_datatable_actions.store_data_headers( row, data, index );
									
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
						
					$( "#dt_form_voucher_length select, #dt_form_voucher_filter input" )
					.addClass( "form-control" );
					
					return _this
				},
		});

		function lookupbox_row_selected( response ){
			var _response = JSON.parse(response)
			if( _response ){
				
				try{
				
					$("#supplier_id").val( _response.id );
					$("#supplier_code").val( _response.supplier_code );
					$("#supplier_name").val( _response.supplier_name );
						
	
				}catch(e){ console.log(e)}
	
				$( '#lookup-ajax-modal' ).remove();
				
				$("body").removeClass("modal-open");
				
			}
		}				

    $( document ).ready(function(e) {
		
		$(".close_form_voucher").on("click", function(){
			$( '#form-ajax-modal' ).remove();			
			$("body").removeClass("modal-open");			
		});
		
		$("#prepare_voucher_number").on("click", function(){
			
			_datatable_actions.add();

		});

        
        $("#account_id").val( table_data.account_id );
        $("#account_number").html( table_data.account_number );
        $("#account_name").html( table_data.account_name );
        $("#description_detail").val( table_data.description );
        $("#value").val( table_data.value );

		$( "#dt_form_voucher" ).dt_form_voucher();
		$( "#dt_form_voucher" ).data( "object", object_data_name );

		_datatable_actions.retrieve_data_headers();

       //console.log(table_data);
                            
	});
})( jQuery );
//]]></script>