<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close close_form_voucher_detail">&times;</button> 
            <h4 class="modal-title"><?php echo lang('general_cashier:form_voucher_title') ?></h4>
        </div>
        <div class="modal-body">
        	<input type="hidden" id="voucher_id" name="voucher_id"  />
            <div class="row form-group">
            	<div class="col-md-12">
            		<label class="control-label col-md-4"><?php echo lang("general_cashier:voucher_number_label")?></label>
                    <div class="col-md-8">
                    	<input type="text" id="voucher_number" name="voucher_number" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row form-group">
            	<div class="col-md-12">
            		<label class="control-label col-md-4"><?php echo lang("general_cashier:supplier_label")?></label>
                    <div class="col-md-8">
                    	<input type="text" id="supplier_name" name="supplier_name" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row form-group">
            	<div class="col-md-12">
            		<label class="control-label col-md-4"><?php echo lang("general_cashier:original_value_label")?></label>
                    <div class="col-md-8">
                    	<input type="text" id="original_value" name="original_value" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row form-group">
            	<div class="col-md-12">
            		<label class="control-label col-md-4"><?php echo lang("general_cashier:value_label")?></label>
                    <div class="col-md-8">
                    	<input type="text" id="paid_voucher" name="paid_voucher" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row form-group">
            	<div class="col-md-12">
            		<label class="control-label col-md-4"><?php echo lang("general_cashier:description_label")?></label>
                    <div class="col-md-8">
                    	<textarea id="description" name="description" class="form-control"> </textarea>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="dt_form_voucher_detail" class="table table-bordered table-hover table-account" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo lang('general_cashier:factur_number_label') ?></th>
                            <th><?php echo lang('general_cashier:remain_label') ?></th>                
                            <th><?php echo lang('general_cashier:paid_label') ?></th>                
                            <th><?php echo lang('general_cashier:description_label') ?></th>                
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="javascript:;" id="prepare_voucher_number" title="<?php echo lang( "buttons:add" ) ?>" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="javascript:;" id="apply_voucher_detail" title="<?php echo lang( "buttons:apply" ) ?>" class="btn btn-primary"> <?php echo lang( "buttons:apply" ) ?></a>
                    <a href="javascript:;" title="<?php echo lang( "buttons:close" ) ?>" class="btn btn-default close_form_voucher_detail"> <?php echo lang( "buttons:close" ) ?></a>
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
	var _datatable_detail;
	
	var rowIndex = "<?php echo $rowIndex; ?>";
    var table = "#<?php echo $table; ?>";
	var object_data_name = $( table ).data("object");
	
	var _datatable_populate_detail;
	var _datatable_actions_detail = {			
			edit: function( row, data, index ){
					
					switch( this.index() ){

						case 2:
						
							var _input = $( "<input type=\"text\" value=\"" + (data.remain || '' ) + "\" style=\"width:100%\" class=\"form-control\">" );
							this.empty().append( _input );
							
							_input.trigger( "focus" );
							_input.on( "blur", function(e){
									e.preventDefault();
									try{
										data.paid = paid_money = this.value || 0.00;
										data.paid_money = Number(paid_money).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										
										_datatable_detail.row( row ).data( data );
										_datatable_actions_detail.store_data_detail();
										
									} catch(ex){}
								});
							
						break;
					}
				},			
			retrieve_data_detail: function( row ){
					
					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\""+ object_data_name +"\"]" );
					
					var _input_paid_voucher = $("#paid_voucher");
					
					var retrieve_details = _object_data_details.data("details");
					
					//console.log(row);
					//console.log(details);
					try {
						var data_detail = retrieve_details[row.voucher_number][row.factur_number];
						//console.log(data_detail);
						
						return data_detail;	
					} catch(ex){}

					return row;
										
				},
			calculate_total_paid: function( row ){
					
					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\""+ object_data_name +"\"]" );
					
					var _input_paid_voucher = $("#paid_voucher");
					
					var retrieve_details = _object_data_details.data("details");
					
					try {
						
						var data_detail = retrieve_details[row.voucher_number];
						var paid = 0;
	
						$.each(data_detail, function (key, value) {
													
							paid = paid + Number(value.paid);
							
						});		
									
						var paid_money = Number(paid).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
						//console.log(paid_money);
						_input_paid_voucher.val( paid_money );
						
					} catch(ex){console.log(ex)}
										
				},
			store_data_detail: function(){

					var _form_gc = $( "form[name=\"form_general_cashier\"]" );
					var _object_data_details = _form_gc.find( "input[id=\""+ object_data_name +"\"]" );

					var _form_voucher = $( "form[name=\"form_voucher\"]" );
					var _input_voucher_value = _form_voucher.find( "input[id=\"value\"]" );

					var paid = 	0;

					// Data Header Invoice
					header_data = $( table ).DataTable().row( rowIndex ).data();
					
					// Data Detail voucher (Factur)
					var table_data = _datatable_detail.rows().data();
					details = _object_data_details.data("details");
					details[header_data.voucher_number] = {};

					table_data.each(function (value, index) {
						details[header_data.voucher_number][value.factur_number] = value;
						
						paid = paid + Number(value.paid);
						
					});
					
					var paid_money = Number(paid).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
						
					$("#paid_voucher").val( paid_money );
					
					// Update datatable Invoice
					//headers = _object_data_details.data("headers");;
					header_data['value'] = paid;
					header_data['value_money'] = paid_money;
					//headers[header_data.voucher_number] = header_data; 
					
					$( table ).DataTable().row( rowIndex ).data( header_data ).draw(true);
					$( "#dt_debit_details" ).DataTable().order( [[ 0, 'asc' ]] ).draw(true);
					$( "#dt_credit_details" ).DataTable().order( [[ 0, 'asc' ]] ).draw(true);
					
					// Tampil total bayar pada form voucher
					_input_voucher_value.val( paid_money )
					
					
					// Simpan data header dan details pada object HTML
					//_object_data_details.data("headers", headers);
					_object_data_details.data("details", details);
					
					//console.log(_object_data_details.data("headers"));
					//console.log(_object_data_details.data("details"));
										
				},
			
		};
	
	$.fn.extend({
			dt_form_voucher_detail: function(){
					var _this = this;
					
					if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
						return _this
					}
					
					_datatable_detail = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: false,
							searching: false,
							info: false,
							autoWidth: false,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("payable/vouchers/lookup_detail_collection") ?>",
									type: "POST",
									data: function( params ){
										params.voucher_number = $("#voucher_number").val();
									}
								},
							columns: [
									{ data: "factur_number", className: "text-center", },
									{ data: "remain_money", className: "text-left", },
									{ 
										data: "voucher_number", 
										className: "text-right",  
										render: function( val, type, row, meta ){
											var data_detail = _datatable_actions_detail.retrieve_data_detail( row );
											
											return data_detail.paid;
										}
									},
									{ data: "description", className: "text-left" },
									
								],
							columnDefs  : [
									{
										"targets": ["voucher_id","voucher_number","supplier_id","original_value","paid"],
										"visible": false,
										"searchable": false
									}
								],
							drawCallback: function( settings ) {
								//_datatable_actions_detail.calculate_total_paid();
								dev_layout_alpha_content.init(dev_layout_alpha_settings);
							},
							createdRow: function ( row, data, index ){
									$( row ).on( "dblclick", "td", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											_datatable_actions_detail.edit.call( elem, row, data, index );
										});
										
									$( row ).on( "click", "a.btn-remove", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											
											if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
												_datatable_actions_detail.remove( data, function(){ _datatable.ajax.reload() }, row )
											}
										})
								}
						} );
						
					$( "#dt_form_voucher_detail_length select, #dt_form_voucher_detail_filter input" )
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

		$(".close_form_voucher_detail").on("click", function(){
			
			$( '#lookup-ajax-modal' ).remove();			
			$("body").removeClass("modal-open");			
			
		});
		
        
        var data = $( table ).DataTable().row( rowIndex ).data();
        
        $("#voucher_id").val( data.voucher_id );
        $("#voucher_number").val( data.voucher_number );
        $("#original_value").val( data.original_value_money );
        $("#paid_voucher").val( data.value_money );
		
        $("#supplier_name").val( data.supplier_name );
        $("#description").val( data.description );
		
        //console.log(data);
                            
		$( "#dt_form_voucher_detail" ).dt_form_voucher_detail();
		
	//	_datatable_actions_detail.calculate_total_paid( data );
		
	});
})( jQuery );
//]]></script>