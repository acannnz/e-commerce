<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("id" => "dt_open", "disabled" => "disabled") ) ?>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label"><?php echo 'Tanggal Dari' ?></label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				<input type="text" id="date_from_o" class="form-control searchable_o datepicker" value="<?php echo date("Y-m-d")?>" />
				<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
				<input type="text" id="date_till_o" class="form-control searchable_o datepicker" value="<?php echo date("Y-m-d") ?>" />
			</div>
        </div>
	</div>
</div>
<div class="table-responsive">
    <table id="dt-drug-payment-open" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>No Bukti</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Dokter</th>
                <th>Section</th>
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
	var _form_open = $('#dt_open');
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
					
				},
			reload_table : function(){
					$( "#dt-drug-payment-open" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_DrugPaymenOpens: function(){
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
									url: "<?php echo base_url("pharmacy/drug-payments/data_open/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from_o").val();	
										params.date_till = $("#date_till_o").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										width: "120px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Jam", 
										width: "70px",
										className: "text-center",
										render: function ( val, type, row ){
												return val.substr(0,19)
											}
									},
									{ 
										data: "Keterangan", 
										width: "250px",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},							
									{ data: "JenisKerjasama", width: null },
									{ data: "Nama_Supplier", width: null },
									{ data: "SectionName", width: null },
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("pharmacy/drug-payment/pay") ?>/" + val + "\" title=\"Bayar Obat\" class=\"btn btn-danger btn-xs\"><b><i class=\"fa fa-shopping-cart\"></i> Bayar</b></a>";
													buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-drug-payment-open_length select, #dt-drug-payment-open_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-drug-payment-open" ).DataTable_DrugPaymenOpens();
				search_datatable_o.init();
			});
	})( jQuery );
//]]>
</script>