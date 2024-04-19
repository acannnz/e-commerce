<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_account_concept", "name" => "form_account_concept") ); ?>
<input type="hidden" id="Setup_ID" name="f[Setup_ID]" value="<?php echo @$item->Setup_ID ?>">
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('concepts:max_level_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="number" id="Jumlah_Level" name="f[Jumlah_Level]" value="<?php echo @$item->Jumlah_Level ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('concepts:max_digit_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="number" id="Jumlah_Digit" name="f[Jumlah_Digit]" value="<?php echo @$item->Jumlah_Digit ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('concepts:description_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control"><?php echo @$item->Keterangan ?></textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_concept_details" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("concepts:level_to_label") ?></th>
                        <th><?php echo lang("concepts:digit_number_label") ?></th>                        
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
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							case 1:
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Level_Ke || 0) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Level_Ke = this.value || 0;
											
											_datatable.row( row ).data( data );											
										} catch(ex){}
									});
							break;
							
							case 2:
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Jumlah_Digit || 0) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Jumlah_Digit = this.value || 0;
											data.Jumlah_Digit2 = this.value || 0;
											
											_datatable.row( row ).data( data );											
										} catch(ex){}
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
								"ID" : 0,
								"Setup_ID" : $("#Setup_ID").val(),
								"Level_Ke" : 0,
								"Jumlah_Digit" : 0,
								"Jumlah_Digit2" : 0,
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_concept_details: function(){
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
											data: "ID", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "Level_Ke", 
											className: "text-center", 
											render: function( val, type, row ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ 
											data: "Jumlah_Digit", 
											className: "text-center", 
											render: function( val, type, row ){ 
												return "<b>"+ val +"</b>";
											} 
										},
									],
								columnDefs  : [
										{
											"targets": ["ID","Setup_ID", "Jumlah_Digit2"],
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
							
						$( "#dt_concept_details_length select, #dt_concept_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_concept_details" ).dt_concept_details();
				_datatable_actions.init();
				
				$("form[name=\"form_account_concept\"]").on("submit", function(e){
					e.preventDefault();	

					var data_post = {};
						data_post["f"] = {
							"Jumlah_Level" : $("#Jumlah_Level").val(),
							"Jumlah_Digit" : $("#Jumlah_Digit").val(),
							"Keterangan" : $("#Keterangan").val()
						};
						data_post["details"] = {};
					var validation = true;
					
					var table_data = $( "#dt_concept_details" ).DataTable().rows().data();
					
					table_data.each(function (value, index) {		
						if ( value.Level_Ke > Number( $("#Jumlah_Level").val()) || value.Jumlah_Digit > Number($("#Jumlah_Digit").val()) ){
							validation = false;
							return false;
						}
						data_post["details"][index] = value;
					});
					
					if (!validation){
						$.alert_error( '<?php echo lang("concepts:level_digit_exceed_max") ?>' );
						return false;
					}
									
					$.post($(this).prop("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON( response );

						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						$("#dt-account-concepts").DataTable().ajax.reload();
						
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open");						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>
