<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="table-responsive">
    <table id="dt-lookup-merchan" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Nama Bank</th>
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
				DT_Lookup_Merchan: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 30 ],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("common/merchan/lookup_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							columns: [
									{ 
										data: "ID",
										className: "text-center",
										orderable: false,
										searchable: false,
										width: '100px',
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "ID",
										className: "text-center",
										orderable: true,
										searchable: true,
										width: '100px',
									},
									{ 
										data: "NamaBank",     
										orderable: true,
										searchable: true,
										render: function(val){
											return '<b>' + val + '</b>'
										}
									},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-merchan" ).DT_Lookup_Merchan();
		
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

