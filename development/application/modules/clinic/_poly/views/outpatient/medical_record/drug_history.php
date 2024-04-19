<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style type="text/css">
	#dt-soap-history tbody td {
		vertical-align: top;
		font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
		line-height: 1.42857143;
	}
	
	#dt-soap-history tbody td pre{	
		background: none !important;
		border: none;
		padding: 0;
		margin: 0;
		border-radius: 0;
	}
</style>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Riwayat Resep Pasien</h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="dt-soap-history" class="table table-bordered" width="100%">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>No Resep</th>
									<th>No Registrasi</th>
									<th>Dokter</th>
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
        <div class="modal-footer">
        	
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		$.fn.extend({
				dtSOAPHistory: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: true,
								ordering: true,
								order: [[0, 'desc']],
								searching: true,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "Tanggal", 
											className: "text-center", 
											width: '10%',
											render: function(val){
												return moment(val).format("DD, MMM YYYY");
											}
										},
										{ 
											data: "NoResep", 
											className: "text-center", 
											width: '10%',
											render: function(val){
												return val;
											}
										},

										{ 
											data: "NoReg", 
											width: '15%',
											/*render: function(v){
												return "<pre>"+ v +"</pre>";
											}*/
										},
										
										{ data: "NamaDokter", width: '15%'},
										{ 
											className: 'details-control',
											orderable: false,
											searchable: false,
											data: null,
											width: '15%',
											defaultContent: ''
										},	
									],
								createdRow: function ( row, data, index ){
										/*$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});*/
									}
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
									_details.load( "<?php echo base_url("poly/outpatients/medical_record/drug_history_details") ?>", {"NoBukti": _dt.NoBukti}, function( response, status, xhr ){
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

							
						$( "#dt-soap-history_length select, #dt-soap-history_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt-soap-history" ).dtSOAPHistory();
				
			});

	})( jQuery );
//]]>
</script>