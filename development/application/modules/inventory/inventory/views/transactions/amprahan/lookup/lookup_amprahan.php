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
    <table id="dt-lookup-amprahan" class="table table-sm table-bordered table-hover table-icd" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('label:date') ?></th>
                <th><?php echo lang('label:no_evidence') ?></th>
                <th><?php echo lang('label:section_name')?></th>
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
				DT_Lookup_Amprahan: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							order: [[2, 'DESC']],
							searching: true,
							info: true,
							responsive: true,
							scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("inventory/transactions/amprahan/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
											if ( $("#SectionAsal").size() > 0 )
											{
												params.SectionTujuanID = $("#SectionAsal").val();
											}
											
											if ( $("form[name=\"form_mutations\"]").size() )
											{
												params.is_from_mutation = 1;
											}
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
												return "<a href='javascript:try{row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{data: "Tanggal",
										render: function ( data, type, row ) {
											return (moment(data).format("DD/MM/YYYY"));
										  return data;
										  }
									},
									{data : "NoBukti"},
									{data: "SectionAsalName"},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-amprahan" ).DT_Lookup_Amprahan();
		
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

