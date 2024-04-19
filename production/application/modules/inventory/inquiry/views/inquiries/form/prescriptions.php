<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_prescriptions" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tanggal</th>
                        <th>Jam</th>                        
                        <th>ID Dokter</th>                        
                        <th>Nama Dokter</th>                        
                        <th>Jumlah</th>                        
                        <th>Cito</th>                        
                        <th>Realisasi</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$create_prescription ?>" id="add_prescripstions" data-toggle="form-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Resep</b></a>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						if ( this.index() == 0 ) {
								
							try{
								if( confirm( "<?php echo lang('poly:delete_confirm_message') ?>" ) ){
												_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
											}
							} catch(ex){}
								
						}
						
						if ( this.index() > 0 ) {
							try{
								
								form_ajax_modal.show("<?php echo $view_prescription ?>/"+ data.NoResep)
							} catch(ex){}
						}
							
					},
				remove: function( params, fn, scope ){
												
						$.post( "<?php echo $delete_prescription ?>", { "NoResep" : params.NoResep }, function( response, status, xhr ){
						
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							
							_datatable.row( scope )
									.remove()
									.draw(true);
														
						});	
														
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_general_ledger\"]" );
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
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_prescriptions: function(){
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
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "NoResep", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "Tanggal", className: "text-center", 
											render: function( val ){
												return val.substring(0, 10)
											}
										},
										{ 
											data: "Jam", className: "text-center",
											render: function( val ) {
												return val.substring(11, 19)
											}
										},
										{ data: "DokterID", className: "" },
										{ data: "Nama_Supplier", className: "" },
										{ 
											data: "Jumlah", className: "text-right",
											render: function(val){
												return parseFloat(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
											} 
										},
										{ 
											data: "Cyto", className: "text-center",
											render: function ( val ) {
												if ( val == 1)
												{
													return "YA"
												}
												
												return "TIDAK"
											}
										},
										{ 
											data: "Realisasi", className: "text-center",
											render: function ( val ) {
												if ( val == 1)
												{
													return "SUDAH"
												}
												
												return "BELUM"
											}
										},
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										$( "td", row ).on( "dblclick", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( "a.btn-remove", row ).on( "click", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('poly:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									}
							} );
							
						$( "#dt_prescriptions_length select, #dt_prescriptions_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_prescriptions" ).dt_prescriptions();
				
								
				$("form[name=\"form_registrations\"]").on("submit", function(e){
					e.preventDefault();	
					
					var data_post = { };
						data_post['f'] = {};
						data_post['p'] = {};
						
					var data_form = $(this).serializeArray();
					
					var details = {};
					
					var table_data = $( "#dt_prescriptions" ).DataTable().rows().data();
					
					$.each( data_form, function( index, value ){
						//var field_name = $("input[name=\""+ value.name +"\"]").prop("id");
						last_lenght = value.name.length - 1;
						var field_name = value.name.substring(2,last_lenght);

						var group = value.name.substring(0,2);
						
						if( group == "f[" ) {
							data_post['f'][field_name] = value.value;
						} else if( group == "p[" ) {
							data_post['p'][field_name] = value.value;
						} else { 
							data_post[value.name] = value.value;
						}
						console.log( group +" "+ value.name +" "+ field_name);
						
					});
					
					data_post['destinations'] = {};
					
					table_data.each(function (value, index) {
						var detail = {
							"NoReg" : $("#RegNo").val(),
							"SectionID"	: value.SectionID,
							"DokterID" : value.DokterID,
							"NoUrut" : value.NoUrut,
							"WaktuID" : value.WaktuID,
							"JenisKerjasamaID" : $("#JenisKerjasamaID").val(),
							"UmurThn" : $("#UmurThn").val(),
							"UmurBln" : $("#UmurBln").val(),
							"UmurHr" : $("#UmurHr").val()
						}
						
						data_post['destinations'][index] = detail;
					});
					console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}
						
						$.alert_success("<?php echo lang('global:created_successfully')?>");
						
						var NoReg = response.NoReg;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("registrations/edit"); ?>/"+ NoReg;
							
							}, 3000 );
						
					})	
				});

			});

	})( jQuery );
//]]>
</script>