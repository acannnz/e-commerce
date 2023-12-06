<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Daftar Retur Barang</h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("pharmacy/retur/create") ?>" class="btn btn-info" title="<?php echo lang('buttons:create') ?>"><b><i class="fa fa-plus"></i> Buat Retur Baru</a></b></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?php echo 'Tanggal Dari' ?></label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
						<input type="text" id="date_from" name="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
						<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
						<input type="text" id="date_till" name="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="dt-retur" class="table table-sm" width="100%">
				<thead>
					<tr>
						<th><?php echo lang("retur:retur_number_label")?></th>
						<th><?php echo lang("retur:evidence_number_label")?></th>
						<th><?php echo lang("retur:date_label")?></th>
						<th><?php echo lang("retur:no_reg_label")?></th>
						<th><?php echo lang("retur:nrm_label")?></th>
						<th><?php echo lang("retur:patient_label")?></th>
						<th><i class="fa fa-gears"></i></th>
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
		var _datatable;
		
		$.fn.extend({
				DataTableInit	: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'DESC']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("pharmacy/retur/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();
										params.date_till = $("#date_till").val();
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoRetur", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "NoBukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Jam", 
										className: "text-center",
										render: function( val ){
											return val.substr(0, 19);
										}
									},
									{ data: "NoReg", className: "text-center",},
									{ data: "NRM", className: "text-center",},
									{ data: "NamaPasien_Reg"},
									{ 
										data: "NoRetur", 
										className: "text-center",
										orderable: false,
										searchable: false,
										width: '100px',
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right text-center\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("pharmacy/retur/view") ?>/" + val + "\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-eye\"></i> <?php echo lang( "buttons:view" ) ?> </a>";
											buttons += "</div>";
											
											return buttons
										}
									},
										
								]
						} );
						
					$( "#dt-retur_length select, #dt-retur_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-retur" ).DataTableInit();
				
				$("#date_from, #date_till").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
					$( "#dt-retur" ).DataTable().ajax.reload();
				});

				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-retur" ).DataTable().ajax.reload();
				});
			});
	})( jQuery );
//]]>
</script>