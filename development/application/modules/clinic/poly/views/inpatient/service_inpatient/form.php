<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), ['id' => 'form_service']); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Form Jasa</h4>
        </div>
        <div class="modal-body">

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<label class="control-label"><?php echo lang('poly:evidence_number_label') ?> <span class="text-danger">*</span></label>
								<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
							</div>
							<div class="col-md-4">
								<label class="control-label"><?php echo lang('poly:date_label') .'/'. lang('poly:time_label') ?> <span class="text-danger">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" id="Tanggal" name="f[Tanggal]" value="<?php echo @$item->Tanggal ?>" placeholder="" class="form-control">
									<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
									<input type="text" id="Jam" name="f[Jam]" value="<?php echo @$item->Jam ?>" placeholder="" class="form-control timepicker">
								</div>
							</div>
							<div class="col-md-4">
								<label class="control-label"><?php echo lang('poly:doctor_label') ?> <span class="text-danger">*</span></label>
								<div class="input-group">
									<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ?>" class="clear_doctor">
									<input type="text" id="NamaDokter" value="<?php echo @$item->NamaDokter ?>" placeholder="" class="form-control clear_doctor">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_doctor ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<table id="dt_services" class="table table-bordered" width="100%">
							<thead>
								<tr>
									<th></th>
									<th><?php echo 'Jasa' ?></th>
									<th><?php echo lang('poly:doctor_label') ?></th>
									<th><?php echo 'Qty' ?></th>                        
									<th><?php echo 'Tarif' ?></th>                        
								</tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td style="text-align:left !important" colspan="3">
										<div class="checkbox" style="margin:0 !important">
											<input type="checkbox" id="ClosedTransaksi" value="1" <?php echo @$patient->PasienVVIP == 1 ? "Checked" : NULL ?>>
											<label for="ClosedTransaksi">Closed Transaksi</label>
										</div>
									</td>
									<td><b id="dt_services_total"></b></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="form-group">
						<a href="<?php echo @$lookup_service ?>" id="add_charge" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Jasa</b></a>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<a href="<?php echo @$delete_link ?>" id="btn-delete-inpatient-service" data-toggle="ajax-modal" class="btn btn-danger btn-block <?php echo !empty($collection) ? '' : 'disabled'?>"><?php echo lang('buttons:delete')?></a> 
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-primary btn-block"><?php echo lang('buttons:save')?></button> 
							</div>
							<div class="col-md-4">
								<button type="button" id="btn-close-form" class="btn btn-default btn-block" data-dismiss="modal"><?php echo lang('buttons:close')?></button> 
							</div>
						</div>
					</div>
				</div>
        	</div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<?php echo form_close(); ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var service_component_temp = $("#service_component").data("component");
		var service_consumable_temp = $("#service_consumable").data("consumable");
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						switch (this.index()){
							case 2: 
								try{
									indexRow = _datatable.row( row ).index();								
									lookup_ajax_modal.show("<?php echo @$lookup_doctor ?>/"+ indexRow);
								} catch(ex){}
							break;
							case 0: 
								indexRow = _datatable.row( row ).index();
								ajax_modal.show("<?php echo $view_service ?>/"+ indexRow +"/"+ data.JasaID);
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
								
								var response = $.parseJSON(response);
								
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
								
								var response = $.parseJSON(response);
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
				calculate_balance: function(){
						var service_total = 0;
						_table = $('#dt_services').DataTable().rows().data();
						$.each(_table, function (index, value) {
							service_total = service_total + value.Tarif;
						});
						
						$('#dt_services_total').html(mask_number.currency_add(service_total));
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
												console.log(meta);
												var buttons = '<div class="btn-group" role="group">';
													buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn btn-danger btn-remove btn-xs"><i class="fa fa-trash"></i></a>';
													buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:edit" ) ?>" class="btn btn-info btn-edit btn-xs"><i class="fa fa-pencil"></i></a>';
												buttons += '</div>';
												
												return buttons;
											} 
										},
										{ data: "JasaName", className: "" },
										{ 
											data: "Nama_Supplier",
											render: function(val){
												return '<i class="fa fa-pencil text-info" style="cursor: pointer;"></i> '+ val
											}
										},
										{ data: "Qty", className: "" },
										{ 
											data: "Tarif",
											className: "text-right",
											render: function(val){
												return mask_number.currency_add(val);
											}
										},
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_balance();
								},
								createdRow: function( row, data, index){	
										_datatable_actions.get_component_service( data, index );
										$( row ).on( "dblclick", "td", function(e){
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
											_datatable_actions.edit.call( elem, row, data, index );						
										});
									}
							} );
							
						$( "#dt_services_length select, #dt_services_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		var _form_actions = {
			init: function(){
				$('#Tanggal').datetimepicker({format: "YYYY-MM-DD"});
				
				$('#Tanggal').on('dp.change', function(e){
					_form_actions.getInpatientService( $(this).val() );
				});
			},
			getInpatientService: function( date ){
				
				var params = {f:{
					NoReg : '<?php echo $item->NoReg ?>',
					SectionID : '<?php echo $item->SectionID ?>',
					Tanggal : date
				}};
				
				$.get("<?php echo $get_inpatient_service ?>", params, function( response, status, xhr ){
													
					if( "error" == response.status ){
						$.alert_error(response.message);
						return false
					}
					
					if( response.NoBukti ){
						$('#NoBukti').val(response.NoBukti);
						$('#btn-delete-inpatient-service').prop('href', '<?php echo base_url( $nameroutes) ?>/form_service_delete/'+ response.NoBukti);
					}
					
					if( response.collection ){
						_datatable.clear().draw();
						_datatable.rows.add(response.collection).draw();
					}
					
				});
			}
		};
		
		$( document ).ready(function(e) {
            	$("#dt_services").dt_services();
				
				_form_actions.init();

				$("#form_service").find(".datepicker").datetimepicker({format: "YYYY-MM-DD"});
				$("#form_service").find(".timepicker").datetimepicker({format: "HH:mm:ss"});
				
				$("#form_service").on("submit", function(e){
					e.preventDefault();	
					
					if( !confirm("<?php echo lang("poly:save_confirm_message")?>") ){
						return false;
					}
					
					try{
						var data_post = { };
							data_post['rj'] = {};
							data_post['service'] = {};
							data_post['service_component'] = {};
							data_post['service_consumable'] = {};
							
						var rj = {
								NoBukti : $('#NoBukti').val(),
								RegNo : $("#RegNo").val(),
								Tanggal : $("#Tanggal").val(),
								Jam : $("#Tanggal").val() +' '+ $("#Jam").val(),
								JenisKerjasamaID : $("#JenisKerjasamaID").val(),
								SectionID : "<?php echo $item->SectionID ?>",
								DokterID : $("#DokterID").val(),
								RawatInap : 1,
								Jumlah : mask_number.currency_remove($('#dt_services_total').html()),
								Umur_Th : $("#UmurThn").val(),
								Umur_Bln : $("#UmurBln").val(),
								Umur_Hr : $("#UmurHr").val(),
								NRM : $("#NRM").val(),
								Gender : $("#JenisKelamin").val(),
								NamaPasien : $("#NamaPasien").val(),
								JenisKelamin : $("#JenisKelamin").val(),
								TglLahir : $("#TglLahir").val(),
								CustomerKerjasamaID : $("#CustomerKerjasamaID").val(),
								NoKamarPerawatan : $('Kamar').val(),
								NoBed : $('#NoBed').val(),
								ClosedTransaksi: $('#ClosedTransaksi').is(':checked') ? 1 : 0,
								KdKelas: $("#KdKelas").val(),
							}
						
						data_post['rj'] = rj;						
						
						var dt_services = $( "#dt_services" ).DataTable().rows().data();
						if ( dt_services )
						{							
							dt_services.each(function (value, index) {
								var detail = {
									NoBukti : $("#NoBukti").val(),
									JasaID	: value.JasaID,
									DokterID	: value.DokterID || "XX",
									KelasAsalID	: $("#KelasAsalID").val(),
									KelasID : $("#KdKelas").val(),
									Titip : 0,
									ListHargaID : value.ListHargaID,
									Qty : value.Qty,
									Tarif : value.Tarif,
									Keterangan: $('#DocterName').val() || 'XX',
									UserID : value.User_id,
									NoKartu : $("#NoAnggota").val(),
									NRM : $("#NRM").val(),
									JenisKerjasamaID : $("#JenisKerjasamaID").val(),
									HargaOrig : mask_number.currency_remove($('#dt_services_total').html()),
								}
								
								data_post['service'][index] = detail;
								
								if ( ! $.isEmptyObject(value.component_temp) )
								{
									// service component
									data_post['service_component'][value.JasaID] = {};
									$.each(value.component_temp, function (key, val) {
										data_post['service_component'][value.JasaID][key] = {
											NoBukti : $("#NoBukti").val(),
											//Nomor : no_comp,
											JasaID : value.JasaID,
											KomponenID : val.KomponenID,
											Harga : val.HargaBaru,
											KelompokAkun : val.KelompokAkun,
											PostinganKe : val.PostinganKe,
											HargaOrig : val.HargaAwal,
											HargaAwal : val.HargaAwal,
											HargaAwalOrig : val.HargaAwal,
											HargaOrigMA : val.HargaAwal,
											ListHargaID : val.ListHargaID,
											Manual : 0,
										}
									});
								}
								
								if ( ! $.isEmptyObject(value.consumable_temp) )
								{
									// service service_consumable
									data_post['service_consumable'][value.JasaID] = {};
									$.each(value.consumable_temp, function (key, val) {
										data_post['service_consumable'][value.JasaID][key] = {
											NoBUkti : $("#NoBukti").val(),
											//Nomor : no_bhp,
											JasaID : value.JasaID,
											Barang_ID : val.Barang_ID,
											Satuan : val.Satuan,
											Qty : val.Qty,
											Disc : val.Disc,
											Harga : val.Harga,
											HPP : val.HPP,
											RI : 0,
											KelasID : $("#KdKelas").val(),
											PasienKTP : $("#PasienKTP").val(),
											Stok : val.Stok,
											Ditanggung : 1,
											JenisBarangId : 0,
											Qty_JasaID : 1,
											HargaOrig : val.HargaOrig,
										}
									});
								}
							});
						}
						
						$.post($(this).attr("action"), data_post, function( response, status, xhr ){
							if( "error" == response.status ){
								$.alert_error(response.status);
								return false
							}							
							if( !response.NoBukti ){
								$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
								return false
							}
							
							get_inpatient_examination();
							
							$.alert_success( response.message );							
							$('#btn-close-form').trigger('click');
						});
						
					} catch (e){ console.log(e);}
				});
				
				function get_inpatient_examination()
				{
					$.post('<?php echo $get_inpatient_examination; ?>', function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}							
						if( !response.collection ){
							$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
							return false
						}
						
						$('#dt_examination_inpatient').DataTable().clear().draw();
						$('#dt_examination_inpatient').DataTable().rows.add(response.collection).draw();
					});
				}
				
			});

	})( jQuery );
//]]>
</script>