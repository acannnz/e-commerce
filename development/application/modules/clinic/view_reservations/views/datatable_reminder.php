<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info">List Data Reservasi Dalam 3 Hari</h3>
            <p>data reservasi pasien</p>
        </div>
        <div class="col-md-6">
            <a href="<?php echo base_url("reservations/create") ?>"<?php /*?> data-toggle="ajax-modal"<?php */?> title="<?php echo lang('buttons:create_registration') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:create_registration') ?></span></a>
        </div>
	</div>
</div>
<div class="table-responsive">
    <table id="dt-reservations" class="table" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('reservations:reservations_number_label') ?></th>
                <th><?php echo lang('reservations:date_label') ?></th>
                <th><?php echo lang('reservations:time_label') ?></th>
                <th><?php echo lang('reservations:mr_number_label') ?></th>
                <th><?php echo lang('reservations:name_label') ?></th>
                <th><?php echo lang('reservations:address_label') ?></th>
                <th><?php echo lang('reservations:phone_label') ?></th>
                <th><?php echo lang('reservations:section_label') ?></th>
                <th><?php echo lang('reservations:doctor_name_label') ?></th>
                <th><?php echo lang('reservations:day_label') ?></th>
                <th><?php echo lang('reservations:queue_label') ?></th>
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
				DataTable_reservations: function(){
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
							dom: "<'row'<'col-md-5'l><'col-md-7'f>r>t<'row'<'col-md-5'i><'col-md-7'p>>",
							ajax: {
									url: "<?php echo base_url("reservations/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.reminder = 1;	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoReservasi", 
										//className: "text-right",
										width: "180px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tanggal", 
										width: "120px",
										format: "LLL",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "Jam", 
										className: "text-center",
										width: "40px",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ className: 'NRM',
										orderable: false,
										searchable: false,
										data: null,
										width: "32px",
										defaultContent: ''
									},									
									{ 
										data: "Nama", 
										className: "text-right",
										width: "90px",
										render: function ( val, type, row ){
												return "<strong class=\"text-success\">" + val + "</strong>"
											}
									},
									{ data: "Alamat", width: null },
									{ data: "Phone", width: null },
									{ data: "SectionName", width: null },
									{ data: "Nama_Supplier", width: null },
									{ data: "UntukHari", width: null },
									{ data: "NoUrut", width: null },
									<?php /*?>{ 
										data: "personal_address", 
										//width: "20%",
										render: function ( val, type, row ){
												var rr_address = new Array();
												rr_address.push( row.personal_address );
												if( row.area_name != "" ){ rr_address.push( row.area_name ); }
												if( row.district_name != "" ){ rr_address.push( row.district_name ); }
												if( row.county_name != "" ){ rr_address.push( row.county_name ); }
												if( row.province_name != "" ){ rr_address.push( row.province_name ); }
												if( row.country_name != "" ){ rr_address.push( row.country_name ); }
												
												return ( rr_address.length ) ? rr_address.join( ", " ) : "n/a"
											}
									},<?php */?>
									<?php /*?>{ 
										data: "phone_number", 
										render: function ( val, type, row ){
												return ( val ) ? "<a href=\"tel:" + val + "\">" + val + "</a>" : "n/a"
											}
									},<?php */?>
									{ 
										data: "NoReservasi",
										className: "",
										orderable: false,
										width: "130px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("reservations/edit") ?>/" + val + "\" data-toggle=\"form-ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
													buttons += "<a href=\"<?php echo base_url("reservations/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";
												buttons += "</div>";
												
												return buttons
											}
									}
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
									_details.load( "<?php echo base_url("reservations/patient_details") ?>", {"reg_num": _dt.registration_number}, function( response, status, xhr ){
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
						
					$( "#dt-reservations_length select, #dt-reservations_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-reservations" ).DataTable_reservations();

				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-reservations" ).DataTable().ajax.reload();
				});
			});
	})( jQuery );
//]]>
</script>