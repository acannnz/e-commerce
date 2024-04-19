<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_helpers" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>No Bukti Memo</th>
                        <th>Section</th>                        
                        <th>Tanggal</th>                        
                        <th>Jam</th>                        
                        <th>ID Dokter</th>                        
                        <th>Nama Dokter</th>                        
                        <th>Memo</th>                        
                        <th>Sudah Diperiksa</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="javascript:;" id="add_helper" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Penunjang</b></a>
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

							case 2:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.SectionTujuanID ? data.SectionTujuanID : 0
								_input.load( "<?php echo $get_helper_section ?>/" + _value, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								/*_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data );
										} catch(ex){}
									});*/
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.SectionTujuanID = _selected.sectionid || 0;
											data.SectionName = _selected.sectionname || 'XX';
											
											console.log(_selected);
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;
							
							case 7:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.Memo  +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Memo = this.value || "";
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
										} catch(ex){}
									});
							break;								
						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				add_row: function( ){
						$.post( "<?php echo $get_helper_number ?>", {}, function( response, status, xhr ){
						
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							_datatable.row.add(
								{
									"NoBuktiMemo" : response.NoBuktiMemo,
									"SectionName" : "",
									"Tanggal" : '<?php echo date("Y-m-d")?>',
									"Jam" : '<?php echo date("Y-m-d H:i:s")?>',
									"DokterID" : $("#DokterID").val(),
									"Nama_Supplier" : $("#DocterName").val(),
									"Memo" : "",
									"SudahDiperiksa" : "BELUM",
									"SectionID": '<?php echo config_item('section_id') ?>',
									"SectionTujuanID": 0,
								}
							).draw(false);
														
						});	
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_helpers: function(){
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
											data: "NoBuktiMemo", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "NoBuktiMemo", 
											className: "text-center", 
										},
										{ data: "SectionName", className: "" },
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
										{ data: "DokterID", className: "text-center" },
										{ data: "Nama_Supplier", className: "" },
										{ data: "Memo", className: "" },
										{ 
											data: "SudahPeriksa", className: "text-center",
											render: function( val ) {
												if (val == 1)
												{
													return "Sudah"
												}
												
												return "Belum"
											}
										},
									],
								columnDefs  : [
										{
											"targets": ["SectionTujuanID","SectionID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
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
							
						$( "#dt_helpers_length select, #dt_helpers_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_helpers" ).dt_helpers();
				
				$("#add_helper").on("click", function(e){
					e.preventDefault();
					
					_datatable_actions.add_row();
				});

			});

	})( jQuery );
//]]>
</script>