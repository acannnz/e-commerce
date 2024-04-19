<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row mb10">
	<div class="col-md-12">
		<a href="javascript:;" data-action-url="<?php echo @$add_service_bhp ?>" data-act="ajax-modal"  data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('subtitle:service_bhp')) ?>"  data-act="ajax-modal" class="btn btn-primary btn-sm pull-right"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table id="dt_form_bhp" class="table table-bordered table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:code') ?></th>
					<th><?php echo lang('label:name') ?></th>
					<th><?php echo lang('label:unit') ?></th>
					<th><?php echo lang('label:qty') ?></th>
					<th><?php echo lang('label:charged') ?></th>
				</tr>
			</thead>        
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){

		var _datatable;		
		var _datatable_actions = {
				edit: function( row, data, index ){
												
						switch( this.index() ){									
							case 4:
							
								var _input = $( "<input type=\"text\" value=\"" + parseFloat(data.Qty) + "\" style=\"width:100%\"  class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Qty = this.value ? this.value : 1;
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
							break;
							
							case 5:
								if ( data.Ditagihkan == 0 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"1\">Ya</option>\n<option value=\"0\" selected>Tidak</option>\n</select>" );
								} else if ( data.Ditagihkan == 1 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"1\" selected>Ya</option>\n<option value=\"0\">Tidak</option>\n</select>" );
								}
								
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

											data.Ditagihkan =  $( e.target ).find( "option:selected" ).val() || 1;
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;
						}
						
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope ).remove().draw();
								
					}
			};
		
		$.fn.extend({
				dataTableFormBHP: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								scrollCollapse: true,
								data: <?php print_r(json_encode(@$collection_bhp, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "Kode_Barang", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Kode_Barang"},
										{ data: "Nama_Barang"},
										{ data: "Satuan"},
										{ data: "Qty" },
										{ 
											data: "Ditagihkan",
											render: function( val ){
												return val == 1 ? '<?php echo lang('global:yes')?>' : '<?php echo lang('global:no')?>';
											}
										}
									],
								createdRow: function ( row, data, index ){												
										$( row ).on( "click", "td",  function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
												}
											})
									}
							} );
							
						$( "#dt_trans_purchase_request_detail_length select, #dt_trans_purchase_request_detail_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});

		$( document ).ready(function(e) {
				
            	$( "#dt_form_bhp" ).dataTableFormBHP();

			});

	})( jQuery );
//]]>
</script>
