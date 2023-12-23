<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(); ?>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('general_payment:date_from_label') ?></label>
            <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="date_from_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d")?>" />
            	<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
                <input type="text" id="date_till_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d") ?>" />
            </div>
        </div>
	</div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('general_payment:patient_label') ?></label>
            <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
                <input type="text" id="NRM_c" class="form-control searchable_c mask_nrm" placeholder="<?php echo lang('general_payment:nrm_label') ?>" />
				<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
                <input type="text" id="Nama_c" class="form-control searchable_c" placeholder="<?php echo lang('general_payment:patient_name_label') ?>" />
            </div>
        </div>
    </div>
    <div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('general_payment:section_label') ?></label>
			<select id="SectionPerawatanID_c" class="form-control searchable_option_c">
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
			<select id="DokterID_c" class="form-control searchable_option_c">
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
    <table id="dt-cashier-close" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>Tgl Bayar</th>
                <th>NoBukti</th>
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
					$( "#dt-cashier-close" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_cashier_c: function(){
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
									url: "<?php echo base_url("cashier/general-payments/data_close/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_c").val();	
										params.date_till = $("#date_till_c").val();	
										
										params.NRM = $("#NRM_c").val() || "";
										params.Nama = $("#Nama_c").val() || "";
										
										params.SectionPerawatanID = $("#SectionPerawatanID_c").val() || "";
										params.DokterID = $("#DokterID_c").val() || "";
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "Jam", 
										className: "text-center",
										width: "150px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},

									{ data: "NoBukti", className: "text-center", width: "130px" },
									{ data: "NoReg", className: "text-center", width: "130px" },
									{ data: "NRM", width: null },
									{ data: "NamaPasien_Reg", width: null },
									{ data: "JenisKelamin", width: null, render:function(val){if(val == 'M'){return  "Laki-Laki"}else{return "Perempuan"}} },
									{ data: "Alamat"},
									{ data: "JenisKerjasama"},
									{ 
										data: "NoBukti",
										width: "100px",
										className: "text-center",
										render: function ( val, type, row ){
												if ( row.Batal == 1 )
												{
													return "<a href=\"javascript:;\" title=\"<?php echo lang("buttons:cancel") ?>\" class=\"btn btn-danger btn-xs\"><b><?php echo lang("buttons:cancel") ?></b></a>"; 
												} else if ( row.Audit == 1 )
												{
													return "<a href=\"javascript:;\" title=\"Audit\" class=\"btn btn-info btn-xs\"><b>Audit</b></a>"; 
												} else if ( row.Closing == 1 )
												{
													return "<a href=\"javascript:;\" title=\"Closing\" class=\"btn btn-success btn-xs\"><b>Closing</b></a>"; 
												}
												
												return ""
												
											}
									},
									{ 
										data: "NoBukti",
										className: "text-center",
										orderable: false,
										width: "80px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("cashier/general-payment/edit") ?>/" + val + "\" title=\"Lihat Detail\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-eye\"></i> Lihat Detail</a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-cashier-close_length select, #dt-cashier-close_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-cashier-close" ).DataTable_cashier_c();
				search_datatable_c.init();
				
			});
	})( jQuery );
//]]>
</script>