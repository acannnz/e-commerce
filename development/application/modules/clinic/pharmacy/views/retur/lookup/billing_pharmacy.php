<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Billing Farmasi</h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( _response ){
					var _response = JSON.parse(_response)
                    if( _response ){
						console.log(_response)
						try{							
							$('#NoBukti').val( _response.NoBukti );
							$('#NoReg').html( _response.NoReg );
							$('#NRM').html( _response.NRM );
							$('#NamaPasien').html( _response.Keterangan );
							$('#SectionAsal').html( _response.SectionName );
							$('#SectionID').val( _response.SectionID );

							$("#dt_returs").DataTable().clear();
							$.get('<?php echo base_url('pharmacy/retur/get_pharmacy_detail') ?>', {NoBukti: _response.NoBukti}, function( collection ){
								$.each( collection, function(i, v){
									if(v.Barang_ID == 0) return true;
									// console.log(v);
									$("#dt_returs").DataTable().row.add({
											Kode_Barang : v.Kode_Barang,
											Nama_Barang : v.Nama_Barang,
											Qty_Pesan : v.JmlObat - v.JmlRetur, 
											Qty_Terpakai : v.JmlPemakaian - v.JmlRetur, 
											Qty_Retur : 0, 
											HargaPersediaan : v.HargaPersediaan, 
											Barang_ID : v.Barang_ID, 
											Satuan : v.Satuan, 
											NoBill : $('#NoBukti').val(),
											JenisBarangID : v.JenisBarangID || 0,
											PemakaianOnly : 0,
											Harga : v.Harga,
											Disc : null,
											batchs : v.batchs
										}).draw();
								});
							});
							
							$('#lookup-ajax-modal').remove();
							$("body").removeClass("modal-open").removeAttr("style");
						} catch(e){console.log(e)}
                    }
			}
			//]]></script>
            <div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-search"></i></span>
							<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
							<div class="input-group-btn">
								<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-billing-pharmacy" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo lang('retur:evidence_number_label') ?></th>
							<th><?php echo lang('retur:no_reg_label') ?></th>    
							<th><?php echo lang('retur:nrm_label') ?></th>
							<th><?php echo lang('retur:patient_label') ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
        </div>
		<div class="modal-footer"></div>
    </div>
	<!-- /.modal-content -->
	
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				DT_Lookup_Schedule: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							//lengthMenu: [ 10, 20 ],
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'desc']],
							searching: true,
							info: true,
							//responsive: true,
							ajax: {
									url: "<?php echo base_url("pharmacy/retur/pharmacy_collection") ?>",
									type: "POST",
									data: function( params ){
									}
								},
							columns: [
									{ 
										data: "NoBukti",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = row;
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												return "<a id=\"apply\" href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}'title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs apply\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ 
										data: "NoBukti",     
										name: "NoBukti",     
										width: "160px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "NoReg",     
										name: "NoReg",     
										width: "160px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + (val || '') + "</b>"
											}
									},
									{ 
										data: "NRM",   
										name: "NRM",       
										width: "120px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + (val || '') + "</b>"
											}
									},
									{ data: "Keterangan"},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-billing-pharmacy" ).DT_Lookup_Schedule();
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				var words = $( "input[type=\"search\"]#lookupbox_search_words" ).val() || "";
				
				_datatable.DataTable().search( words );					
				_datatable.DataTable().draw();
			});
			
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keypress", function(e){
				if ( (e.which || e.keyCode) == 13 ) {
					e.preventDefault();
					return false
				}
			});	
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup paste change", function(e){
				e.preventDefault();
					
				var words = $.trim( $( this ).val() || "" );
				
				_datatable.DataTable().search( words );
				_datatable.DataTable().draw();
			});
		
	})( jQuery );
//]]></script>


