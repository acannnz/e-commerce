<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( $form_action, array("id" => "form_verify_result") ); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('laboratory:patient_label') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'No Lab' ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="NoBill" name="f[NoBill]" value="<?php echo @$item->NoBill ?>" placeholder="" class="form-control" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Tanggal' ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tanggal" name="f[Tanggal]" value="<?php echo substr(@$item->Tanggal, 0, 10) ?>" placeholder="" class="form-control" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Sample ID' ?></label>
					<div class="col-lg-9">
						<input type="text" id="SampleID" name="f[SampleID]" value="<?php echo @$item->SampleID ?>" placeholder="" class="form-control" maxlength="8">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Jenis Sample' ?></label>
					<div class="col-lg-9">
						<input type="text" id="JenisSample" name="f[JenisSample]" value="<?php echo @$item->JenisSample ?>" placeholder="" class="form-control" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Keterangan' ?></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control" ><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'NRM' ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Nama Pasien' ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="f[NamaPasien]" value="<?php echo @$item->PasienNama ?>" placeholder="" class="form-control">
					</div>
				</div>				  
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Tanggal Lahir' ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" id="TglLahir" name="f[TglLahir]" value="<?php echo substr(@$item->TglLahir, 0, 10) ?>" placeholder="" class="form-control" />
							<div class="input-group-addon">Umur</div>
							<input type="text" id="TglLahir" name="f[TglLahir]" value="<?php echo sprintf("%s Th %s Bln %s Hr", floor($item->Pasien_UmurTh), floor($item->Pasien_UmurBln), floor($item->Pasien_UmurHr)) ?>" placeholder="" class="form-control" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Jenis Kelamin' ?></label>
					<div class="col-lg-9">
						<input type="text" id="JenisKelamin" name="f[JenisKelamin]" value="<?php echo @$item->JenisKelamin == 'F' ? 'Perempuan' : 'Laki-laki' ?>" placeholder="" class="form-control">
					</div>
				</div>	
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Alamat' ?></label>
					<div class="col-lg-9">
						<textarea id="Alamat" name="f[Alamat]" placeholder="" class="form-control" ><?php echo @$item->Alamat ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Dokter' ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$doctor->DokterID ?>">
						<input type="text" value="<?php echo @$doctor->Nama_Supplier ?>" placeholder="" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Analis' ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="hidden" id="AnalisID" name="f[AnalisID]" value="<?php echo @$analysis->DokterID ?>">
						<input type="text" value="<?php echo @$analysis->Nama_Supplier ?>" placeholder="" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Jenis Sample' ?></label>
					<div class="col-lg-9">
						<input type="text" id="JenisSample" name="f[JenisSample]" value="<?php echo @$item->JenisSample ?>" placeholder="" class="form-control" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Keterangan' ?></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control" ><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
				<?php /*?><div class="form-group">
					<label class="col-lg-3 control-label">Opsi</label>
					<div class="col-md-3">
						<div class="checkbox">
							<input type="hidden" name="f[Approved]" value="0" >
							<input type="checkbox" id="Approved" name="f[Approved]" value="1" <?php echo @$item->Tampilkan == 1 ? "Checked" : NULL ?>><label for="Approved">Disetujui</label>
						</div>
					</div>
					<div class="col-md-3">
						<div id="checkbox-tampilkan" class="checkbox <?php echo @$item->Tampilkan == 1 ? NULL : "hidden" ?>">
							<input type="hidden" name="f[Tampilkan]" value="0" >
							<input type="checkbox" id="Tampilkan" name="f[Tampilkan]" value="1" <?php echo @$item->Tampilkan == 1 ? "Checked" : NULL ?>><label for="Tampilkan">Publish</label>
						</div>
					</div>
				</div><?php */?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<table id="dt_verify_result" class="table table-bordered" width="100%">
						<thead>
							<tr>
								<th><?php echo "Kategori" ?></th>
								<th><?php echo "TestID" ?></th>                        
								<th><?php echo "Nama Test" ?></th>                        
								<th><?php echo "Hasil" ?></th>                        
								<th><?php echo "Nilai Rujukan" ?></th>                        
								<th><?php echo "Satuan" ?></th>                        
								<th><?php echo "Flag" ?></th>                        
								<th><?php echo "Keterangan" ?></th>                        
								<?php /*?><th><?php echo "Tampilkan" ?></th><?php */?>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
    <div class="col-md-6">
		<a id="btn-verify-results" href="<?php echo current_url()?>" class="btn btn-danger"><?php echo 'Proses Verifikasi' ?></a>
		<a target="_blank" href="<?php echo $print_url?>" class="btn btn-info"><?php echo lang('buttons:print') ?></a>
	</div>
	<div class="col-md-6 text-right">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_verify_result");
		var _datatable;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){			
							case 3:
							
								var _input = $( "<input type='text' value='" + (data.Nilai || data.NilaiRujukan) + "' style='width:100%'  class='form-control'>" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
   
										try{
											
											data.Nilai = this.value;
											_datatable.row( row ).data( data );
											
										} catch(ex){}
								});								
							break;
																								
							case 6:
								var _input = $( "<select style='width:100%' class='form-control'> \
													<option value='N'>N</option> \
													<option value='A'>A</option> \
													<option value='H'>H</option> \
													<option value='L'>L</option> \
												</option>\n</select>" );
								this.empty().append( _input );
								
								_input.val(data.HasilTidakNormal_Flag);								
								_input.trigger( "focus" )
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
											var _selected = $( e.target ).find( "option:selected" ) || {};
											data.HasilTidakNormal_Flag = _selected.val() || 'N';
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;
							
						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope ).remove().draw();
					},
				get_queue: function( scope, params ){					
						$.post('<?php echo @$get_queue_link ?>', params, function( response, status, xhr ){							
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}							
							params.NoUrut =  response.queue;
							_datatable.row( scope ).data( params ).draw();
						});						
					},
			};
			
		$.fn.extend({
				dt_verify_result: function(){
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
								data: <?php print_r(json_encode($collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "KategoriTestNama", 
											className: "", 
										},
										{ data: "TestID", className: "text-center",  },
										{ data: "NamaTest",},
										{ data: "Nilai",},
										{ data: "NilaiRujukan",},
										{ data: "Satuan",},
										{ data: "HasilTidakNormal_Flag",},
										{ 
											data: "Keterangan",
											width: '300px',
											render: function(val){
												return typeof val != "" ? val : '-';
											} 
										},
										//{ data: "Tampilkan",},										
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										$( row ).on( "click", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
									}
							} );
							
						$( "#dt_verify_result_length select, #dt_verify_result_filter input" )
						.addClass( "form-control" );
						
						return _this
					}
			});
	
		$( document ).ready(function(e) {			
			$( "#dt_verify_result" ).dt_verify_result();
			
			$("#Approved").on("change", function(){
				if($(this).is(":checked")){
					$("#checkbox-tampilkan").removeClass("hidden");
				} else {
					$("#checkbox-tampilkan").addClass("hidden");
				}
			});
			
			_form.on("submit", function(e){
				e.preventDefault();
				
				var data_post = $(this).serializeArray();
				var table_data = $( "#dt_verify_result" ).DataTable().rows().data();
					table_data.each(function (v, i) {
						data_post.push({name: 'results['+ i +'][Nilai]', value: v.Nilai});
						data_post.push({name: 'results['+ i +'][NilaiRujukan]', value: v.NilaiRujukan});
						data_post.push({name: 'results['+ i +'][Satuan]', value: v.Satuan});
						data_post.push({name: 'results['+ i +'][Keterangan]', value: v.Keterangan});
						data_post.push({name: 'results['+ i +'][NoSystem]', value: $("#NoBill").val()});
						data_post.push({name: 'results['+ i +'][SampelID]', value: $("#SampelID").val()});
						data_post.push({name: 'results['+ i +'][TestID]', value: v.TestID});
						data_post.push({name: 'results['+ i +'][Harga]', value: v.Harga});
						data_post.push({name: 'results['+ i +'][NamaTest]', value: v.NamaTest});
						data_post.push({name: 'results['+ i +'][MesinID]', value: '0'});	
						data_post.push({name: 'results['+ i +'][HasilTidakNormal_Flag]', value: v.HasilTidakNormal_Flag});							
											
					});	
				
				$.post($(this).attr("action"), data_post, function( response, status, xhr ){					
					if( "error" == response.status ){
						$.alert_error(response.message);
						return false
					}
									
					$.alert_success( response.message );
					$('#btn-view-result').removeClass('disabled');
				});						
			});
			
			$("#btn-verify-results").on("click", function(e){
				e.preventDefault();
								
				var data_post = _form.serializeArray();
				var table_data = $( "#dt_verify_result" ).DataTable().rows().data();
					table_data.each(function (v, i) {
						data_post.push({name: 'results['+ i +'][Nilai]', value: v.Nilai});
						data_post.push({name: 'results['+ i +'][NilaiRujukan]', value: v.NilaiRujukan});
						data_post.push({name: 'results['+ i +'][Satuan]', value: v.Satuan});
						data_post.push({name: 'results['+ i +'][Keterangan]', value: v.Keterangan});
						data_post.push({name: 'results['+ i +'][NoSystem]', value: $("#NoBill").val()});
						data_post.push({name: 'results['+ i +'][SampelID]', value: $("#SampelID").val()});
						data_post.push({name: 'results['+ i +'][TestID]', value: v.TestID});
						data_post.push({name: 'results['+ i +'][Harga]', value: v.Harga});
						data_post.push({name: 'results['+ i +'][NamaTest]', value: v.NamaTest});
						data_post.push({name: 'results['+ i +'][MesinID]', value: '0'});							
						data_post.push({name: 'results['+ i +'][HasilTidakNormal_Flag]', value: v.HasilTidakNormal_Flag});							
					});	
				
				$.post($(this).attr("href"), data_post, function( response, status, xhr ){					
					if( "error" == response.status ){
						$.alert_error(response.message);
						return false
					}
									
					$.alert_success( response.message );
					
					$.each(response.collection, function(i, val){
						var rowIndex;
						getTest = $("#dt_verify_result").DataTable().rows( function ( idx, data, node ) {
								if(data.TestID === val.TestID){
									rowIndex = idx;
									return true;
								}
								return false;
							} ).data();
						
						getTest[0].HasilTidakNormal_Flag = val.HasilTidakNormal_Flag;
						$("#dt_verify_result").DataTable().row( rowIndex ).data( getTest[0] ).draw();
					});
				})								
			})
			
		});

	})( jQuery );
//]]>
</script>