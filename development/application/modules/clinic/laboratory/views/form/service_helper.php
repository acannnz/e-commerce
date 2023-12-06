<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<input type="hidden" id="service_component" data-component="{}" />
<input type="hidden" id="service_consumable" data-consumable="{}" />
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_services" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID Jasa</th>
                        <th>Deskripsi</th>                        
                        <th>Qty</th>                        
                        <th>Tarif</th>                        
                        <th>ID Dokter</th>                        
                        <th>Nama Dokter</th>                        
                        <th>UserID</th>                        
                        <th>Jam</th>                        
                        <!--<th>Disc TL</th>                        -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$lookup_service ?>" id="add_charge" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Jasa</b></a>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var service_component_temp = $("#service_component").data("component");
		var service_consumable_temp = $("#service_consumable").data("consumable");
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){						
						switch(this.index()){
							case 3:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Qty || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											
											data.Qty = this.value < 1 ? 1 : this.value;
	
											_datatable.row( row ).data( data ).draw(false);
										} catch(ex){console.log(ex)}
									});
							default:
								
							break;	
						}								
					},
				get_component_service: function( params, index ){
						// Mengambil data Component service dan BHP, ketika jasa dipilih
						var service = params;
						if ( $.isEmptyObject(service.component_temp) )
						{
							$('#js-btn-submit').prop('disabled', 'disabled');
							var data_post = {
								NoBukti : '<?php echo @$item->NoBukti?>',
								ListHargaID : params.ListHargaID,
								JasaID : params.JasaID,
								Nomor : params.Nomor || 0,
								JenisKerjasamaID : $("#JenisKerjasamaID").val(),
								KTP : $("#PasienKTP").val(),
								CustomerKerjasamaID : $("#KodePerusahaan").val() || 0,
								SectionID : "<?php echo config_item('section_id'); ?>"
							}

							$.post("<?php echo $get_service_component ?>", data_post, function( response, status, xhr ){
								
								$('#js-btn-submit').removeAttr('disabled');
								if( "error" == response.status ){
									$.alert_error(response.message);
									return false
								}
		
								if( response.collection ){
									service.component_temp = response.collection;
									_datatable.row( index ).data(service);
								}
								
							}).always(function(){
								$('#js-btn-submit').removeAttr('disabled');
							});
						}
						
						if ( $.isEmptyObject(service.consumable_temp) )
						{
							//= JenisKerjasamaID, KelasID, KTP, Barang_ID, CustomerKerjasamaID, SectionID, JenisBarangID
							$('#js-btn-submit').prop('disabled', 'disabled');
							var data_post = {
								NoBukti : '<?php echo @$item->NoBukti?>',
								JasaID : params.JasaID,
								Nomor : params.Nomor || 0,
								JenisKerjasamaID : $("#JenisKerjasamaID").val(),
								KTP : $("#PasienKTP").val(),
								CustomerKerjasamaID : $("#KodePerusahaan").val() || 0,
								SectionID : "<?php echo config_item('section_id'); ?>"
							}
							// Request data detail jasa BHP, karena bnyak parameter yg harus dikirim
							$.post("<?php echo $get_service_consumable ?>", data_post, function( response, status, xhr ){
								
								if( "error" == response.status ){
									$.alert_error(response.message);
									return false
								}
		
								if( response.collection ){
									service.consumable_temp = response.collection;
									_datatable.row( index ).data(service);
								}
								
							}).always(function(){
								$('#js-btn-submit').removeAttr('disabled');
							});
						}
					},
				remove: function( index ){
						_datatable.row( index ).remove().draw();
						_datatable.draw();					
					}
			};
		
		$.fn.extend({
				dt_services: function(){
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
											data: "JasaID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													var buttons = '<div class="btn-group">';
													buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn btn-danger btn-xs btn-remove"><i class="fa fa-trash"></i></a>';
													buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:edit" ) ?>" class="btn btn-info btn-xs btn-edit"><i class="fa fa-pencil"></i></a>';
													buttons += '</div>';
												return buttons
												} 
										},
										{ 
											data: "JasaID", 
											className: "", 
										},
										{ data: "JasaName", className: "" },
										{ data: "Qty", className: "" },
										{ 
											data: "Tarif", 
											className: "text-right",
											render: function(val){
												return mask_number.currency_add(val);
											}
										},
										{ data: "DokterID", className: "" },
										{ data: "Nama_Supplier", className: "" },
										{ data: "User_id", className: "text-center", },
										{ data: "Jam", className: "text-center", },
										//{ data: "Discount", className: "text-center", },
									
										
									],
								createdRow: function ( row, data, index ){
										_datatable_actions.get_component_service( data, index );
										
										$( row ).on( "click", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											
											if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
												_datatable_actions.remove( index )
											}
										});
										
										$( row ).on( "click", "a.btn-edit", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											
											form_ajax_modal.show("<?php echo $view_service ?>/"+ index +"/"+ data.JasaID);
										});
									}
							} );
							
						$( "#dt_services_length select, #dt_services_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_services" ).dt_services();

			});

	})( jQuery );
//]]>
</script>