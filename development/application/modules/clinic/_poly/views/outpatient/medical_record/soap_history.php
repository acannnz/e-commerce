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
            <h4 class="modal-title">Riwayat Catatan SOAP</h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="dt-soap-history" class="table table-bordered" width="100%">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Dokter</th>
									<th>Subjektif</th>
									<th>Objektif</th>
									<th>Assesmen</th>
									<th>Perencanaan</th>
									<th>Riwayat OBGYN</th>
									<th>Vital Signs</th>
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
											data: "CreatedAt", 
											className: "text-center", 
											width: '10%',
											render: function(val){
												return moment(val).format("DD, MMM YYYY");
											}
										},
										{ data: "NamaDokter", width: '15%'},
										{ 
											data: "Subjective", 
											width: '15%',
											/*render: function(v){
												return "<pre>"+ v +"</pre>";
											}*/
										},
										{ 
											data: "Objective", 
											width: '15%',
											/*render: function(v){
												return "<pre>"+ v +"</pre>";
											}*/
										},
										{ 
											data: "Assessment", 
											width: '15%',
											/*render: function(v){
												return "<pre>"+ v +"</pre>";
											}*/
										},
										{ 
											data: "Plan", 
											width: '15%',
											/*render: function(v){
												return "<pre>"+ v +"</pre>";
											}*/
										},
										{
											data: "riwayat",
											width: '15%'
										},
										{ 
											data: "vital_signs", 
											width: '15%'
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