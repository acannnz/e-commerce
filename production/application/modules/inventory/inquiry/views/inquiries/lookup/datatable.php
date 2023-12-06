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
    <table id="dt-lookup-inquiries" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Nomor Bukti</th>
                <th>Tanggal</th>
                <th>Section Asal</th>                
                <th>Keterangan</th>                
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
				DT_Lookup_Inquiries: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 30, 60 ],
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[2, 'DESC']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("inquiry/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
										params.mutation = 1;
										params.SectionTujuanID = "<?php echo $SectionID ?>";
									}
								},
							columns: [
									{ 
										data: "NoBukti",
										className: "actions text-center",
										orderable: false,
										searchable: false,
										width: "120px",
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoBukti",     
										width: "160px",
										name: "a.NoBukti",
										orderable: true,
										searchable: true,
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Tanggal", 
										orderable: true, 
										searchable: true,
										name: "a.Tanggal",
										className: "text-center",
										render: function( val ){
											return val.substr(0,11)
										}
									},
									{ 
										data: "SectionAsalName", 
										name: "b.SectionName",
										orderable: true, 
										searchable: true,
									},
									{ 
										data: "Keterangan", 
										name: "a.Keterangan",
										orderable: true, 
										searchable: true,
										render: function( val ){
											return val.substr(0,30)
										}
									},								
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-inquiries" ).DT_Lookup_Inquiries();
		
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

