<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
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
    <table id="dt-lookup-reservations" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('poly:registration_number_label') ?></th>
                <th><?php echo lang('poly:name_label') ?></th>
                <th><?php echo lang('poly:for_date_label') ?></th>                
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
				DT_Lookup_Reservations: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 10, 20 ],
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("registrations/lookup_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							columns: [
									{ 
										data: "id",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												//var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href=\"javascript:try{lookupbox_row_selected('" + row.registration_number + "')}catch(e){}\" title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ 
										data: "registration_number",     
										width: "160px",
										orderable: true,
										searchable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "personal_name", orderable: true, searchable: true},
									{ data: "state", 
										width: "90px", 
										className: "text-right",
										orderable: true, 
										searchable: false,
										render: function ( val, type, row ){
												if( 3 == val ){ return "<span class=\"label label-info\"><?php echo lang("registrations:completed_label") ?></span>"
												} else if( 2 == val ){ return "<span class=\"label label-success\"><?php echo lang("registrations:progress_label") ?></span>"
												} else if( 1 == val ){ return "<span class=\"label label-warning\"><?php echo lang("registrations:registered_label") ?></span>"
												} else { return "<span class=\"label\"><?php echo lang("registrations:cancelled_label") ?></span>" }
											}
									}									
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-reservations" ).DT_Lookup_Reservations();
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				var words = $( "input[type=\"search\"]#lookupbox_search_words" ).val() || "";
				if( words ){
					_datatable.DataTable().search( words );
					_datatable.DataTable().draw();
				}
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
				if( words != "" ){
					_datatable.DataTable().search( words );
					_datatable.DataTable().draw();
				}
			});
		
	})( jQuery );
//]]></script>

