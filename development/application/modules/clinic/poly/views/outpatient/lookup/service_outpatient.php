<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Jasa </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {
												
						var dt = new Date();
						var time = "<?php echo date("Y-m-d")?> "+ dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
						
						var add_data = {
								"JasaID" : _response.JasaID,
								"JasaName" : _response.JasaName,
								"Qty" :  1,
								"Tarif" : _response.Harga_Baru,
								"DokterID" : $("#DokterID").val() || "",
								"Nama_Supplier" : $("#DocterName").val() || "",
								"User_id" : _response.user_id,
								"Jam" : time,
								"HargaOrig" : _response.Harga_Baru,
								"ListHargaID" : _response.ListHargaID,
								//"Disc" : ''
							};
						
						$("#dt_services").DataTable().row.add( add_data ).draw();
						
						$( '#lookup-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="checkbox">
							<input type="checkbox" id="show_all" name="f[show_all]" value="1" class="" checked><label for="show_all">Tampil Semua</label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
							<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
							<div class="input-group-btn">
								<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-common-services" class="table table-sm table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo 'Kode' ?></th>
							<th><?php echo 'Nama' ?></th>
							<th><?php echo 'Tarif' ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>        
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				DT_Lookup_CommonServices: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 30, 60 ],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("common/services/datatable_charge_collection") ?>",
									type: "POST",
									data: function( params ){
										params.SectionID = $("#SectionID").val();
										params.JenisKerjasamaID = $("#JenisKerjasamaID").val();
										params.NoAnggota = $('#NoAnggota').val() || '';
										params.PasienKTP = $("#PasienKTP").val();
										params.show_all = $("#show_all:checked").val() || 0;
										params.Lokasi = 'RJ';
										params.KelasID = $('#KdKelas').val();
										params.DokterID = $('#form_poly').find('#DokterID').val();
										
									}
								},
							columns: [
									{ 
										data: "JasaID",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = row;
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ 
										data: "JasaID",     
										width: "70px",
										orderable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "JasaName", orderable: true},
									{ 
										data: "Harga_Baru",
										orderable: true,
										render: function(val){
												return mask_number.currency_add(val);
											}
									},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-common-services" ).DT_Lookup_CommonServices();
		
		var timer = 0;
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 400); 
				
			});
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keypress", function(e){
				if ( (e.which || e.keyCode) == 13 ) {
					e.preventDefault();
					return false
				}
			});	
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup paste change", function(e){
				e.preventDefault();

				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 400); 
				
			});
		
		function searchWord(){
			var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
			_datatable.DataTable().search( words );
			_datatable.DataTable().draw(true);	
		}
		
		$("#show_all").on("change",function(e){
			_datatable.DataTable().ajax.reload();
		});
				
	})( jQuery );
//]]></script>

