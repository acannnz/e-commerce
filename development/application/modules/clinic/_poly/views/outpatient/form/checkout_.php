<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>			

<div class="row">	
	<div class="col-md-6">
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxKeluar_Pulang" name="f[checkout]" value="1" <?php echo @$item->PxKeluar_Pulang == 1 || @$item->TindakLanjut_Pulang == 1 ? "Checked" : NULL ?> class="" ><label for="PxKeluar_Pulang">Pulang</label>
                </div>
            </div>
			<div id="PxKeluar_PulangOption" > <!--style="display:none"-->
				<div class="col-md-9">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" id="TindakLanjutCekUpUlang" name="f[TindakLanjutCekUpUlang]" value="1" <?php echo @$item->TindakLanjutCekUpUlang == 1 ? "Checked" : NULL ?> aria-label="Check Up">
						</span>
						<input type="text" id="TglCekUp" name="f[checkout]" value="<?php echo @$item->TindakLanjutCekUpUlang ? @$item->TglCekUp : NULL ?>" class="form-control datepicker" placeholder="Tanggal CheckUp Kembali" autocomplete="off">  
						<span class="input-group-addon">Check Up </span>
					</div>
				</div>
            </div>
		</div>
		<!-- <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="TindakLanjut_RI" name="f[checkout]" value="1" <?php echo @$item->TindakLanjut_RI == 1 ? "Checked" : NULL ?> class="" ><label for="TindakLanjut_RI">Rawat Inap</label>
                </div>
            </div>
			<div id="TindakLanjut_RIOption" style="display:none">
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
		</div> -->

		<div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxKeluar_Dirujuk" name="f[checkout]" value="1" <?php echo @$item->PxKeluar_Dirujuk == 1 ? "Checked" : NULL ?> class=""><label for="PxKeluar_Dirujuk">Dirujuk</label>
                </div>
            </div>
			<div id="PxKeluar_DirujukOption" style="display:none">
				<div class="col-md-9">
					<input type="text" id="PxKeluar_DirujukKeterangan" name="f[PxKeluar_DirujukKeterangan]" value="<?php echo  @$item->PxKeluar_DirujukKeterangan ?>" class="form-control" placeholder="Referensi Rujukan">
				</div>
			</div>
			<?php if(config_item('bpjs_bridging') == 'TRUE' )
					echo modules::run("bpjs/visite/checkout", @$item->NoReg); ?>
        </div>        
        <div class="form-group">
            <div class="col-md-3">
                <div class="radio">
                    <input type="radio" id="PxMeninggal" name="f[checkout]" value="1" <?php echo @$item->PxMeninggal == 1 ? "Checked" : NULL ?> class=""><label for="PxMeninggal">Meninggal</label>
                </div>
            </div>
			<div id="PxMeninggalOption" style="display:none">
				<div class="col-md-2">
					<div class="radio">
						<input type="radio" id="MeninggalSblm48" name="PxMeninggalOption" value="1" <?php echo @$item->MeninggalSblm48 == 1 ? "Checked" : NULL ?> class=""><label for="MeninggalSblm48"> < 48 Jam</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="radio">
						<input type="radio" id="MeninggalStl48" name="PxMeninggalOption" value="1" <?php echo @$item->MeninggalStl48 == 1 ? "Checked" : NULL ?> class=""><label for="MeninggalStl48"> > 48 Jam</label>
					</div>
				</div>
				<div class="col-md-2">
					<input type="text" id="MeninggalTgl" name="f[MeninggalTgl]" value="<?php echo  @$item->Meninggal ? @$item->MeninggalTgl : NULL ?>" class="form-control datepicker" placeholder="Tanggal">                
				</div>
				<div class="col-md-2">
					<input type="text" id="Meninggal_Jam" name="f[Meninggal_Jam]" value="<?php echo  @$item->Meninggal ? @$item->Meninggal_Jam : NULL ?>" class="form-control timepicker" placeholder="Pukul">                
				</div>
			</div>
        </div>
    </div>
	<div class="col-md-6">
        <div class="form-group">
			<div class="radio">
				<input type="radio" id="TindakLanjut_KonsulMedik" name="f[checkout]" value="1" <?php echo @$item->TindakLanjut_KonsulMedik == 1 ? "Checked" : NULL ?> class=""><label for="TindakLanjut_KonsulMedik">Konsul Medik</label>
			</div>
			<div class="col-md-12">
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
			<div class="col-md-12">
				<a href="javascript:;" id="add_konsul" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Konsul Medik</b></a>
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
								
								var _section_asal = "<?php echo $item->SectionID ?>";
								var _value = data.Konsul_SectionID ? data.Konsul_SectionID : 0;
								_input.load( "<?php echo $section_dropdown ?>/" + _value + "/" + _section_asal, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.Konsul_SectionID = _selected.sectionid || 0;
											data.SectionName = _selected.sectionname;
											data.Konsul_DOkterID = 'XX';
											data.Nama_Supplier = 'XX';
											data.WaktuID = 0;
											data.Keterangan = '';
											data.NoUrut = '';
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;

							// case 2:
							// 	var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
							// 	this.empty().append( _input );
								
							// 	var _value = data.Konsul_DOkterID ? data.Konsul_DOkterID : 0;
							// 	_input.load( "<?php echo $doctor_dropdown ?>/" + _value +"/"+ data.Konsul_SectionID, function( response, status, xhr ){
							// 			_input.trigger( "focus" )
							// 		} );
								
							// 	_input.on( "change", function( e ){
							// 			e.preventDefault();
																				
							// 			try{
							// 				var _selected = $( e.target ).find( "option:selected" ).data() || {};
							// 				data.Konsul_DOkterID = _selected.dokterid || 0;
							// 				data.Nama_Supplier = _selected.nama_supplier;
											
							// 				console.log(_selected);
							// 				_datatable.row( row ).data( data );
							// 			} catch(ex){console.log(ex);}
							// 		});
							// break;							

							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.WaktuID ? data.WaktuID : 0;
								_input.load( "<?php echo $time_dropdown ?>/" + _value  +"/"+ data.Konsul_SectionID +"/"+ data.Konsul_DOkterID, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.WaktuID = _selected.waktuid;
											data.Keterangan = _selected.keterangan;
											
											_datatable_actions.get_queue( data, row );
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
							break;						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						// _datatable_actions.calculate_balance();
						
					},
				get_queue: function( params, scope ){

						$.post( "<?php echo $get_queue ?>", { "SectionID" : params.Konsul_SectionID, "DokterID" : params.Konsul_DOkterID, "WaktuID" : params.WaktuID  }, function( response, status, xhr ){
						
							// var response = $.parseJSON(response);
							if( "error" == response.status ){
								$.alert_error(response.message);
								params.NoUrut = '';		
							} else {
								params.NoUrut = response.queue;									
							}
							_datatable.row( scope ).data( params ).draw();		
						});													
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
								Konsul_SectionID : '',
								SectionName : '',
								Konsul_DOkterID : 'XX',
								Nama_Supplier : 'XX',
								WaktuID : '',
								Keterangan : '',
								NoUrut : '',
							}
						).draw();
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
			
			$('.btn-clear').on('click', function(){
				var _class = '.'+ $(this).attr('id');
				$(_class).val('');
			});
		
			$('input[name="f[checkout]"]').on('change', function(){
				
				if($(this).attr('id') == 'PxKeluar_Pulang'){
					$('#PxKeluar_PulangOption').show();
					$('#TglCekUp').val('');					
				} else {
					$('#PxKeluar_PulangOption').hide();
					$('#TindakLanjutCekUpUlang').prop("checked", false);
					$('#TglCekUp').val('');
				}
				
				if($(this).attr('id') == 'TindakLanjut_RI'){
					$('#TindakLanjut_RIOption').show();
					$('#Konsul_DOkterID').val('');
					$('#Konsul_DOkterIDName').val('');
					
				} else {
					$('#TindakLanjut_RIOption').hide();
					$('#Konsul_DOkterID').val('');
					$('#Konsul_DOkterIDName').val('');
				}
				
				if($(this).attr('id') == 'PxKeluar_Dirujuk'){
					$('#PxKeluar_DirujukOption').show();
					$('#PxKeluar_DirujukKeterangan').val('');
					
				} else {
					$('#PxKeluar_DirujukOption').hide();
					$('#PxKeluar_DirujukKeterangan').val('');
				}
				
				if($(this).attr('id') == 'PxMeninggal'){
					$('#PxMeninggalOption').show();
					$('#MeninggalSblm48').prop("checked", true);
					$('#MeninggalTgl').val('');
					$('#Meninggal_Jam').val('');
					
				} else {
					$('#PxMeninggalOption').hide();
					$('input[name="PxMeninggalOption"]').prop("checked", false);
					$('#MeninggalTgl').val('');
					$('#Meninggal_Jam').val('');
				}
			});
						
			if($('#PxMeninggal').is(':checked')){
				$('#PxMeninggalOption').show();
			}
			
			$('#PxKeluar_Dirujuk').on('click', function(){
				if(typeof bpjsBridging !== 'undefined' && $('#JenisKerjasamaID').val() == 9 ){
					ajax_modal.show('<?php echo base_url('bpjs/visite/lookup_referral') ?>');
				}	
			});
								
		});

	})( jQuery );
//]]>
</script>