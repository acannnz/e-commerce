<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("id" => "dt_close", "disabled" => "disabled") ) ?>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label"><?php echo 'Tanggal Dari' ?></label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				<input type="text" id="date_from_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d")?>" />
				<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
				<input type="text" id="date_till_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d") ?>" />
			</div>
        </div>
	</div>
</div>

<div class="table-responsive">
    <table id="dt-drug-payment-close" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>No Bukti</th>
                <th>No Farmasi</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Dokter</th>
                <th>Section</th>
				<th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form_close = $('#dt_close');
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

					
				},
			reload_table : function(){
					$( "#dt-drug-payment-close" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_DrugPaymenCloses: function(){
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
									url: "<?php echo base_url("pharmacy/drug-payments/data_close/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_c").val();	
										params.date_till = $("#date_till_c").val();
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										name: "a.NoBukti",
										width: "120px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "NoBuktiFarmasi", 
										name: "a.NoBuktiFarmasi",
										width: "140px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>" || "-"
											}
									},									{ 
										data: "Jam", 
										name: "a.Jam", 
										width: "70px",
										className: "text-center",
										render: function ( val, type, row ){
												return val.substr(0,19)
											}
									},
									{ 
										data: "Keterangan", 
										name: "b.Keterangan", 
										width: "220px",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},							
									{ 
										data: "JenisKerjasama", 
										name: "c.JenisKerjasama", 
										width: null 
										},
									{ 
										data: "Nama_Supplier", 
										name: "d.Nama_Supplier", 
										width: null 
									},
									{ 
										data: "SectionName", 
										name: "e.SectionName", 
										width: null 
									},
									{ 
										data: "Batal", 
										name: "a.Batal", 
										class: "text-center",
										render: function(val){
											return val == 1 ? '<span class="btn btn-xs btn-danger"><b>Batal</b></span>' : '<span class="btn btn-xs btn-success"><b>Sudah Bayar</b></span>'
										}
									},
									{ 
										data: "NoBuktiFarmasi",
										className: "",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("pharmacy/drug-payment/pay") ?>/" + val + "\" title=\"Bayar Obat\" class=\"btn btn-primary btn-xs\"><b><i class=\"fa fa-eye\"></i> Lihat</b></a>";
													buttons += "</div>";
													
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-drug-payment-close_length select, #dt-drug-payment-close_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-drug-payment-close" ).DataTable_DrugPaymenCloses();
				search_datatable_c.init();
			});
	})( jQuery );
//]]>
</script>