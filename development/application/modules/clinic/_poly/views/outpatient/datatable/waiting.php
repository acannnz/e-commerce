<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('poly:date_from_label') ?></label>
            <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="date_from_w" class="form-control searchable_w datepicker" value="<?php echo date("Y-m-d")?>" />
				<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
                <input type="text" id="date_till_w" class="form-control searchable_w datepicker" value="<?php echo date("Y-m-d") ?>" />
            </div>
        </div>
	</div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('poly:patient_label') ?></label>
            <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
                <input type="text" id="NRM_w" class="form-control searchable_w mask_nrm" placeholder="<?php echo lang('poly:mr_number_label')?>" />
				<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
                <input type="text" id="Nama_w" class="form-control searchable_w" placeholder="<?php echo lang('poly:name_label') ?>"/>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('poly:doctor_label') ?></label>
			<select id="DokterID_w" class="form-control searchable_option_w">
				<option value="" selected><?php echo lang("global:select-none")?></option>
				<?php foreach($option_doctor as $k => $v): ?>
				<option value="<?php echo $k ?>" ><?php echo $v ?></option>
				<?php endforeach;?>
			</select>
        </div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
            <label class="control-label"><?php echo lang('poly:section_label') ?></label>
			<select id="SectionID_w" class="form-control searchable_option_w">
				<option value=""><?php echo lang("global:select-none")?></option>
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
                <th>Jam Appointment</th>
                <th>NoReg</th>
                <th>Waktu</th>
                <th>N.R.M</th>
                <th>Nama Pasien</th>
                <th>Jenis Kelamin</th>
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
		<?php if(config_item('use_websocket') == 'TRUE'): ?>
			var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
		<?php endif; ?>
		$(document).ready(function(e) {
			<?php if(config_item('use_websocket') == 'TRUE'): ?>
				socket.onmessage = function(e) {
					if (e.data == "queue_refresh") {
						$("#dt-data-waitings").DataTable().ajax.reload();
						$("#dt-data-checkups").DataTable().ajax.reload();
						var data_post = {
								date_from : $("#date_from_w").val(),
								date_till : $("#date_till_w").val(),
								SectionID : $("#SectionID_w").val()
							};
						$.post('<?php echo $cek_data ?>',data_post,function(response, status, xhr) {
							search_datatable_w.check_open(response.queue_left);
						});

					}
				};
			<?php endif; ?>
		});
		var search_datatable_w = {
			init : function(){
					var timer = 0;
			
					$( ".searchable_w" ).on("keyup", function(e){
						e.preventDefault();
						
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable_w.reload_table , 600 ); 					
						}
							
					});
	
					$( ".searchable_option_w" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_w.reload_table , 600 ); 
							
					});
					
					$("#date_from_w, #date_till_w").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_w.reload_table , 600 ); 
	
					});
				},
			reload_table : function(){
					$( "#dt-data-waitings" ).DataTable().ajax.reload();
				},
			check_open: function(count) {				
			
				if(count > 0){
					$.notif_long(`Terdapat ${count} Pasien yang belum direalisasi`);
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
										params.date_from = $("#date_from_w").val();	
										params.date_till = $("#date_till_t").val();	

										params.NRM = $("#NRM_w").val() || "";
										params.Nama = $("#Nama_w").val() || "";
										//params.DokterID = $("#DokterID_w").val() || "";	
										params.SectionID = $("#SectionID_w").val() || "";	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "Waktu", 
										className: "text-center",
										name: "b.Waktu",
										width: "50px",
										render: function(val, type, row) {
											if (!row.Waktu || row.Waktu.substr(11, 5) === "00:00") {
												return "";
											} else {
												return row.Waktu.substr(11, 5);
											}
										}
									},
									{ 
										data: "NoReg", 
										width: "180px",
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
									{ data: "JenisKelamin", class: "text-center",
										render:function(val)
										{
											return (val == 'F') ? 'P' : 'L';
										}
									},
									{ data: "Nama_Supplier", width: null },
									{ data: "JenisKerjasama", class: "text-center" },
									{ 
										data: "NoReg",
										className: "text-right",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = '<div class="btn-group pull-right" role="group">';
													buttons += '<a href="<?php echo base_url("{$nameroutes}/create") ?>/' + row.NoReg + '/'+ row.SectionID +'" title="Periksa Pasien" class="btn btn-info btn-xs"> <i class="fa fa-stethoscope"></i> Periksa</a>';
												buttons += '</div>';
												
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
				search_datatable_w.init();
            	$( "#dt-data-waitings" ).DataTable_DataWaitings();
				
			});
	})( jQuery );
//]]>
</script>