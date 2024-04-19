<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<!--<div class="row">
        <div class="col-md-6">
            <h3 class="text-info">Daftar Pemeriksaan Poli Umum</h3>
            <p>Pasien Poli Umum akan dikelola mulai dari sini.</p>
        </div>
	</div>-->
</div>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('poly:list_heading') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<div class="checkbox" style="margin-top:0 !important">
						<input type="checkbox" id="show_history" value="1" class="" ><label for="show_history">History</label>
					</div>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('poly:patient_label') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
						<input type="text" id="NRM" class="form-control searchable mask_nrm" placeholder="<?php echo lang('poly:mr_number_label')?>" />
						<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
						<input type="text" id="Nama" class="form-control searchable" placeholder="<?php echo lang('poly:name_label') ?>"/>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('poly:doctor_label') ?></label>
					<select id="DokterID" class="form-control searchable_option">
						<option value=""><?php echo lang("global:select-none")?></option>
						<?php foreach($option_doctor as $k => $v): ?>
						<option value="<?php echo $k ?>" <?php echo ($k == $medics['doctor_id']) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('poly:section_label') ?></label>
					<select id="SectionID" class="form-control searchable_option">
						<?php foreach($option_section as $k => $v): ?>
						<option value="<?php echo $k ?>" <?php echo ($k == $medics['section_id']) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="dt-data-waitings" class="table table-sm" width="100%">
				<thead>
					<tr>
						<th>NoReg</th>
						<th>Waktu</th>
						<th>N.R.M</th>
						<th>Nama Pasien</th>
						<th>Gender</th>
						<th>Dokter</th>
						<th>Jenis Pasien</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var search_datatable = {
			init : function(){
					var timer = 0;
			
					$( ".searchable" ).on("keyup", function(e){
						e.preventDefault();
						
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 					
						}
							
					});
	
					$( ".searchable_option" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 							
					});
					
					$("#date_from, #date_till").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
					});
					
					$("#show_history").on("change", function(){						
						if($(this).is(":checked"))
						{
							$("#date_from, #date_till").removeAttr('disabled');
						} else {
							$("#date_from, #date_till").attr('disabled');
						}
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
					})
				},
			reload_table : function(){
					$( "#dt-data-waitings" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_DataWaitings: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[0, 'asc']],
							searching: false,
							info: true,
							responsive: true,
							lengthChange: false,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("{$nameroutes}s/data_waiting/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										if($('#show_history').is(':checked')){
											params.date_from = $("#date_from").val();	
											params.date_till = $("#date_till").val();	
										}

										params.NRM = $("#NRM").val() || "";
										params.Nama = $("#Nama").val() || "";
										params.DokterID = $("#DokterID").val() || "";	
										params.SectionID = $("#SectionID").val() || "";	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoReg", 
										width: "180px",
										class: "text-center",
										name: "b.NoReg",
										render: function ( val, type, row ){
											var state = 'text-primary';
											var title = '';
											switch(row.StatusPeriksa){
												case 'Belum':
													state = 'text-success';
													title = 'Belum Periksa';
													break
												case 'Sudah Periksa':
													state = 'text-info';
													title = 'Sudah Periksa';
													break
												case 'CO':
													state = 'text-danger';
													title = 'Sudah Keluar';
													break
											}
											
											return '<strong class="'+ state +'" title="'+ title +'">' + val + '</strong>'
										}
									},
									{ 
										data: "Jam", 
										className: "text-center",
										width: "40px",
										name: "a.Jam",
										render: function ( val, type, row ){
											 return val.substring(0, 19)
										}
									},							
									{ 
										data: "NRM", 
										className: "text-center",
										width: "90px",
										name: "b.NRM",
										render: function ( val, type, row ){
											return "<strong class=\"text-success\">" + val + "</strong>"
										}
									},
									{ 
										data: "NamaPasien", 
										render: function ( val, type, row ){
											var gender = row.JenisKelamin == 'F' ? 'venus' : 'mars';
											return '<i class="fa fa-'+ gender +'"></i> '+ val;
											
										}
									},
									{ data: "JenisKelamin", class: "text-center" },
									{ data: "Nama_Supplier", width: null },
									{ data: "JenisKerjasama", class: "text-center" },
									{ 
										data: "NoReg",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("{$nameroutes}/examination") ?>/" + val + "/" + row.SectionID +"\" title=\"Periksa Pasien\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-stethoscope\"></i> Periksa</a>";
											buttons += "</div>";
											
											return buttons
										}
									}
								]
						} );
						
					$( "#dt-data-waitings_length select, #dt-data-waitings_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});		
		
		$( document ).ready(function(e) {
				search_datatable.init();
            	$( "#dt-data-waitings" ).DataTable_DataWaitings();
				
			});
	})( jQuery );
//]]>
</script>