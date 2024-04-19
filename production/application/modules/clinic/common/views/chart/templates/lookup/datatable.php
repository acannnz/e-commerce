<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th-list"></i></span>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-common-chart-templates" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('global:select') ?></th>
                <th><?php echo lang('chart_template:complaint_label') ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php echo form_close() ?>
<script type="text/javascript">//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {
				$.fn.extend({
						DT_Lookup_CommonChartTemplates: function(){
								var _this = this;
								
								if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
									return _this
								}
								
								var _datatable = _this.DataTable( {
									dom: 'tip',
									lengthMenu: [ 5, 10, 20 ],
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
											url: "<?php echo base_url("common/chart-templates/lookup_collection") ?>",
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
														var data = {'id': row.id, 'complaint': row.chief_complaint,'subjective': row.subjective, 'objective': row.objective, 'assessment': row.assessment, 'plan': row.plan};
														var json = JSON.stringify( data ).replace( /"/g, '\\"' );
														return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
													}
											},
											{ 
												data: "chief_complaint",
												orderable : true,
												searchable : true,    
											}
										]
								} );
							
							return _this
						}
					});
				
				var _datatable = $( "#dt-lookup-common-chart-templates" ).DT_Lookup_CommonChartTemplates();
				
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
        	});	
	})( jQuery );
//]]></script>

