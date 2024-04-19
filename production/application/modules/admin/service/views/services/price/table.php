<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row mb10">
	<div class="col-md-12">
		<a href="javascript:;" data-action-url="<?php echo @$add_service_price ?>" data-act="ajax-modal"  data-title="<?php echo lang('subtitle:price_detail')?>"  data-act="ajax-modal" data-modal-lg="1" class="btn btn-primary btn-sm pull-right"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table id="dt_form_price" class="table table-bordered table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:class') ?></th>
					<th><?php echo lang('label:patient_type') ?></th>
					<th><?php echo lang('label:specialist') ?></th>
					<th><?php echo lang('label:doctor') ?></th>
					<th><?php echo lang('label:location') ?></th>
					<th><?php echo lang('label:operation_category') ?></th>
				</tr>
			</thead>        
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<hr/>

<div class="row mb10">
	<div class="col-md-6">
		<h4 class="subtitle"><?php echo lang('subtitle:service_component_detail');?></h4>
	</div>
	<div class="col-md-6">
		<a href="javascript:;" id="js_add_service_component" data-action-url="<?php echo @$add_service_component ?>" data-act="ajax-modal"  data-title="<?php echo lang('subtitle:service_component_detail')?>"  data-act="ajax-modal" data-modal-lg="1" class="btn btn-primary btn-sm pull-right disabled"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table id="dt_form_service_component" class="table table-bordered table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:description') ?></th>
					<th><?php echo lang('label:insentif') ?></th>
					<th><?php echo lang('label:qty') ?></th>
					<th><?php echo lang('label:new_price') ?></th>
					<th><?php echo lang('label:new_price_hc') ?></th>
					<th><?php echo lang('label:new_price_bpjs') ?></th>					
				</tr>
			</thead>        
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" style='text-align:right !important;'><?php echo lang('label:total') ?></th>
					<th id="js_total_price" align="right"></th>
					<th id="js_total_hc_price" align="right"></th>
					<th id="js_total_bpjs_price" align="right"></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){

		var _datatable, _datatable_component;		
		var _datatable_actions = {
				edit: function( row, data, index ){
												
						switch( this.index() ){									
							case 9:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Qty_Permintaan || 1) + "\" style=\"width:100%\"  class=\"form-control\" min=\"1\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Qty_Permintaan = this.value > 0 ? this.value : 1;
											data.Jumlah_Total = Number( data.Qty_Permintaan ) * mask_number.currency_remove( data.Harga_Beli );
											_datatable.row( row ).data( data );
									
											
										} catch(ex){}
									});
							break;
							
							case 10:
							
								var _input = $( "<input type=\"number\" value=\"" + mask_number.currency_remove(data.Harga_Beli || 1) + "\" style=\"width:100%\"  class=\"form-control\" min=\"1\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										   
										try{
											data.Harga_Beli = this.value > 0 ? this.value : data.Harga_Beli;
											data.Jumlah_Total = Number( data.Qty_Permintaan ) * mask_number.currency_remove( data.Harga_Beli );
											_datatable.row( row ).data( data );
									
										} catch(ex){}
									});
							break;
						}
						
					},
				show_component: function(){
						var service_price_data = $("#dt_form_price").DataTable().row( '.selected' ).data();
						$('#dt_form_service_component').DataTable().clear().draw();
						$('#dt_form_service_component').DataTable().rows.add( service_price_data.component_detail ).draw();
						$('#js_add_service_component').removeClass('disabled');
						
						$('#js_total_price').html( mask_number.currency_add(service_price_data.Harga_Baru || 0) );
						$('#js_total_hc_price').html( mask_number.currency_add(service_price_data.HargaHC_Baru || 0) );
						$('#js_total_bpjs_price').html( mask_number.currency_add(service_price_data.HargaBPJS || 0) );
					},
				hide_component: function(){
						$('#dt_form_service_component').DataTable().clear().draw();
						$('#js_add_service_component').addClass('disabled');
						
						$('#js_total_price').html('');
						$('#js_total_hc_price').html('');
						$('#js_total_bpjs_price').html('');
					},
				calculate_total_price: function(){
						var service_price_data = $("#dt_form_price").DataTable().row( '.selected' ).data();
						var Harga_Lama = Harga_Baru = HargaHC_Lama = HargaHC_Baru = HargaBPJS = HargaBPJS_Lama = 0;
						
						if (typeof service_price_data !== 'undefined') { 
							
							$.each(service_price_data.component_detail, function(index, value){
								Harga_Lama = Harga_Lama + parseFloat(value.HargaLama);
								Harga_Baru = Harga_Baru + parseFloat(value.HargaBaru);
								HargaHC_Lama = HargaHC_Lama + parseFloat(value.HargaHCLama);
								HargaHC_Baru = HargaHC_Baru + parseFloat(value.HargaHCBaru);	
								HargaBPJS = HargaBPJS + parseFloat(value.HargaBPJS);
								HargaBPJS_Lama = HargaBPJS_Lama + parseFloat(value.HargaBPJS_Lama);
							});
							
							service_price_data.Harga_Lama = Harga_Lama;
							service_price_data.Harga_Baru = Harga_Baru;
							service_price_data.HargaHC_Lama = HargaHC_Lama;
							service_price_data.HargaHC_Baru = HargaHC_Baru;
							service_price_data.HargaBPJS = HargaBPJS;
							service_price_data.HargaBPJS_Lama = HargaBPJS_Lama;
							
							$('#dt_form_price').DataTable().row( '.selected' ).data(service_price_data);
							$('#js_total_price').html( mask_number.currency_add(Harga_Baru) );
							$('#js_total_hc_price').html( mask_number.currency_add(HargaHC_Baru) );
							$('#js_total_bpjs_price').html( mask_number.currency_add(HargaBPJS) );
						}
					},
				remove_price: function( params, fn, scope ){
						_datatable.row( scope ).remove().draw();						
						$('#js_add_service_component').addClass('disabled');
					},
				remove_component: function( params, scope, index ){
						var service_price_data = $("#dt_form_price").DataTable().row( '.selected' ).data();
						service_price_data.component_detail.splice(index, 1);
						$("#dt_form_price").DataTable().row( '.selected' ).data(service_price_data).draw();
						_datatable_actions.show_component();
					}
			};
		
		$.fn.extend({
				dataTableFormPrice: function(){
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
								data: <?php print_r(json_encode(@$collection_price, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "ListHargaID", 
											className: "actions text-center", 
											width: '10%',
											render: function( val, type, row, meta ){
													var buttons = '<div class="btn-group">';
														buttons += '<a href="javascript:;" title="Ubah" data-action-url="<?php echo @$update_service_price ?>/'+ meta.row +'" data-title="<?php echo lang('subtitle:price_detail'); ?>" data-act="ajax-modal" data-modal-lg="1" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>';
														buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn btn-danger btn-xs btn-remove"><i class="fa fa-trash"></i></a>';
														buttons += '</div>';
													return buttons;
												} 
										},
										{ data: "NamaKelas"},
										{ data: "JenisKerjasama"},
										{ data: "SpesialisName"},
										{ 
											data: "DokterID", 
											render: function(val, type, row){
												return row.DokterID +' '+ row.NamaDokter;
											} 
										},
										{ data: "Lokasi" },
										{ data: "KategoriOperasiName"}
									],
								drawCallback: function( settings ){
									_datatable_actions.calculate_total_price();
								},
								createdRow: function ( row, data, index ){												
										
										$( row ).on( "dblclick", function(e){
												e.preventDefault();			
																					
												
												
											});

										$( row ).on( "click", function(e){
											e.preventDefault();			
																				
											if ( $(this).hasClass('selected info') ) {
													$(this).removeClass('selected info');
													_datatable_actions.hide_component();
												} else {
													_this.DataTable().$('tr.selected').removeClass('selected info');
													$(this).addClass('selected info');
													_datatable_actions.show_component();
												}
												
											
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
							
						$( "#dt_form_price_length select, #dt_form_price_filter input" )
						.addClass( "form-control" );
												
						return _this
					},
				dataTableServiceComponent: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable_component = _this.DataTable( {
								dom: 'tip',
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								scrollCollapse: true,
								columns: [
										{ 
											data: "KomponenBiayaID", 
											width: '10%',
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													var buttons = '<div class="btn-group">';
														buttons += '<a href="javascript:;" title="Ubah" data-action-url="<?php echo @$update_service_component ?>/'+ meta.row +'" data-title="<?php echo lang('subtitle:service_component'); ?>" data-act="ajax-modal" data-modal-lg="1" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>';
														buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn btn-danger btn-xs btn-remove"><i class="fa fa-trash"></i></a>';
														buttons += '</div>';
													return buttons;
												} 
										},
										{ 
											data: "KomponenBiayaID",
											render: function( val, type, row ){
												return row.KomponenBiayaID +' - '+ row.KomponenName;
											}
										},
										{ 
											data: "PersenInsentif",
											className: "text-right",
											render: function( val ){
												return val +'%';
											}
										},
										{ 
											data: "Qty",
											className: 'text-right'
										},
										{ 
											data: "HargaBaru",
											className: 'text-right',
											render: function( val ){
												return mask_number.currency_add( val || 0 );
											}
										},
										{ 
											data: "HargaHCBaru",
											className: 'text-right',
											render: function( val ){
												return mask_number.currency_add( val || 0 );
											}
										},
										{ 
											data: "HargaBPJS",
											className: 'text-right',
											render: function( val ){
												return mask_number.currency_add( val || 0 );
											}
										},
									],
								createdRow: function ( row, data, index ){												
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove_component( data, row, index )
												}
											})
									}
							} );
							
						$( "#dt_form_price_length select, #dt_form_price_filter input" )
						.addClass( "form-control" );
												
						return _this
					},
			});

		$( document ).ready(function(e) {
				
            	$( "#dt_form_price" ).dataTableFormPrice();
				$( "#dt_form_service_component" ).dataTableServiceComponent();

			});

	})( jQuery );
//]]>
</script>
