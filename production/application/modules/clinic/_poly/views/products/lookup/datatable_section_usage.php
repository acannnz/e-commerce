<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
				<div class="input-group-btn">
                	<button type="button" id="select-all" class="btn btn-primary"><b><i class="fa fa-check"></i> Pilih Semua</b></button>                
                </div>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive form-group">
    <table id="dt-lookup-common-services" class="table table-sm table-bordered" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama </th>
                <th>Satuan</th>              
                <th>Stok</th>              
                <th>Kategori</th>              
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <button type="button" id="select-item" class="btn btn-primary btn-block"><b><i class="fa fa-arrow-circle-down"></i> <?php echo lang('buttons:select') ?></b></button>
        </div>
    </div>
</div>
<?php echo form_close() ?>
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
							info: false,
							responsive: true,
							/*scrollCollapse: true,
							scrollY: "400px",*/
							ajax: {
									url: "<?php echo base_url("poly/products/lookup_item_usage_collection") ?>",
									type: "POST",
									data: function( params ){
										params.SectionID = $("#SectionID").val();
									}
								},
							columns: [
									{ 
										data: "Kode_Barang", 
										width: "10px",
										className: "text-center",
										orderable: false,
									},
									{
										data: "Kode_Barang",     
										width: "120px",
										name: "a.Barang_ID",
										className: "text-center",
										orderable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Nama_Barang", 
										orderable: true,
										name: "b.Nama_Barang",
									},
									{ 
										data: "Satuan",
										name: "c.Nama_Satuan",
									},									
									{ 
										data: "Qty_Stok",
										name: "a.Qty_Stok",
									},								
									{ 
										data: "Kategori",
										name: "d.Nama_Kategori",
									},									
								],
							fnRowCallback : function( nRow, aData, iDisplayIndex , iDisplayIndexFull ) {
									var index = iDisplayIndexFull + 1;
									$('td:eq(0)',nRow).html(index);
									return nRow;					
								},

						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-common-services" ).DT_Lookup_CommonServices();

		$('#dt-lookup-common-services tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('danger');
		} );
		
		$('#select-all').on("click", function (e) {
			$('#dt-lookup-common-services tbody tr').toggleClass('danger');
		});
		
		$('#select-item').on("click", function (e) {
			e.preventDefault();
			try{
				var data_selected = _datatable.DataTable().rows('.danger').data();
				var data_table = [];
				
				data_selected.each(function (value, index) {
					val = parseFloat(value.Harga_Jual.replace(/[^0-9\.-]+/g,""));
					var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
					value.Harga = mask;
					
					value.BarangID = value.Barang_ID;
					value.QtyStok = value.Qty_Stok;
					value.QtyPemakaian = "";
					value.Keterangan = "";
					data_table[index] = value;
				});

				lookupbox_row_selected(data_table);
				
			} catch(e){console.log(e)}
		});
			
		var timer = 0;
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 200); 
				
			});
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keypress", function(e){
				if ( (e.which || e.keyCode) == 13 ) {
					e.preventDefault();
					return false
				}
			});	
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup paste", function(e){
				e.preventDefault();

				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord,200); 
				
			});
		
		function searchWord(){
			var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
			_datatable.DataTable().search( words );
			_datatable.DataTable().draw(true);	
		}

		
	})( jQuery );
//]]></script>

