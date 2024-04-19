<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:date_from_label') ?></label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				<input type="text" id="date_from_o" class="form-control searchable_o datepicker" value="<?php echo date("Y-m-d")?>" />
				<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
				<input type="text" id="date_till_o" class="form-control searchable_o datepicker" value="<?php echo date("Y-m-d") ?>" />
			</div>
        </div>
	</div>
	<div class="col-md-3">	
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:patient_label') ?></label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-id-card-o"></i></div>
				<input type="text" id="NRM_o" class="form-control searchable_o mask_nrm" placeholder="<?php echo lang('pharmacy:mr_number_label') ?>"/>
				<div class="input-group-addon"><i class="fa fa-wheelchair"></i></div>
				<input type="text" id="Nama_o" class="form-control searchable_o" placeholder="<?php echo lang('pharmacy:name_label') ?>"/>
			</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:doctor_label') ?></label>
			<select id="DokterID_o" class="form-control searchable_option_o">
				<option value=""><?php echo lang("global:select-none")?></option>
				<?php if($option_doctor): foreach($option_doctor as $row): ?>
				<option value="<?php echo $row->Kode_Supplier ?>"><?php echo $row->Nama_Supplier ?></option>
				<?php endforeach;endif; ?>
			</select>
        </div>
	</div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:section_label') ?></label>
			<select id="SectionID_o" class="form-control searchable_option_o">
				<option value=""><?php echo lang("global:select-none")?></option>
				<?php if($option_section): foreach($option_section as $k => $v): ?>
				<option value="<?php echo $k ?>" <?php echo $k == $pharmacy['section_id'] ? 'selected' : NULL ?>><?php echo $v ?></option>
				<?php endforeach;endif; ?>
			</select>
        </div>
	</div>
</div>
<div class="table-responsive">
    <table id="dt-data-waitings" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>NoResep</th>
                <th>NoRegistrasi</th>
                <th>Tanggal</th>
                <th>N.R.M</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Jenis Pasien</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var check_open;
		// var socket = new WebSocket('ws://localhost:8080');
		<?php if(config_item('use_websocket') == 'TRUE'): ?>
			var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
		<?php endif; ?>
		$(document).ready(function(e) {
			<?php if(config_item('use_websocket') == 'TRUE'): ?>
				socket.onmessage = function(e) {
					if (e.data == "refresh_queue") {
						setTimeout($("#dt-data-waitings").DataTable().ajax.reload(), 600);
						setTimeout($("#dt-data-checkups").DataTable().ajax.reload(), 600);
						var data_post = {
								date_from : $("#date_from_o").val(),
								date_till : $("#date_till_o").val()
							};
						$.post('<?php echo $cek_resep_url ?>',data_post,function(response, status, xhr) {
							search_datatable_o.check_open(response.queue_left);
						});

					}
				};
			<?php endif; ?>
		});
		var search_datatable_o = {
			init : function(){
					var timer = 0;
			
					$( ".searchable_o" ).on("keyup", function(e){
						e.preventDefault();
						
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable_o.reload_table , 600 ); 					
						}
							
					});
	
					$( ".searchable_option_o" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_o.reload_table , 600 ); 
							
					});
					
					$("#date_from_o, #date_till_o").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_o.reload_table , 600 ); 
	
					});
							
					$("#reset_o").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_o.reload_table , 600 ); 
					});

					setInterval(function(){ 
						search_datatable_o.reload_table();
					}, 30000);
					
				},
			reload_table : function(){
					$( "#dt-data-waitings" ).DataTable().ajax.reload();
				},
			check_open: function(count) {				
			
				if(count > 0){
					$.notif_long(`Terdapat ${count} resep yang belum direalisasi`);
				}
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
							order: [[1, 'desc']],
							searching: false,
							info: true,
							responsive: true,
							lengthChange: false,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("pharmacy/pharmacies/data_open/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_o").val();	
										params.date_till = $("#date_till_o").val();	

										params.NRM = $("#NRM_o").val() || "";
										params.Nama = $("#Nama_o").val() || "";
										params.DokterID = $("#DokterID_o").val() || "";
										params.SectionID = $("#SectionID_o").val() || "";											
									}
								},
							fnDrawCallback: function( settings ){ 
								$( window ).trigger( "resize" ); 
								search_datatable_o.check_open(settings.json.recordsFiltered);
								
							},

							// fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							// "drawCallback": function( settings, start, end, max, total, pre ) {  
							// 	var rowCount = this.fnSettings().fnRecordsTotal();
							// 		if(rowCount > 0)
							// 		{
							// 			try{ $( "#notification" ).get(0).play(); }catch(ex){}
							// 		}
							// 		$( window ).trigger( "resize" );
							// },
							columns: [
									{ 
										data: "NoResep", 
										className: "text-center",
										name: "a.NoResep",
										width: "150px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "NoReg", 
										width: "120px",
										class: "text-center",
										name: "b.NoReg",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
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
									{ data: "NamaPasien", width: null },
									{ data: "Nama_Supplier", width: null },
									{ data: "JenisKerjasama", class: "text-center" },
									{ 
										data: "NoResep",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("pharmacy/selling") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-primary btn-xs\"><b><i class=\"fa fa-handshake-o\"></i> Realisasi</b></a>";
													buttons += "</div>";
												
												return buttons
											}
									}
								],
						} );
						
					$( "#dt-data-waitings_length select, #dt-data-waitings_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});		
		
		$( document ).ready(function(e) {
            	$( "#dt-data-waitings" ).DataTable_DataWaitings();
				search_datatable_o.init();

				
			});
	})( jQuery );
//]]>
</script>