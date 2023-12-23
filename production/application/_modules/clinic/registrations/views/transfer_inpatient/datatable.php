<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('registrations:transfer_inpatient_list_heading') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:date_from_label') ?></label>
					<div class="input-group">
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:mr_number_label') ?></label>
					<input type="text" id="NRM" class="form-control searchable" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:name_label') ?></label>
					<input type="text" id="Nama" class="form-control searchable" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="reset" type="reset" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset")?></b></button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-registrations" class="table table-sm table-striped" width="100%">
					<thead>
						<tr>
							<th><?php echo lang('registrations:registration_number_label') ?></th>
							<th><?php echo lang('registrations:date_label') ?></th>
							<th></th>
							<th><?php echo lang('registrations:mr_number_label') ?></th>
							<th><?php echo lang('registrations:name_label') ?></th>
							<th><?php echo lang('registrations:type_label') ?></th>
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
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var search_datatable = {
				init : function(){
						var timer = 0;
				
						$( ".searchable" ).on("keyup", function(e){
							e.preventDefault();
			
							var isWordCharacter = event.key.length === 1;
							var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
						
							if (isWordCharacter || isBackspaceOrDelete) {
								if (timer) {
									clearTimeout(timer);
								}
								timer = setTimeout( search_datatable.reload_table , 600 ); 
							}
									
						});
								
						$(".datepicker").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 
		
						});
						
						$(".btn-clear").on("click", function(){
							target = $(this).data("target");
							
							$(target).val("");
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 

						});
								
						$("#reset").on("click", function(){
							
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 
						});
						
					},
				reload_table : function(){
						$( "#dt-registrations" ).DataTable().ajax.reload();
					}
			};
			
		$.fn.extend({
				DataTable_Registrations: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: false,
							ordering: true,
							order: [[0, 'desc']],
							searching: false,
							info: true,
							lengthChange: false,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("registrations/transfer_inpatient/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
										
										params.NRM = $("#NRM").val() || "";
										params.Nama = $("#Nama").val() || "";
	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoReg", 
										name: "a.NoReg", 
										className: "text-center",
										width: "180px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "TglReg", 
										name: "TglReg", 
										width: "180px",
										className: "text-center",
										render: function ( val, type, row ){
												return row.TglReg +" "+ row.JamReg
											}
									},									
									{ 
										className: 'details-control',
										orderable: false,
										searchable: false,
										data: null,
										width: "32px",
										defaultContent: ''
									},									
									{ 
										data: "NRM", 
										name: "NRM", 
										width: "90px",
										render: function ( val, type, row ){
												return "<strong class=\"text-success\">" + val + "</strong>"
											}
									},
									{ 
										data: "NamaPasien", 
										name: "NamaPasien", 
										width: null 
									},
									{ 
										data: "JenisKerjasama", 
										name: "JenisKerjasama", 
									},
									{ 
										data: "NoReg",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("registrations/transfer_inpatient/create") ?>/" + val + "\" title=\"<?php echo lang( "buttons:transfer" ) ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-bed\"></i> <?php echo lang( "buttons:transfer" ) ?> </a>";
											buttons += "</div>";
											
											return buttons
										}
									}
								]
						} );
						
					var _detail_rows = [];
					
					_this.find( 'tbody' ).on( 'click', 'tr td.details-control', function(e){
							var _tr = $( this ).closest( 'tr' );
							var _rw = _datatable.row( _tr );
							
							var _dt = _rw.data();
							var _ids = $.inArray( _tr.attr( 'id' ), _detail_rows );
					 
							if( _rw.child.isShown() ){
								_tr.removeClass( 'details' );
								
								_rw.child.hide();
					 
								// Remove from the 'open' array
								_detail_rows.splice( _ids, 1 );
							} else {
								_tr.addClass( 'details' );
								
								if( _rw.child() == undefined ){
									var _details = $( "<div class=\"details-loader\"></div>" );
									_rw.child( _details ).show();
									_details.html( "<span class=\"text-loader\"><?php echo lang("global:ajax_loading") ?></span>" );
									_details.load( "<?php echo base_url("registrations/patient_details") ?>", {"NoReg": _dt.NoReg}, function( response, status, xhr ){
											$( window ).trigger( "resize" );
										});
								} else {
									_rw.child.show();
								}
								
								// Add to the 'open' array
								if( _ids === -1 ){
									_detail_rows.push( _tr.attr( 'id' ) );
								}
							}
							
							$( window ).trigger( "resize" );
						});
					
					// On each draw, loop over the `_detail_rows` array and show any child rows
					_datatable.on('draw', function (){
							$.each(_detail_rows, function ( i, id ){
									$( '#' + id + ' td.details-control').trigger( 'click' );
								});
						});
						
					$( "#dt-registrations_length select, #dt-registrations_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-registrations" ).DataTable_Registrations();

				search_datatable.init();
				
			});
	})( jQuery );
//]]>
</script>