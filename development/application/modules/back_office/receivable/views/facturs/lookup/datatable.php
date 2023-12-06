<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus-square-o"></i></span>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-facturs" class="table table-bordered table-hover table-sm" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('facturs:date_label') ?></th>
                <th><?php echo lang('facturs:factur_number_label') ?></th>
                <th><?php echo lang('facturs:description_label') ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <button type="button" id="select-factur" class="btn btn-primary btn-block"><b><i class="fa fa-arrow-circle-down"></i> <?php echo lang('buttons:select') ?></b></button>
        </div>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				DT_Lookup_CommonAccount: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 30, 60 ],
							processing: true,
							serverSide: true,								
							paginate: false,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							scrollCollapse: true,
							scrollY: "400px",
							ajax: {
									url: "<?php echo base_url("receivable/factur/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
											params.Customer_ID = $("#Customer_ID").val();
										}
								},
							columns: [
									{ 
										data: "No_Faktur",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "No_Faktur",     
										width: "150px",
										className: "text-center",
										orderable: true,
										searchable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Tgl_Faktur", orderable: true, searchable: true},									
									{ 
										data: "Keterangan", 
										orderable: true, 
										searchable: true,
										render: function( val ){
												return val.substr(0,45);
											}
									}									
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-facturs" ).DT_Lookup_CommonAccount();
		
		$('#dt-lookup-facturs tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('danger');
		} );
		
		$('#select-all').on("click", function (e) {
			$('#dt-lookup-facturs tbody tr').toggleClass('danger');
		});
		
		$('#select-factur').on("click", function (e) {
			e.preventDefault();
			try{
				var data_selected = _datatable.DataTable().rows('.danger').data();
				var data_table = [];
				
				data_selected.each(function (value, index) {						
					data_table[index] = value;
				});
				
				lookupbox_multiple_selected( data_table );
				
			} catch(e){console.log(e)}
		});
			
		
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

