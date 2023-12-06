<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_test_type", "name" => "form_test_type") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">List Data Jenis Test</h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("{$nameroutes}/create") ?>"  data-toggle="ajax-modal" title="<?php echo lang('buttons:create') ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <b><?php echo lang('buttons:create') ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label">Test ID<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" name="f[TestID]" id="TestID" value="<?php echo @$item->TestID ?>" class="form-control"/>
					</div>
				 </div>
				 <div class="form-group">
					  <label class="col-lg-3 control-label">Kategori Test</label>
					  <div class="col-lg-9">
						  <select id="KategoriTestiID" name="f[KategoriTestiID]" class="form-control">
							  <option value="0">-- Pilih Kategori Test --</option>
							  <?php if(!empty($option_test_category)): foreach($option_test_category as $row):?>
							  <option value="<?php echo $row['KategoriTestID'] ?>" <?php echo $row['KategoriTestID'] == @$item->KategoriTestiID ? "selected" : NULL  ?>><?php echo $row['KategoriTestNama'] ?></option>
							  <?php endforeach; endif;?>
						  </select>
					  </div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Nama Test<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" name="f[NamaTest]" id="NamaTest" value="<?php echo @$item->NamaTest ?>" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Key Mesin<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" name="f[KeyMesin]" id="KeyMesin" value="<?php echo @$item->KeyMesin ?>" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Satuan<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<select id="Satuan" name="f[Satuan]" class="form-control">
							<option value="0">-- Pilih Satuan --</option>
							<?php if(!empty($option_satuan)): foreach($option_satuan as $row):?>
							<option value="<?php echo $row['SatuanID'] ?>" <?php echo $row['SatuanID'] == @$item->Satuan ? "selected" : NULL  ?>><?php echo $row['SatuanID'] ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">				
				<div class="form-group">
					<label class="col-lg-3 control-label">ACN<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" name="f[ACN]" id="ACN" value="<?php echo @$item->ACN ?>" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">HCN<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" name="f[HCN]" id="HCN" value="<?php echo @$item->HCN ?>" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">HC Advia<span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" name="f[HostCodeAdvia]" id="HostCodeAdvia" value="<?php echo @$item->HostCodeAdvia ?>" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Teknik</label>
					<div class="col-lg-9">
						<select id="TeknikPemeriksaan" name="f[TeknikPemeriksaan]" class="form-control">
							<option value="0">-- Pilih Teknik Pemeriksaan --</option>
							<?php if(!empty($option_test_technique)): foreach($option_test_technique as $row):?>
							<option value="<?php echo $row['TeknikPemeriksaan'] ?>" <?php echo $row['TeknikPemeriksaan'] == @$item->TeknikPemeriksaan ? "selected" : NULL  ?>><?php echo $row['TeknikPemeriksaan'] ?></option>
							<?php endforeach; endif;?>
						</select>
					 </div>
				</div>	
				<div class="form-group">
					<label class="col-lg-3">Opsi</label>
					<div class="col-lg-3">
						<div class="checkbox">
						  <input type="checkbox" id="aktif" name="f[aktif]" <?php echo (@$item->Aktif == 1)?'checked':''; ?> value="1">
						  <label for="aktif">Aktif</label>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="checkbox">
						  <input type="checkbox" id="PakeMesin" name="f[PakeMesin]" <?php echo (@$item->PakeMesin == 1)?'checked':''; ?> value="1">
						  <label for="PakeMesin">Auto Mesin</label>
						</div>
					</div>
				</div>			
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<div class="table-responsive">
						<table id="dt_detail_test_type" class="table table-sm table-bordered" width="100%">
								<thead>
								<tr>
									<th></th>
									<th>Nilai Rujukan</th>
									<th>Satuan</th>
									<th>Kelomok Umur</th>
									<th>TypeKelahiran</th>
									<th>Gender</th>
									<th>OperatorUmur1</th>
									<th>Umur(Th)1</th>
									<th>Umur(Bln)1</th>
									<th>Umur(Hari)1</th>
									<th>OperatorUmur2</th>
									<th>Umur(Th)2</th>
									<th>Umur(Bln)2</th>
									<th>Umur(Hari)2</th>
									<th>Keterangan</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<div class="form-group">
					<a id="btn_detail_add" href="javascript:;" title="<?php echo lang( "buttons:add_product" ) ?>" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Tambahkan</a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-offset-10 col-lg-6">
				<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
				<?php /*?>//<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button><?php */?>
				<button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						switch( this.index() ){							
							case 1:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\"" + (data.NilaiRujukan || '') + "\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.NilaiRujukan = (this.value || '');
											_datatable.row( row ).data( data ).draw();
											_datatable_actions.store( data, function(){ _datatable.ajax.reload() }, row )
										} catch(ex){}
									});
							break;
							
							case 2:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _option = '<option value="'+ data.Satuan +'" >'+ data.Satuan +'</option>';
								<?php if(!empty($option_satuan)): foreach($option_satuan as $row):?>
								_option += '<option value="<?php echo $row['SatuanID'] ?>" ><?php echo $row['SatuanID'] ?></option>';
								<?php endforeach; endif;?>
								
								var _value = data.Satuan ? data.Satuan : ''
								_input.html(_option);
																
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											data.Satuan = $( this ).val();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _option = '<option value="'+ data.KelompokUmur +'" >'+ data.KelompokUmur +'</option>';							
								_option += '<option value="Child">Child</option>';
								_option += '<option value="Adult">Adult</option>';								
								_option += '<option value="Per Umur">Per Umur</option>';								
								
								var _value = data.KelompokUmur ? data.KelompokUmur : ''
								_input.html(_option);
																
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											data.KelompokUmur = $( this ).val();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 4:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _option = '<option value="'+ data.TypeKelahiran +'" >'+ data.TypeKelahiran +'</option>';							
								_option += '<option value="Normal">Normal</option>';
								_option += '<option value="Prematur">Prematur</option>';								
								
								var _value = data.TypeKelahiran ? data.TypeKelahiran : ''
								_input.html(_option);
																
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											data.TypeKelahiran = $( this ).val();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 5:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _option = '<option value="'+ data.Sex +'" >'+ data.Sex +'</option>';							
								_option += '<option value="M">Male</option>';
								_option += '<option value="F">Female</option>';
								_option += '<option value="A">All</option>';								
								
								var _value = data.Sex ? data.Sex : ''
								_input.html(_option);
																
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											data.Sex = $( this ).val();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 6:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _option = '<option value="'+ data.OperatorUmur1 +'" >'+ data.OperatorUmur1 +'</option>';							
								_option += '<option value=">">></option>';
								_option += '<option value=">=">>=</option>';
								
								var _value = data.OperatorUmur1 ? data.OperatorUmur1 : ''
								_input.html(_option);
																
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											data.OperatorUmur1 = $( this ).val();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 7:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Umur_Th_1 || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											if(_input.val() < 0)
											{
												alert("Angka input tidak Valid!");
												_input.val("0");
												
											}       
											
											data.Umur_Th_1 = this.value || 0;											
											_datatable.row( row ).data( data ).draw();
											
										} catch(ex){}
									});
							break;
							
							case 8:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Umur_Bln_1 || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											if(_input.val() < 0)
											{
												alert("Angka input tidak Valid!");
												_input.val("0");
												
											}       
											data.Umur_Bln_1 = this.value || 0;
											_datatable.row( row ).data( data ).draw();											
										} catch(ex){}
									});
							break;
							
							case 9:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Umur_Hari_1 || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											if(_input.val() < 0){
												alert("Angka input tidak Valid!");
												_input.val("0");
											}       
											data.Umur_Hari_1 = this.value || this.value;
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 10:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _option = '<option value="'+ data.OperatorUmur2 +'" >'+ data.OperatorUmur2 +'</option>';							
								_option += '<option value="<"> < </option>';
								_option += '<option value="<="> <= </option>';
								
								var _value = data.OperatorUmur2 ? data.OperatorUmur2 : ''
								_input.html(_option);
																
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											data.OperatorUmur2 = $( this ).val();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 11:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Umur_Th_2 || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											if(_input.val() < 0)
											{
												alert("Angka input tidak Valid!");
												_input.val("0");
												
											}       
											
											data.Umur_Th_2 = this.value || 0;											
											_datatable.row( row ).data( data ).draw();
											
										} catch(ex){}
									});
							break;
							
							case 12:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Umur_Bln_2 || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											if(_input.val() < 0)
											{
												alert("Angka input tidak Valid!");
												_input.val("0");
												
											}       
											data.Umur_Bln_2 = this.value || 0;
											_datatable.row( row ).data( data ).draw();											
										} catch(ex){}
									});
							break;
							
							case 13:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Umur_Hari_2 || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											if(_input.val() < 0){
												alert("Angka input tidak Valid!");
												_input.val("0");
											}       
											data.Umur_Hari_2 = this.value || this.value;
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
							break;
							
							case 14:
							
								var _input = $( "<input type=\"text\" value=\"" + (data.Keterangan || '') + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										//console.log(_input.val());
										//var min = parseInt(_input.attr('min'));
										if(_input.val() < 0)
										{
											alert("Angka input tidak Valid!");
											_input.val("0");
											
										}       
										try{
											data.Keterangan = this.value || this.value;
											
											_datatable.row( row ).data( data ).draw();
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
						}
					},
				remove: function( params, fn, scope ){						
						_datatable.row( scope ).remove().draw();
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
								"action" : "<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>",
								"NilaiRujukan" : '',
								"Satuan" : '',
								"TypeKelahiran" : 'Normal',
								"Sex" : '',
								"KelompokUmur" : 'Per Umur',
								"OperatorUmur1" : '',
								"Umur_Th_1" : 0,
								"Umur_Bln_1" : 0,
								"Umur_Hari_1" : 0,
								"OperatorUmur2" : '',
								"Umur_Th_2" : 0,
								"Umur_Bln_2" : 0,
								"Umur_Hari_2" : 0,
								"Keterangan" : '',
							}
						).draw(false);
					}
					
			};
		
		$.fn.extend({
				dt_detail_test_type: function(){
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
								autoWidth: true,
								responsive: true,
								<?php if (!empty($is_edit)):?>
								data: <?php print_r(json_encode($populate_reference_value, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "TestID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove btn-xs\"><i class=\"fa fa-trash\"></i></a>")
												} 
										},
										{ 
											data: "NilaiRujukan", 
											className: "", 
										},
										{ data: "Satuan", className: "text-left", width:'2000' },
										{ data: "KelompokUmur"},
										{ data: "TypeKelahiran", className: "text-center", },
										
										{ data: "Sex", className: "text-center", },
										{ data: "OperatorUmur1"},
										{ data: "Umur_Th_1"},
										{ data: "Umur_Bln_1"},
										{ data: "Umur_Hari_1"},
										{ data: "OperatorUmur2"},
										{ data: "Umur_Th_2"},
										{ data: "Umur_Bln_2"},
										{ data: "Umur_Hari_2"},
										{ data: "Keterangan"}
									
										
									],
								columnDefs  : [
										{
											"targets": ["UmurTotal_Hr","UmurTotal_Hr2"],
											"visible": false,
											"searchable": false
										}
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
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									}
							} );
							
						$( "#dt_detail_test_type_length select, #dt_detail_test_type_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_detail_test_type" ).dt_detail_test_type();
								
				$("#btn_detail_add").on("click", function(){
					_datatable_actions.add_row();
				});
				
								
				$("form[name=\"form_test_type\"]").on("submit", function(e){
					e.preventDefault();	
											
					var data_post = $(this).serializeArray();
										
					var table_data = $( "#dt_detail_test_type" ).DataTable().rows().data();
					table_data.each(function (v, i) {
						var UmurTotal_Hr = (v.Umur_Th_1 * 365) + (v.Umur_Bln_1 * 12) + v.Umur_Hari_1;
						var UmurTotal_Hr2 = (v.Umur_Th_2 * 365) + (v.Umur_Bln_2 * 12) + v.Umur_Hari_2;
						
						data_post.push({name: 'reference_value['+ i +'][TestID]', value: $("#TestID").val()});
						data_post.push({name: 'reference_value['+ i +'][NilaiRujukan]', value: v.NilaiRujukan});
						data_post.push({name: 'reference_value['+ i +'][Satuan]', value: v.Satuan});
						data_post.push({name: 'reference_value['+ i +'][KelompokUmur]', value: v.KelompokUmur});
						data_post.push({name: 'reference_value['+ i +'][TypeKelahiran]', value: v.TypeKelahiran});
						data_post.push({name: 'reference_value['+ i +'][Sex]', value: v.Sex});
						
						data_post.push({name: 'reference_value['+ i +'][OperatorUmur1]', value: v.OperatorUmur1});
						data_post.push({name: 'reference_value['+ i +'][Umur_Th_1]', value: v.Umur_Th_1});
						data_post.push({name: 'reference_value['+ i +'][Umur_Bln_1]', value: v.Umur_Bln_1});
						data_post.push({name: 'reference_value['+ i +'][Umur_Hari_1]', value: v.Umur_Hari_1});
						//data_post.push({name: 'reference_value['+ i +'][UmurTotal_Hr]', value: UmurTotal_Hr});
						
						data_post.push({name: 'reference_value['+ i +'][OperatorUmur2]', value: v.OperatorUmur2});
						data_post.push({name: 'reference_value['+ i +'][Umur_Th_2]', value: v.Umur_Th_2});
						data_post.push({name: 'reference_value['+ i +'][Umur_Bln_2]', value: v.Umur_Bln_2});
						data_post.push({name: 'reference_value['+ i +'][Umur_Hari_2]', value: v.Umur_Hari_2});
						//data_post.push({name: 'reference_value['+ i +'][UmurTotal_Hr2]', value: UmurTotal_Hr2});
						
						data_post.push({name: 'reference_value['+ i +'][Keterangan]', value: v.Keterangan});
					});
							
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);
						setTimeout(function(){
							document.location.href = "<?php echo base_url("laboratory/test-type"); ?>/";
							}, 300 );
						
					})	
				});

			});
	})( jQuery );
//]]>
</script>