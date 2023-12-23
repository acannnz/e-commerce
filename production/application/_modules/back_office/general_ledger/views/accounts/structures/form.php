<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_account_structure", "name" => "form_account_structure") ); ?>
<input type="hidden" id="Komponen" name="f[Komponen]" value="<?php echo @$item->Komponen ?>">
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('structures:group_name_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <input type="text" id="Group_Name" name="f[Group_Name]" value="<?php echo @$item->Group_Name ?>" placeholder="" class="form-control" required>
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('structures:description_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control"><?php echo @$item->Keterangan ?></textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_structure_details" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("structures:group_account_detail_label") ?></th>
                        <th><?php echo lang("structures:cash_label") ?></th>                        
                        <th><?php echo lang("structures:bank_label") ?></th>                        
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
    	<a id="btn_add_detail" href="javascript:;" title="<?php echo lang( "buttons:add" ) ?>" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {		
				init: function(){
						$( "#btn_add_detail" ).on("click", function(e){ 
							_datatable_actions.add();
						
						});
					},
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
												}
								} catch(ex){}
								
							break;
							
							case 1:
								var _input = $( "<input type=\"text\" value=\"" + (data.GroupAkunDetailName || '') + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.GroupAkunDetailName = this.value || "";
											
											_datatable.row( row ).data( data );											
										} catch(ex){}
									});
							break;
							
							case 2:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" "+ (data.Cash == 0 ? "selected" : '') +">Tidak</option>\n<option value=\"1\" "+ (data.Cash == 1 ? "selected" : '') +">Ya</option>\n</select>" );
								this.empty().append( _input );
								
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

											data.Cash =  $( e.target ).find( "option:selected" ).val() || 0;
											data.Bank = data.Cash == 1 ? 0 : data.Bank;
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;

							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" "+ (data.Bank == 0 ? "selected" : '') +">Tidak</option>\n<option value=\"1\" "+ (data.Bank == 1 ? "selected" : '') +">Ya</option>\n</select>" );
								this.empty().append( _input );
								
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

											data.Bank =  $( e.target ).find( "option:selected" ).val() || 0;
											data.Cash = data.Bank == 1 ? 0 : data.Cash;
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});

							break;							
						}
					},		
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
					},
				add: function( params, fn, scope ){
						_datatable.row.add(
							{
								"GroupAkunDetailId" : 0,
								"GroupAkunDetailName" : '',
								"Cash" : 0,
								"Bank" : 0,
							}
						).draw(false);
						
						
					}
			};
		
		$.fn.extend({
				dt_structure_details: function(){
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
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								columns: [
										{ 
											data: "GroupAkunDetailId", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "GroupAkunDetailName", 
											render: function( val, type, row ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ 
											data: "Cash", 
											className: "text-center", 
											render: function( val, type, row ){ 
												return val == 1 ? "<?php echo lang("global:yes")?>" : "<?php echo lang("global:no")?>"
											} 
										},
										{ 
											data: "Bank", 
											className: "text-center", 
											render: function( val, type, row ){ 
												return val == 1 ? "<?php echo lang("global:yes")?>" : "<?php echo lang("global:no")?>"
											} 
										},
									],
								columnDefs  : [
										{
											"targets": [],
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
							
						$( "#dt_structure_details_length select, #dt_structure_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_structure_details" ).dt_structure_details();
				_datatable_actions.init();
				
				$("form[name=\"form_account_structure\"]").on("submit", function(e){
					e.preventDefault();	

					var data_post = {};
						data_post["f"] = {
							"Komponen" : $("#Komponen").val(),
							"Group_Name" : $("#Group_Name").val(),
							"Keterangan" : $("#Keterangan").val()
						};
						data_post["details"] = {};
		
					var table_data = $( "#dt_structure_details" ).DataTable().rows().data();
					
					table_data.each(function (value, index) {	
						value.NoUrut = index + 1;	
						data_post["details"][index] = value;
					});		
									
					$.post($(this).prop("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON( response );

						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						$("#structure_tree").jstree(true).refresh();
						
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open");						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>
