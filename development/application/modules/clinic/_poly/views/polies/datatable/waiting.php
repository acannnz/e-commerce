<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:date_from_label') ?></label>
            <div class="col-md-3">
                <input type="text" id="date_from_w" class="form-control searchable_w datepicker" value="<?php echo date("Y-m-d")?>" />
            </div>
            <label class="col-md-3 control-label text-center"><?php echo lang('poly:date_till_label') ?></label>
            <div class="col-md-3">
                <input type="text" id="date_till_w" class="form-control searchable_w datepicker" value="<?php echo date("Y-m-d") ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:mr_number_label') ?></label>
            <div class="col-md-3">
                <input type="text" id="NRM_w" class="form-control searchable_w mask_nrm" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:name_label') ?></label>
            <div class="col-md-9">
                <input type="text" id="Nama_w" class="form-control searchable_w" />
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:doctor_label') ?></label>
            <div class="col-md-9">
            	<select id="DokterID_w" class="form-control searchable_option_w">
                	<option value=""><?php echo lang("global:select-none")?></option>
                	<?php foreach($option_doctor as $k => $v): ?>
                    <option value="<?php echo $k ?>" <?php echo ($k == $this->session->userdata('doctor_id')) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
	            <button id="reset_w" type="reset" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset")?></b></button>
			</div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-data-waitings" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>Antrian</th>
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
<script type="text/javascript">
//<![CDATA[
(function( $ ){
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
							
					$("#reset_w").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_w.reload_table , 600 ); 
					});
					
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
							searching: true,
							info: true,
							responsive: true,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/polies/data_waiting/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_w").val();	
										params.date_till = $("#date_till_t").val();	

										params.NRM = $("#NRM_w").val() || "";
										params.Nama = $("#Nama_w").val() || "";
										params.DokterID = $("#DokterID_w").val() || "";	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoAntri", 
										className: "text-center",
										name: "a.NoAntri",
										width: "50px",
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
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/create") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-medkit\"></i> Periksa</a>";
												<?php /*?>	buttons += "<a href=\"<?php echo base_url("data-waitings/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */?>
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
				search_datatable_w.init();
            	$( "#dt-data-waitings" ).DataTable_DataWaitings();
				
			});
	})( jQuery );
//]]>
</script>