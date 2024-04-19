<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
	.input-group .form-control {
    	padding-bottom: 11px!important;
}
.btn-default {
		padding: 9px 16px 8px 16px!important;	
}
</style>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="search" id="lookupbox_search_words" value="" placeholder="Ketik Pencarian..." class="form-control">
                <!-- <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div> -->
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-common-item" class="table table-sm table-bordered table-hover" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('label:item_code') ?></th>
                <th><?php echo lang('label:item_name') ?></th>
                <th><?php echo lang('label:item_stock')?></th>
                <th><?php echo lang('label:item_unit')?></th>
                <th><?php echo lang('label:item_category')?></th>                
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php echo form_close() ?>
<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				DT_Lookup_CommonItem: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: false,
							searching: true,
							info: true,
							responsive: true,
							scrollCollapse: true,
								ajax: {
									url: "<?php echo base_url("inventory/references/item_goods_receipt/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
											if ( $("#Lokasi_ID").size() > 0)
											{
												params.Lokasi_ID = $("#Lokasi_ID").val() || "";
											}
											
											if ( $("#SectionTujuan").size() > 0)
											{
												params.SectionID = $("#SectionTujuan").val() || "";
											}
											
											if ( $("#location_to").size() > 0)
											{
												params.location_to = $("#location_to").val() || "";
											}
											
										}
								},
							columns: [
									{ 
										data: "Barang_Id",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = row;
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{data: "Kode_Barang"},
									{ 
										data: "Nama_Barang",     
										orderable: true,
										searchable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Qty_Stok", orderable: true, searchable: true},	
									{ data: "Kode_Satuan", orderable: true, searchable: true},	
									{ data: "Nama_Kategori", orderable: true, searchable: true}	
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-common-item" ).DT_Lookup_CommonItem();
		
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
		
	})( jQuery );
//]]></script>

