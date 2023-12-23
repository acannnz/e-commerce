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
						
						if ( this.index() == 0 ) {
								
							try{
								if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
										_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
								}
							} catch(ex){}
								
						}
						
						if ( this.index() > 0 ) {
							try{
								indexRow = _datatable.row( row ).index();
								
								form_ajax_modal.show("<?php echo $view_service ?>/"+ indexRow +"/"+ data.JasaID);
							} catch(ex){}
						}
						
					},
				get_component_service: function( params ){
						// Mengambil data Component service dan BHP, ketika jasa dipilih
						if ( $.isEmptyObject(service_component_temp[params.JasaID]) )
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
								
								var response = $.parseJSON(response);
								
								$('#js-btn-submit').removeAttr('disabled');
								if( "error" == response.status ){
									$.alert_error(response.message);
									return false
								}
		
								if( response.collection ){
									service_component_temp[params.JasaID] = response.collection;
									$("#service_component").data("component", service_component_temp);
								}
								
							}).always(function(){
								$('#js-btn-submit').removeAttr('disabled');
							});
						}
						
						if ( $.isEmptyObject(service_consumable_temp[params.JasaID]) )
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
								
								var response = $.parseJSON(response);
								if( "error" == response.status ){
									$.alert_error(response.message);
									return false
								}
		
								if( response.collection ){
									service_consumable_temp[params.JasaID] = response.collection;
									$("#service_consumable").data("consumable", service_consumable_temp);
								}
								
							}).always(function(){
								$('#js-btn-submit').removeAttr('disabled');
							});
						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope ).remove().draw();
						delete service_component_temp[params.JasaID];
						delete service_consumable_temp[params.JasaID];
						
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add({}).draw();					
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
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "JasaID", 
											className: "", 
										},
										{ data: "JasaName", className: "" },
										{ data: "Qty", className: "" },
										{ data: "Tarif", className: "" },
										{ data: "DokterID", className: "" },
										{ data: "Nama_Supplier", className: "" },
										{ data: "User_id", className: "text-center", },
										{ data: "Jam", className: "text-center", },
										//{ data: "Discount", className: "text-center", },
									
										
									],
								columnDefs  : [
										{
											"targets": ["NoKartu","JenisKerjasamaID", "ListHargaID", "Nomor"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										_datatable_actions.get_component_service( data );
										
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