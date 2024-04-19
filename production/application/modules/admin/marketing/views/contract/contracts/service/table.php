<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row mb10">
	<div class="col-md-6">
		<h4><?php echo lang('subtitle:contract_service_list')?></h4>
	</div>
	<div class="col-md-6">
		<a href="javascript:;" data-action-url="<?php echo @$add_contract_service ?>" data-act="ajax-modal"  data-title="<?php echo lang('subtitle:list_service')?>"  data-modal-lg="1" class="btn btn-primary btn-sm pull-right"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table id="dt_contract_service" class="table table-bordered table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:code') ?></th>
					<th><?php echo lang('label:name') ?></th>
					<th><?php echo lang('label:doctor') ?></th>
					<th><?php echo lang('label:old_price') ?></th>
					<th><?php echo lang('label:new_price') ?></th>
					<th><?php echo lang('label:date') ?></th>
					<th><?php echo lang('label:class') ?></th>
					<th><?php echo lang('label:location') ?></th>
					<th><?php echo lang('label:include') ?></th>
					<th><?php echo lang('label:auto_service') ?></th>
				</tr>
			</thead>        
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<hr/>

<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script type="text/javascript">
//<![CDATA[
(function( $ ){

		var _datatable, _datatable_component;		
		var _datatable_actions = {
				edit: function( row, data, index ){
												
						switch( this.index() ){									
							case 9:
								if ( data.Included == 0 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"1\">Ya</option>\n<option value=\"0\" selected>Tidak</option>\n</select>" );
								} else if ( data.Included == 1 ) {
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

											data.Included =  $( e.target ).find( "option:selected" ).val() || 1;
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;
							
							case 10:
								if ( data.AutoSystem == 0 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"1\">Ya</option>\n<option value=\"0\" selected>Tidak</option>\n</select>" );
								} else if ( data.AutoSystem == 1 ) {
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

											data.AutoSystem =  $( e.target ).find( "option:selected" ).val() || 1;
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;
						}
						
					},
				show_service_component: function( components, _tr ){
						var component_dom = $('<div class="col-md-offset-1 col-md-10">'+
								'<dl class="chart-details-list">'+
									'<div class="table-responsive">'+
										'<table id="dt_component" class="table table-bordered" width="100%">'+
											'<thead>'+
												'<tr>'+
													'<th><?php echo lang("label:component") ?></th>'+
													'<th class="text-right"><?php echo lang("label:old_price") ?></th>'+
													'<th class="text-right"><?php echo lang("label:new_price") ?></th>'+   
												'</tr>'+
											'</thead>'+
											'<tbody>'+
											'</tbody>'+
										'</table>'+
									'</div>'+
								'</dl>'+
							'</div>');
						
						$.each(components, function(i, v){
							var component_detail = $('<tr>'+
								'<input type="hidden" class="component_id form-control text-right" value="'+ i +'" />'+
								'<td>'+ v.KomponenName +'</th>'+
								'<td class="text-right"><input type="text" class="Harga_Lama form-control text-right" value="'+ mask_number.currency_add(v.Harga_Lama) +'" /></td>'+
								'<td class="text-right"><input type="text" class="Harga_Baru form-control text-right" value="'+ mask_number.currency_add(v.Harga_Baru) +'" /></td>'+
							'</tr>');
							
							component_dom.find('tbody').append( component_detail );
						});
						
						$(component_dom).on('focus', '.Harga_Baru, .Harga_Lama', function(e){
							$(this).val(mask_number.currency_remove(e.target.value));
						});
						
						$(component_dom).on('blur', '.Harga_Baru, .Harga_Lama', function(e){
							$(this).val(mask_number.currency_add(e.target.value));
						});
						
						$(component_dom).on('keyup', '.Harga_Baru, .Harga_Lama', function(e){
							_datatable_actions.calculate_service_component( component_dom, _tr);
						});
						
						return component_dom;
					},
				calculate_service_component: function( component_dom, _tr ){
						var contract_service_data = $("#dt_contract_service").DataTable().row(_tr).data();
						var Harga_Lama = Harga_Baru = 0;
						
						if (typeof contract_service_data !== 'undefined') { 
							
							$.each(component_dom.find('tbody > tr'), function(index, value){
								var td = $(value);
								var old_component = mask_number.currency_remove(td.find('.Harga_Lama').val());
								var new_component = mask_number.currency_remove(td.find('.Harga_Baru').val());
								
								contract_service_data.components[td.find('.component_id').val()]['Harga_Lama'] = old_component;
								contract_service_data.components[td.find('.component_id').val()]['Harga_Baru'] = new_component;								
								Harga_Lama = Harga_Lama + old_component;
								Harga_Baru = Harga_Baru + new_component;
							});
							
							contract_service_data.Harga_Lama = Harga_Lama;
							contract_service_data.Harga_Baru = Harga_Baru;
							$('#dt_contract_service').DataTable().row(_tr).data(contract_service_data);
						}
					},
				remove_price: function( params, fn, scope ){
						_datatable.row( scope ).remove().draw();						
						$('#js_add_service_component').addClass('disabled');
					},
			};
		
		$.fn.extend({
				dataTableContractService: function(){
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
								data: <?php print_r(json_encode(@$collection_service, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "ListHargaID", 
											className: "actions text-center",
											width: '100px',
											render: function( val, type, row, meta ){
												var buttons = '<div class="btn-group" role="group">';
													buttons = '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn btn-danger btn-xs btn-remove"><i class="fa fa-trash"></i></a>';
													buttons += '<a href="javascript:;" title="<?php echo lang('buttons:detail') ?>" class="btn btn-success btn-xs js-component-expand"><i class="fa fa-expand"></i> <?php echo lang('buttons:component')?></a>';
												buttons += '</div>';
												return buttons;
											} 
										},
										{ data: "JasaID"},
										{ 
											data: "JasaName",
											width: '250px'
										},
										{ data: "NamaDokter"},
										{ 
											data: "Harga_Lama",
											className: 'text-right',
											render: function(val){
												return mask_number.currency_add(val)
											} 
										},
										{ 
											data: "Harga_Baru", 
											className: 'text-right',
											render: function(val){
												return mask_number.currency_add(val)
											} 
										},
										{ data: "TglUpdate", width: '120px'},
										{ data: "NamaKelas" },
										{ data: "Lokasi" },
										{ 
											data: "Included",
											width: '100px',
											render: function(val){
												return val ? '<?php echo lang('global:yes')?>' : '<?php echo lang('global:no')?>'
											}
										},
										{ 
											data: "AutoSystem",
											render: function(val){
												return val ? '<?php echo lang('global:yes')?>' : '<?php echo lang('global:no')?>'
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
												_datatable_actions.remove_price( data, null, row )
											}
										})
									}
							} );
							
						$( "#dt_contract_service_length select, #dt_contract_service_filter input" )
						.addClass( "form-control" );
						
						_this.find( 'tbody' ).on( 'click', 'tr td a.js-component-expand', function(e){
							var _tr = $( this ).closest( 'tr' );
							var _rw = _datatable.row( _tr );
							
							var _dt = _rw.data();
					 
							if( _rw.child.isShown() ){
								
								$(this).find('i').addClass( 'fa-expand' );
								$(this).find('i').removeClass( 'fa-compress' );
																	
								_rw.child.hide();
					 
							} else {
								
								$(this).find('i').removeClass( 'fa-expand' );
								$(this).find('i').addClass( 'fa-compress' );									
								
								if( _rw.child() == undefined ){
									_rw.child(_datatable_actions.show_service_component( _dt.components, _tr )).show();
								} else {
									_rw.child.show();
								}
							}
							
						});
												
						return _this
					},
			});

		$( document ).ready(function(e) {
				
            	$( "#dt_contract_service" ).dataTableContractService();
				

			});

	})( jQuery );
//]]>
</script>
