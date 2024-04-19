<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info">Daftar Jadwal Prakter Dokter</h3>
            <p>Jadwal Prakter Dokter Dikelola Disini</p>
        </div>
        <div class="col-md-6">
            <a href="<?php echo base_url("schedules/create") ?>" title="<?php echo lang('buttons:create_registration') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span>Buat Jadwal Praktek</span></a>
        </div>
	</div>
</div>
<div class="table-responsive">
    <table id="dt-schedules" class="table table-sm table-striped" width="100%">
        <thead>
            <tr>
                <th>DokterID</th>
                <th>Nama Dokter</th>
                <!--<th>Spesialis</th>-->
                <th>Section</th>
                <th>Senen</th>
                <th>Selasa</th>
                <th>Rabu</th>
                <th>Kamis</th>
                <th>Jumat</th>
                <th>Sabtu</th>
                <th>Minggu</th>
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
		$.fn.extend({
				DataTable_Registrations: function(){
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
									url: "<?php echo base_url("schedules/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "DokterID", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Nama_Supplier", 
									},
									/*{ 
										data: "SpesialisName", 
									},*/
									{ 
										data: "SectionName", 
									},
									{ 
										data: "Senen", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "Selasa", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "Rabu", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "Kamis", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "Jumat", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "Sabtu", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "Minggu", 
										className: "text-center",
										render: function (val)
										{
											if ( val )
											{
												return "Praktek";
											}
											
											return "Libur"
										}
									},
									{ 
										data: "DokterID", className: "text-center",
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("schedules/edit") ?>/" + row.DokterID + "/" +row.SectionID + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
											<?php /*?>	buttons += "<a href=\"<?php echo base_url("registrations/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */?>
											buttons += "</div>";
											
											return buttons
										}
									},
										
								]
						} );
						
					// Array to track the ids of the details displayed rows
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
									_details.load( "<?php echo base_url("schedules/patient_details") ?>", {"reg_num": _dt.registration_number}, function( response, status, xhr ){
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
						
					$( "#dt-schedules_length select, #dt-schedules_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-schedules" ).DataTable_Registrations();

				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-schedules" ).DataTable().ajax.reload();
				});
			});
	})( jQuery );
//]]>
</script>