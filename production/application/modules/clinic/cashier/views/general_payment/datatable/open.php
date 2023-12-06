<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(); ?>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label col-md-4"><?php echo lang('general_payment:date_from_label') ?></label>
			<label class="control-label">
				<div class="checkbox" style="margin:0">
					<input type="checkbox" id="show_onprocess" value="1" class="searchable_check_o"><label for="show_onprocess">Tampilkan sedang proses bayar</label>
				</div>
			</label>
            <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="date_from_o" class="form-control searchable_o datepicker" value="<?php echo date("Y-m-d")?>" />
				<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
                <input type="text" id="date_till_o" class="form-control searchable_o datepicker" value="<?php echo date("Y-m-d") ?>" />
            </div>
        </div>
	</div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('general_payment:patient_label') ?></label>
            <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
                <input type="text" id="NRM_o" class="form-control searchable_o mask_nrm" placeholder="<?php echo lang('general_payment:nrm_label') ?>" />
				<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
                <input type="text" id="Nama_o" class="form-control searchable_o" placeholder="<?php echo lang('general_payment:patient_name_label') ?>" />
            </div>
        </div>
    </div>
   	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('general_payment:section_label') ?></label>
			<select id="SectionPerawatanID_o" class="form-control searchable_option_o">
				<option value=""><?php echo lang("global:select-none")?></option>
				<?php if($option_section): foreach($option_section as $row): ?>
				<option value="<?php echo $row->SectionID?>"><?php echo $row->SectionName ?></option>
				<?php endforeach;endif; ?>
			</select>
		</div>
    </div>
   	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('general_payment:doctor_label') ?></label>
			<select id="DokterID_o" class="form-control searchable_option_o">
				<option value=""><?php echo lang("global:select-none")?></option>
				<?php foreach($option_doctor as $k => $v): ?>
				<option value="<?php echo $k ?>" <?php echo ($k == $this->session->userdata('doctor_id')) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
				<?php endforeach;?>
			</select>
		</div>
    </div>
</div>
<?php echo form_close() ?>
<div class="table-responsive">
    <table id="dt-cashier-open" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>Tgl Masuk</th>
                <th>NoReg</th>
                <th>NRM</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Tipe</th>
                <th>Status</th>
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
	
					$( ".searchable_option_o, .searchable_check_o" ).on("change", function(e){
		
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
					
				},
			reload_table : function(){
					$( "#dt-cashier-open" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_cashier_o: function(){
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
									url: "<?php echo base_url("cashier/general-payments/data_open/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_o").val();	
										params.date_till = $("#date_till_o").val();	
										
										params.show_onprocess = $("#show_onprocess").is(":checked") ? 1 : 0;
										
										params.NRM = $("#NRM_o").val() || "";
										params.Nama = $("#Nama_o").val() || "";
										params.Phone = $("#Phone_o").val() || "";

										params.SectionPerawatanID = $("#SectionPerawatanID_o").val() || "";
										params.DokterID = $("#DokterID_o").val() || "";
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "JamReg", 
										className: "text-center",
										width: "120px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},

									{ data: "NoReg", className: "text-center", width: "200px" },
									{ data: "NRM", width: null },
									{ data: "NamaPasien", width: null },
									{ data: "JenisKelamin", width: null, render:function(val){if(val == 'M'){return  "Laki-Laki"}else{return "Perempuan"}} },
									{ data: "Alamat"},
									{ data: "JenisKerjasama"},
									{ 
										data: "Status",
										className: "text-center",
										render: function ( val, type, row ){
											return '<b>'+ val +'</b>';
										}
									},
									{ 
										data: "NoReg",
										className: "text-center",
										orderable: false,
										width: "80px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("cashier/general-payment/pay") ?>/" + val + "\" title=\"Lihat Detail\" class=\"btn btn-success btn-xs\"> <i class=\"fa fa-dollar\"></i> Bayar</a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-cashier-open_length select, #dt-cashier-open_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-cashier-open" ).DataTable_cashier_o();
				search_datatable_o.init();
				
			});
	})( jQuery );
//]]>
</script>