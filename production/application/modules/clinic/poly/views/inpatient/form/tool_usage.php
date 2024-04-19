<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_tool_usage" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>No. Bukti</th>
						<th>Tanggal</th>
                        <th>Nama Alat</th>
						<th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="javascript:" id="add_tool" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Alat</b></a>
</div>


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						switch( this.index() ){
							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.IDAlat ? data.IDAlat : ''
								_input.html('<?php echo $option_tool ?>');
								_input.val(_value);
								
								_input.trigger( "focus" )
								_input.on( "change", function( e ){
									e.preventDefault();
																			
									try{
										data.IDAlat = $(this).val() || data.IDAlat;
										data.NamaAlat = $(this).find('option:selected').html() || data.NamaAlat;
										
										_datatable.row( row ).data( data );
									} catch(ex){console.log(ex);}
								});
								
								_input.on( "blur", function( e ){
									e.preventDefault();
									try{
										$( e.target ).remove();
										_datatable.row( row ).data( data );
									} catch(ex){}
								});							
								
							break;
							case 4:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ data.Jml  +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Jml = this.value || "";
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
							break;							
							
						}
					},
				remove: function( params, fn, scope ){						
						_datatable.row(scope).remove().draw();
					},
				add_row: function( params, fn, scope ){
						date = new Date();
						_datatable.row.add({
							NoBukti : '', 
							NoReg : '<?php echo $NoReg ?>', 
							SectionID : '<?php echo $SectionID ?>',
							Tanggal : date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(), 
							Jam: date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds(), 
							IDAlat : '',
							NamaAlat : '',
							Jml : 1,
						}).draw();					
					}
			};
		
		$.fn.extend({
				dt_tool_usage: function(){
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
											data: "NoBukti", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "NoBukti", 
											className: "tex-center", 
											render: function(val){
												return val ? val : '-';
											}
										},
										{ 
											data: "Jam", 
											className: "text-center",
											render: function(val, type, row){
												return row.Tanggal +' '+ row.Jam;
											}
										},
										{
											data: "NamaAlat",
											render: function(val, type, row){
												return row.IDAlat +' - '+ row.NamaAlat;
											}
										},
										{data: "Jml", className: "text-right" },
									],
								createdRow: function ( row, data, index ){
										$( row ).on( "click", "td", function(e){
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
							
						$( "#dt_tool_usage_length select, #dt_tool_usage_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_tool_usage" ).dt_tool_usage();
				$('#add_tool').on('click', function(){
					_datatable_actions.add_row();
				});
			});

	})( jQuery );
//]]>
</script>