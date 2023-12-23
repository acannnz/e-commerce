<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
            <div class="panel-body">
				<div class="row">
					<div class="col-md-6">
			        	<h3 class="subtitle"><?php echo lang('patients:list_heading') ?></h3>
					</div>
					<div class="col-md-6">
						<div class="input-group">
							<div class="input-group-btn">
								<a href="<?php echo base_url("common/patients/create") ?>" title="<?php echo lang('buttons:add_patient') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:add_patient') ?></span></a>
							</div>
						</div>
					</div>
				</div>		
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<table id="dt-common-patients" class="table" width="100%">
								<thead>
									<tr>
										<th><?php echo lang('patients:mr_number_label') ?></th>
										<th><?php echo lang('patients:name_label') ?></th>
										<th><?php echo lang('patients:phone_label') ?></th>
										<th><?php echo lang('patients:gender_label') ?></th>
										<th><?php echo lang('patients:birth_date_label') ?></th>
										<th><?php echo lang('patients:address_label') ?></th>
										<th><?php echo lang('patients:type_label') ?></th>
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
	</div
></div>
						
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_CommonPatients: function(){
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
									url: "<?php echo base_url("common/patients/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NRM", 
										name: "a.NRM", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "NamaPasien", 
										name: "b.NamaPasien", 
										width: "20%" 
									},
									{ 
										data: "Phone", 
										name: "b.Phone", 
										className: "", 
										render: function ( val, type, row ){
												return ( val ) ? "<a href=\"tel:" + val + "\"><i class=\"fa fa-phone\" class=\"text-success\"></i> " + val + "</a>" : "n/a"
											}
									},
									{ 
										data: "JenisKelamin",
										name: "b.JenisKelamin", 
									},
									{ 
										data: "TglLahir",
										name: "b.TglLahir", 
										render: function(val, type, row){
											return val ? val.substr(0, 11) : '';
										}
									},
									{ 
										data: "Alamat", 
										name: "b.Alamat", 
										width: "20%",
										render: function ( val, type, row ){
												return "<em>" + val + "</em>"
											}
									},
									{ 
										data: "JenisPasien",
										name: "b.JenisPasien", 
									},
									{ 
										data: "NRM",
										name: "a.NRM", 
										className: "text-center",
										orderable: false,
										width: "120px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group\" role=\"group\">";
													buttons = "<a href=\"<?php echo base_url("common/patients/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-xs btn-primary\"><i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?></a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-common-patients_length select, #dt-common-patients_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-patients" ).DataTable_CommonPatients();
				
				$("#view_member").on("change", function(e){
					$( "#dt-common-patients" ).DataTable().ajax.reload();
				});
				
			});
	})( jQuery );
//]]>
</script>