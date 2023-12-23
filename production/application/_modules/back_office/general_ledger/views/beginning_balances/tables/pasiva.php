<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="page-subtitle text-center">
    <h2 class="text-info"><i class="fa fa-arrow-circle-down text-info"></i> <?php echo lang('beginning_balances:pasiva_sub') ?></h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt-table-pasiva" class="table table-sm" width="100%">
                <thead>
                    <tr>
                        <th><?php echo lang("beginning_balances:account_number_label") ?></th>
                        <th><?php echo lang("beginning_balances:account_name_label") ?></th>
                        <th><?php echo lang("beginning_balances:currency_label") ?></th>
                        <th><?php echo lang("beginning_balances:value_label") ?></th>
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
		var _datatable_pasiva;
		
		var _datatable_pasiva_populate;
		var _datatable_pasiva_actions = {	
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 3:
									if (data.Induk == 1) return;
									var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Nilai || 0) + "\" style=\"width:100%\" class=\"form-control\">" );
									this.empty().append( _input );
									
									_input.trigger( "focus" );
									_input.on( "blur", function(e){
											e.preventDefault();
											try{
												data.Nilai = this.value || 0;
												
												_datatable_pasiva.row( row ).data( data );
												_datatable_pasiva_actions.calculate_balance();
												
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
								
								_datatable_pasiva_actions.calculate_balance();
								
								if( $.isFunction(fn) ){
									fn.call( scope || _datatable_pasiva )
								}
							})
					},				
				calculate_balance: function( isDraw ){
						
						var _form_activa = $( "a[id=\"activa\"]" );
						var _form_pasiva = $( "a[id=\"pasiva\"]" );
						var _form_submit = $( "a[id=\"submit\"]" );
						var _info_balance = $( "h2[id=\"info_balance\"]" );
						
						var tol_pasiva = 0;

						var table_data = $( "#dt-table-pasiva" ).DataTable().rows().data();
						
						table_data.each(function (value, index) {
							var Nilai = value.Nilai;
							
							Nilai = Nilai == '-' ? 0.00 : Nilai ;
							
							tol_pasiva = tol_pasiva + parseFloat( Nilai );
							
						});
						
						var Nilai = parseFloat( tol_pasiva ).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")

						_form_pasiva.html( Nilai );
						
						if (!isDraw )
						{
							if ( _form_activa.html() == _form_pasiva.html() && !isDraw )
							{
								_form_activa.removeClass("btn-danger");
								_form_pasiva.removeClass("btn-danger");
								_form_submit.removeAttr("disabled");
								_info_balance.text("<?php echo lang("beginning_balances:balance_label") ?>");
							} else {
								_form_activa.addClass("btn-danger");
								_form_pasiva.addClass("btn-danger");
								_form_submit.attr("disabled");
								_info_balance.text("<?php echo lang("beginning_balances:not_balance_label") ?>");
							}			
						}
					},	
			};
		
		$.fn.extend({
				dt_table_pasiva: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable_pasiva = _this.DataTable( {
								processing: true,
								serverSide: true,								
								paginate: false,
								paging: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: true,
								responsive: true,
								scrollY: "600px",
								scrollCollapse: true,
								ajax: {
										url: "<?php echo $populate_url ?>",
										type: "POST",
										data: function( params ){},
										dataSrc: function( response ){
												
												_datatable_pasiva_populate = response.data || [];
												return _datatable_pasiva_populate;
											}
									},
								columns: [
										{ 
											data: "Akun_No", 
											className: "", 
											render: function( val, type, row, meta ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ data: "Akun_Name", className: "text-left" },
										{ data: "Currency_Code", className: "text-left" },
										{ 	
											data: "Nilai", 
											width: "25%",
											className: "text-right", 
										},
									],
								columnDefs  : [
										{
											"targets": ["Akun_ID", "Induk"],
											"visible": false,
											"searchable": false
										},
									],
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_pasiva_actions.edit.call( elem, row, data, index );

											});
									},
								drawCallback: function( settings ) {
									
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_pasiva_actions.calculate_balance( true );
									
								}
							} );
							
						$( "#dt-table-pasiva_length select, #dt-table-pasiva_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt-table-pasiva" ).dt_table_pasiva();
				
				
				
			});
	})( jQuery );
//]]>
</script>

 