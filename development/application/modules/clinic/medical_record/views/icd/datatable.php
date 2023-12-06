<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo 'Daftar ICD' ?></h3>
			<ul class="panel-btn">
				<li><a href="<?php echo base_url("medical_record/icd/create") ?>" class="btn btn-info" title="<?php echo 'Tambah ICD' ?>"><b><i class="fa fa-plus"></i> <?php echo 'Tambah Baru' ?></b></a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<table id="dt-common-icd" class="table" width="100%">
							<thead>
								<tr>
									<th><?php echo 'Kode ICD' ?></th>
									<th><?php echo 'Deskripsi' ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
						
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_CommonICD: function(){
						var _this = this;
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							lengthMenu: [ 25, 50, 100 ],
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "KodeICD", 
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Descriptions", 
									},
									{ 
										data: "KodeICD",
										className: "text-center",
										orderable: false,
										width: "120px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group\" role=\"group\">";
													buttons = "<a href=\"<?php echo base_url("{$nameroutes}/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-xs btn-primary\"><i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?></a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-common-icd_length select, #dt-common-icd_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-icd" ).DataTable_CommonICD();
			});
	})( jQuery );
//]]>
</script>