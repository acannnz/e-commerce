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
    <table id="dt-lookup-schedule" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('schedule:doctor_name_label') ?></th>
                <th><?php echo lang('schedule:specialis_label') ?></th>    
                <th><?php echo lang('schedule:hari_label') ?></th>
                <th><?php echo lang('schedule:tanggal_label') ?></th>
                <th><?php echo lang('schedule:waktu_label') ?></th>      
                <th><?php echo lang('schedule:queue_label') ?></th>
                <th><?php echo lang('schedule:cancel_label') ?></th>
                <th><?php echo lang('schedule:doctor_change_label') ?></th>                  
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
				DT_Lookup_Schedule: function(){
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
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("schedules/lookup_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							columns: [
									{ 
										data: "SectionName",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = {'section_name': row.SectionName , 'DokterID': row.DokterID, 'supplier_name':row.Nama_Supplier, 'shedule_date':row.Tanggal, 'schedule_day':row.Hari, 'schedule_time':row.Waktu, 'noqueue':NoUrut};
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												return "<a id=\"apply\" href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}'title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs apply\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ 
										data: "Nama_Supplier",     
										width: "160px",
										orderable: true,
										searchable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "SpesialisName"},
									{ data: "Hari"},
									{ data: "Tanggal"},
									{ data: "Waktu", width: "100px",},
									{ data: "Antrian"},
									{ data: "Cancel" },
									{ data: "DokterPenggantiID"},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-schedule" ).DT_Lookup_Schedule();
		
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

