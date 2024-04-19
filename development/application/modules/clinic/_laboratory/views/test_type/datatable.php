<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">List Data Jenis Test</h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("{$nameroutes}/create") ?>"  title="<?php echo lang('buttons:create') ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <b><?php echo lang('buttons:create') ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<table id="dt-test-type" class="table table-hover" width="100%">
				<thead>
					<tr>
						<th>Test ID</th>
						<th>Kategori Test</th>
						<th>Nama Test</th>
						<th>Satuan</th>
						<th>ACN</th>
						<th>HCN</th>
						<!--<th>Harga</th>
						<th>Harga HC</th>
						<th>Harga IKS</th>-->
						<th></th>
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
		$.fn.extend({
				DataTable_Test_Type: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'DESC']],
							searching: true,
							info: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "TestID",
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "KategoriTestNama", 
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ data: "NamaTest"},
									{ data: "Satuan"},
									{ data: "ACN"},
									{ data: "HCN"},
									/*{ data: "Harga"},
									{ data: "Harga_HC"},
									{ data: "Harga_IKS"},*/
									{ 
										data: "TestID",
										className: "",
										orderable: false,
										width: "130px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("laboratory/test-type/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
													buttons += "<a href=\"<?php echo base_url("laboratory/test-type/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-test-type_length select, #dt-test-type_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-test-type" ).DataTable_Test_Type();
			});
	})( jQuery );
//]]>
</script>