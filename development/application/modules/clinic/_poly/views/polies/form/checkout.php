<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">	
	<div class="col-md-6">
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="TindakLanjut_Pulang" name="f[checkout]" value="1" <?php echo @$item->TindakLanjut_Pulang == 1 ? "Checked" : NULL ?> class="" ><label for="TindakLanjut_Pulang">Pulang</label>
                </div>
            </div>
            <div class="col-md-9">
				<div class="input-group">
					<span class="input-group-addon">
						<input type="checkbox" id="TindakLanjutCekUpUlang" name="f[TindakLanjutCekUpUlang]" value="1" <?php echo @$item->TindakLanjutCekUpUlang == 1 ? "Checked" : NULL ?> aria-label="Check Up">
					</span>
					<input type="text" id="TglCekUp" name="f[checkout]" value="<?php echo @$item->TindakLanjutCekUpUlang ? @$item->TglCekUp : NULL ?>" class="form-control datepicker" placeholder="Tanggal CheckUp Kembali">  
					<span class="input-group-addon">Check Up</span>
				</div>
            </div>
		</div>
		<div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="TindakLanjut_RI" name="f[checkout]" value="1" <?php echo @$item->TindakLanjut_RI == 1 ? "Checked" : NULL ?> class="" ><label for="TindakLanjut_RI">Rawat Inap</label>
                </div>
            </div>
			<div class="col-md-9">
				<div class="input-group">
					<input type="hidden" id="Konsul_DOkterID" name="f[Konsul_DOkterID]" value="<?php echo @$poly->Konsul_DOkterID ?>" class="clear_doctor_consul">
					<input type="text" id="Konsul_DOkterIDName" value="<?php echo @$poly->NamaDokterKonsul ?>" class="form-control clear_doctor_consul" placeholder="Dokter Konsul Rawat Inap" readonly>
					<span class="input-group-btn">
						<a href="<?php echo @$lookup_supplier_consul ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
						<a href="javascript:;" id="clear_doctor_consul" class="btn btn-default .btn-clear" ><i class="fa fa-times"></i></a>
					</span>
				</div>
			</div>
		</div>
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="Meninggal" name="f[checkout]" value="1" <?php echo @$item->Meninggal == 1 ? "Checked" : NULL ?> class=""><label for="Meninggal">Meninggal</label>
                </div>
            </div>
            <div class="col-md-9">
            	<input type="text" id="Meninggal_Jam" name="f[Meninggal_Jam]" value="<?php echo  @$item->Meninggal ? @$item->Meninggal_Jam : NULL ?>" class="form-control timepicker" placeholder="Meninggal Pukul">                
			</div>
        </div>
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="TindakLanjutReferal" name="f[checkout]" value="1" <?php echo @$item->TindakLanjutReferal == 1 ? "Checked" : NULL ?> class=""><label for="TindakLanjutReferal">Referal</label>
                </div>
            </div>
            <div class="col-md-9">
				<input type="text" id="KeteranganReferal" name="f[KeteranganReferal]" value="<?php echo @$item->KeteranganReferal ?>" class="form-control" placeholder="Dirujuk Ke">                
            </div>
        </div>        
    </div>
	<div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
                <div class="radio">
                    <input type="radio" id="TindakLanjut_KonsulMedik" name="f[checkout]" value="1" <?php echo @$item->TindakLanjut_KonsulMedik == 1 ? "Checked" : NULL ?> class=""><label for="TindakLanjut_KonsulMedik">Konsul Medik</label>
                </div>
                <div class="table-responsive">
                    <table id="dt_checkout" class="table table-sm table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Section</th>
                                <th>Dokter</th>                        
                                <th>Waktu</th>                        
                                <th>No</th>                        
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row form-group">
                    <a href="javascript:;" id="add_konsul" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Konsul Medik</b></a>
                </div>
            </div>
        </div>        
    </div>
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
							
							case 1:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.Konsul_SectionID ? data.Konsul_SectionID : '';
								_input.load( "<?php echo $section_dropdown ?>/" + _value , function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
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
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.Konsul_SectionID = _selected.sectionid || 0;
											data.SectionName = _selected.sectionname;
											data.Konsul_DOkterID = 0;
											data.Nama_Supplier = '';
											data.WaktuID = 0;
											data.Keterangan = '';
											data.NoUrut = '';
											
											console.log(_selected);
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;

							case 2:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.Konsul_DOkterID ? data.Konsul_DOkterID : 0;
								_input.load( "<?php echo $doctor_dropdown ?>/" + _value +"/"+ data.Konsul_SectionID, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
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
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.Konsul_DOkterID = _selected.dokterid || 0;
											data.Nama_Supplier = _selected.nama_supplier;
											
											console.log(_selected);
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;							

							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.WaktuID ? data.WaktuID : 0;
								_input.load( "<?php echo $time_dropdown ?>/" + _value  +"/"+ data.Konsul_SectionID +"/"+ data.Konsul_DOkterID, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data );
											_datatable_actions.get_queue( data, row );
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.WaktuID = _selected.waktuid;
											data.Keterangan = _selected.keterangan;
											
											console.log(_selected);
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				get_queue: function( params, scope ){

						$.post( "<?php echo $get_queue ?>", { "SectionID" : params.Konsul_SectionID, "DokterID" : params.Konsul_DOkterID, "WaktuID" : params.WaktuID  }, function( response, status, xhr ){
						
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								params.NoUrut = '';		
							} else {
								params.NoUrut = response.NoUrut;									
							}
							
							_datatable.row( scope )
									.data( params )
									.draw(false);		
						});							

								
						
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
								"Konsul_SectionID" : '',
								"SectionName" : '',
								"Konsul_DOkterID" : '',
								"Nama_Supplier" : '',
								"WaktuID" : '',
								"Keterangan" : '',
								"NoUrut" : '',
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_checkout: function(){
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
											data: "Konsul_SectionID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "SectionName", 
											className: "", 
										},
										{ data: "Nama_Supplier", className: "" },
										{ data: "Keterangan", className: "" },
										{ data: "NoUrut", className: "" },
									],
								columnDefs  : [
										{
											"targets": ["Konsul_SectionID", "Konsul_DOkterID", "WaktuID"],
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
							
						$( "#dt_checkout_length select, #dt_checkout_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_checkout" ).dt_checkout();
				
				$("#add_konsul").on("click", function(e){
					_datatable_actions.add_row();
				});
								
		});

	})( jQuery );
//]]>
</script>