<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_service_consumable" class="table table-sm  table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Deskripsi</th>                        
                        <th>Sat</th>                        
                        <th>Qty</th>                        
                        <th>Harga @</th>                        
                        <th>Disc</th>                        
                        <th>Jumlah</th>                        
                        <!--<th>Exclude</th>-->                        
                        <th>Stok</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$lookup_service_consumable ?>" id="service_consumable" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah BHP</b></a>
</div>


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var services_index = "<?php echo $indexRow ?>";
		var _datatable;
		var service_selected = $("#dt_services").DataTable().row(services_index).data();
		var JasaID = service_selected.JasaID;
		// get data temporary service component
		var service_consumable_temp = $("#service_consumable").data("consumable");
		var JenisKerjasamaID = $("#JenisKerjasamaID").val();		
				
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						switch( this.index() ){

							case 4:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Qty || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{

											data.Qty = Number(this.value || 0);
											data.Jumlah = data.Qty * parseFloat( data.Harga ).toFixed(2);
											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.store_data();

										} catch(ex){}
										// } catch(ex){console.log(ex)}
									});
							break;						
						}

					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(true);
														
					},
				calculate_all: function(params, fn, scope){
						
						var _table = _datatable.rows().data();
						_table.each(function(value, index){
							
							value.Jumlah = Number(value.Qty) * parseFloat(value.Harga).toFixed(2);
							_datatable.rows( index ).data( value );
						});	
											
					},
				store_data: function(){
						
					var data_consumable = _datatable.data().toArray();
					
					service_consumable_temp[JasaID] = data_consumable;
					// console.log(service_consumable_temp[JasaID]);
						
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}				
			};
		
		$.fn.extend({
				dt_service_consumable: function(){
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
								data: [],
								columns: [
										{ 
											data: "Barang_ID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "Barang_ID", 
											className: "actions text-center", 
										},
										{ 
											data: "Nama_Barang", 
											className: "actions", 
										},
										{ 
											data: "Satuan", 
											className: "actions text-center", 
										},
										{ 
											data: "Qty", 
											className: "actions text-right", 
										},
										{ 
											data: "Harga", 
											className: "actions text-right", 
										},
										{ 
											data: "Disc", 
											className: "actions text-right", 
										},
										{ 
											data: "Jumlah", 
											className: "actions text-right", 
										},
										/*{ 
											data: "Exclude", 
											className: "actions text-center", 
										},*/
										{ 
											data: "Stok", className: "text-right", 
											
										},
									],
								columnDefs  : [
										{
											"targets": ["JasaID","Barang_ID","HPP","HargaOrig"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										_datatable_actions.calculate_all( );
										_datatable_actions.store_data();

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
							
						$( "#dt_service_consumable_length select, #dt_service_consumable_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		
		$( document ).ready(function(e) {

				$("#dt_service_consumable").dt_service_consumable();
				
				// cek apakah object servcie compnent dengan jasa ini sudah ada atau belum
				if ( $.isEmptyObject(service_consumable_temp[JasaID]) )
				{
					//= JenisKerjasamaID, KelasID, KTP, Barang_ID, CustomerKerjasamaID, SectionID, JenisBarangID
					var data_post = {
						NoBukti : service_selected.NoBukti || null,
						JasaID : JasaID,
						JenisKerjasamaID : $("#JenisKerjasamaID").val(),
						KTP : $("#PasienKTP").val(),
						CustomerKerjasamaID : $("#KodePerusahaan").val() || 0,
					}

					// Request data detail jasa BHP, karena bnyak parameter yg harus dikirim
					$.post("<?php echo $get_service_consumable ?>", data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}

						if( response.collection ){
							service_consumable_temp[JasaID] = response.collection;
							$("#dt_service_consumable").DataTable().clear().draw();
							$("#dt_service_consumable").DataTable().rows.add( service_consumable_temp[JasaID] ).draw();				
						}
						
					});
				} else {

					$("#dt_service_consumable").DataTable().clear().draw();
					$("#dt_service_consumable").DataTable().rows.add( service_consumable_temp[JasaID] ).draw();				
				
				}

				// console.log("BHP: ", service_consumable_temp);

			});

	})( jQuery );
//]]>
</script>