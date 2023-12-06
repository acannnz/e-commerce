<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:date_from_label') ?></label>
            <div class="col-md-3">
                <input type="text" id="date_from_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d")?>" />
            </div>
            <label class="col-md-3 control-label text-center"><?php echo lang('poly:date_till_label') ?></label>
            <div class="col-md-3">
                <input type="text" id="date_till_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d") ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:mr_number_label') ?></label>
            <div class="col-md-3">
                <input type="text" id="NRM_c" class="form-control searchable_c mask_nrm" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:name_label') ?></label>
            <div class="col-md-9">
                <input type="text" id="Nama_c" class="form-control searchable_c" />
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo lang('poly:doctor_label') ?></label>
            <div class="col-md-9">
            	<select id="DokterID_c" class="form-control searchable_option_c">
                	<option value=""><?php echo lang("global:select-none")?></option>
                	<?php foreach($option_doctor as $k => $v): ?>
                    <option value="<?php echo $k ?>" <?php echo ($k == $medics['doctor_id']) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
	            <button id="reset_c" type="reset" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset")?></b></button>
			</div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-data-checkups" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>NoBukti</th>
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
		var search_datatable_c = {
			init : function(){
					var timer = 0;
			
					$( ".searchable_c" ).on("keyup", function(e){
						e.preventDefault();
		
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable_c.reload_table , 600 ); 
						}
					});
	
					$( ".searchable_option_c" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_c.reload_table , 600 ); 
							
					});
					
					$("#date_from_c, #date_till_c").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_c.reload_table , 600 ); 
	
					});
							
					$("#reset_c").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable_c.reload_table , 600 ); 
					});
					
				},
			reload_table : function(){
					$( "#dt-data-checkups" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_DataCheckups: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("{$nameroutes}s/data_checkup/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_c").val();	
										params.date_till = $("#date_till_c").val();	

										params.NRM = $("#NRM_c").val() || "";
										params.Nama = $("#Nama_c").val() || "";
										params.DokterID = $("#DokterID_c").val() || "";
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										name: "f.NoUrut",
										width: "120px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "RegNo", 
										width: "120px",
										class: "text-center",
										name: "a.RegNo",
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
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/edit") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-medkit\"></i> Periksa</a>";
												<?php /*?>	buttons += "<a href=\"<?php echo base_url("data-checkups/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */?>
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-data-checkups_length select, #dt-data-checkups_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				search_datatable_c.init();
            	$( "#dt-data-checkups" ).DataTable_DataCheckups();

			});
	})( jQuery );
//]]>
</script>