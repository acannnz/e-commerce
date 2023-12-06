<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="table-responsive">
    <table id="dt-test-type" class="table" width="100%">
        <thead>
            <tr>
                <th>Kelompok Umur</th>
                <th>TypeKelahiran</th>
                <th>Gender</th>
                <th>OperatorUmur1</th>
                <th>Umur(Th)1</th>
                <th>Umur(Bln)1</th>
                <th>Umur(Hari)1</th>
                <th>OperatorUmur2</th>
                <th>Umur(Th)2</th>
                <th>Umur(Bln)2</th>
                <th>Umur(Hari)2</th>
                <th>Keterangan</th>
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
				DataTable_Test_Type: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: false,
							ordering: true,
							order: [[1, 'DESC']],
							searching: false,
							info: true,
							responsive: true,
							dom: "<'row'<'col-md-5'l><'col-md-7'f>r>t<'row'<'col-md-5'i><'col-md-7'p>>",
							ajax: {
									url: "<?php echo base_url("laboratory/test_type/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "a", 
										//className: "text-right",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},
									{ data: "a"},

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
										data: "TestID",
										className: "",
										orderable: false,
										width: "130px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("laboratory/test_type/edit") ?>/" + val + "\" data-toggle=\"form-ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
													buttons += "<a href=\"<?php echo base_url("laboratory/test_type/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";
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
						
					$( "#dt-test-type_length select, #dt-test-type_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-test-type" ).DataTable_Test_Type();

				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-test-type" ).DataTable().ajax.reload();
				});
			});
	})( jQuery );
//]]>
</script>