<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_factur_details" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("facturs:description_label") ?></th>
                        <th><?php echo lang("facturs:value_label") ?></th>
                        <th><?php echo lang("facturs:account_number_label") ?></th>
                        <th><?php echo lang("facturs:account_name_label") ?></th>                 
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
				dt_factur_details: function(){
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
										{ data: "value", className: "text-left",  },
										{ data: "account_number", className: "text-left", },
										{ data: "account_name", className: "text-left",  },

									],
								columnDefs  : [
										{
											"targets": ["account_id"],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
			
										_datatable_actions.calculate_balance();
									
	
									}
							} );
							
						$( "#dt_factur_details_length select, #dt_factur_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_factur_details" ).dt_factur_details();
								
			});
	})( jQuery );
//]]>
</script>

 