<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<!-- MODAL -->
<?php if(count($collection_history) > 0 ): ?>
<div id="modalHistoryPasien" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DAFTAR PASIEN REGISTRASI 3 HARI YANG LALU</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="table-responsive">
						<table id="dt_pasien_history" class="table table-sm table-striped" width="100%">
							<thead>
								<tr>
									<th><?php echo 'No Reg' ?></th>
									<th><?php echo 'NRM' ?></th>
									<th><?php echo 'Nama Pasien' ?></th>
									<th><?php echo 'Telepon' ?></th>
									<th><?php echo 'Diagnosa' ?></th>
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

<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
	$("#modalHistoryPasien").modal('show');
});
//<![CDATA[
(function( $ ){
			//LIST PASIEN 3 HARI YANG LALU
			$.fn.extend({
				dt_pasien_history: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: true,
								ordering: true,
								searching: true,
								info: true,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection_history)):?>
								data: <?php print_r(json_encode(@$collection_history, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ data: "NoReg", className: "" },
										{ 
											data: "NRM", className: "text-center", 
											render: function( val ){
												return val
											}
										},
										{ 
											data: "NamaPasien",
											render: function( val ) {
												return val
											}
										},
										{ data: "NoTelp", className: "" },
                                        { 
											className: 'details-control',
											orderable: false,
											searchable: false,
											data: null,
											width: '15%',
											defaultContent: ''
										},	
									],

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
									_details.load( "<?php echo base_url("registrations/patient_diagnosa_details") ?>", {"NoReg": _dt.NoReg}, function( response, status, xhr ){
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

							
						$( "#dt_pasien_history_length select, #dt_pasien_history_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
				$( "#dt_pasien_history" ).dt_pasien_history();
				
			});
	})( jQuery );
//]]>
</script>