<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:date_from_label') ?></label>
            <div class="input-group">
				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" id="date_from_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d")?>" />
				<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
                <input type="text" id="date_till_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d") ?>" />
            </div>
        </div>
	</div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:patient_label') ?></label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-id-card-o"></i></div>
				<input type="text" id="NRM_c" class="form-control searchable_c mask_nrm"  placeholder="<?php echo lang('pharmacy:mr_number_label') ?>"/>
				<div class="input-group-addon"><i class="fa fa-wheelchair"></i></div>
				<input type="text" id="Nama_c" class="form-control searchable_c" placeholder="<?php echo lang('pharmacy:name_label') ?>"/>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo lang('pharmacy:doctor_label') ?></label>
			<select id="DokterID_c" class="form-control searchable_option_c">
				<option value=""><?php echo lang("global:select-none")?></option>
				<?php if($option_doctor): foreach($option_doctor as $row): ?>
				<option value="<?php echo $row->Kode_Supplier ?>"><?php echo $row->Nama_Supplier ?></option>
				<?php endforeach;endif; ?>
			</select>
        </div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Tampilkan</label>
			<div class="row">
				<div class="col-md-4">
					<div class="checkbox">
						<input type="checkbox" id="show_paid" value="1" class="check-searchable" >
						<label for="show_paid">Sudah Bayar</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<input type="checkbox" id="show_return" value="1" class="check-searchable" >
						<label for="show_return">Retur Data</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<input type="checkbox" id="include_jasa" value="1" class="check-searchable" >
						<label for="include_jasa">Include Jasa</label>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-data-checkups" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>NoBukti</th>
                <th>NoRegistrasi</th>
                <th>Tanggal</th>
                <th>N.R.M</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
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
	
					$( ".searchable_option_c, .check-searchable" ).on("change", function(e){
		
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
							searching: false,
							info: true,
							responsive: true,
							lengthChange: false,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("pharmacy/pharmacies/data_close/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_c").val();	
										params.date_till = $("#date_till_c").val();	

										params.NRM = $("#NRM_c").val() || "";
										params.Nama = $("#Nama_c").val() || "";
										params.DokterID = $("#DokterID_c").val() || "";
										
										params.show_paid = $("#show_paid:checked").val() || 0;	
										params.show_return = $("#show_return:checked").val() || 0;	
										params.include_jasa = $("#include_jasa:checked").val() || 0;	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										name: "a.NoBukti",
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
												return (val) 
													? "<strong class=\"text-success\">" + val + "</strong>"
													: "n/a"
											}
									},
									{ 
										data: "NamaPasien",  
										name: "a.Keterangan",
										render: function(val, type, row){
											return val || row.Keterangan;
										}
									},
									{ data: "Nama_Supplier", width: null },
									{ 
										data: "ObatBebas", 
										className: "text-center",
										width: "90px",
										name: "a.ObatBebas",
										render: function ( val, type, row ){
											if ( val == 1)
											{
												return "Bebas"
											} else if ( row.IncludeJasa == 1)
											{
												return "Include Jasa"
											}
											
											return "Rawat Jalan"
										}
									},
									{ 
										data: "ClosePayment", 
										class: "text-center", 
										render: function(val, type, row){
											if(row.ClosePayment == 1){
												return '<span class="btn btn-success btn-xs">Dibayar</span>'
											}
											if(row.Retur == 1){
												return '<span class="btn btn-danger btn-xs">Retur</span>'
											}
											if ( row.IncludeJasa == 1)
											{
												return ""
											}
											return '<span class="btn btn-default btn-xs">Belum Bayar</span>'
										}
									},
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("pharmacy/selling_view") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-primary btn-xs\"><b><i class=\"fa fa-eye\"></i> Lihat</b></a>";
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
            	$( "#dt-data-checkups" ).DataTable_DataCheckups();
				search_datatable_c.init();

			});
	})( jQuery );
//]]>
</script>