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
    <table id="dt-list-downlines" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('patient_cooperation_cards:mr_number_label') ?></th>
                <th><?php echo lang('patient_cooperation_cards:type_label') ?></th>
                <th><?php echo lang('patient_cooperation_cards:name_label') ?></th>
                <th><?php echo lang('patient_cooperation_cards:address_label') ?></th>
                <th><?php echo lang('patient_cooperation_cards:phone_label') ?></th>
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
				DT_Lookup_ListDownlines: function(){
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
									url: "<?php echo base_url("common/patient_cooperation_cards/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
										params.referrer_id = <?php echo @$referrer_id ?>;
									}
								},
							columns: [
									{ 
										data: "mr_number", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ data: "type_name", 
										width: "5%",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a";
											}
									},
									{ data: "personal_name", width: "20%" },
									{ 
										data: "personal_address", 
										width: "20%",
										render: function ( val, type, row ){
												return "<em>" + val + "</em>"
											}
									},
									{ 
										data: "phone_number", 
										className: "", 
										render: function ( val, type, row ){
												return ( val ) ? "<a href=\"tel:" + val + "\"><i class=\"fa fa-phone\" class=\"text-success\"></i> " + val + "</a>" : "n/a"
											}
									},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-list-downlines" ).DT_Lookup_ListDownlines();
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				var words = $( "input[type=\"search\"]#lookupbox_search_words" ).val() || "";
				if( words ){
					_datatable.DataTable().search( words );
					_datatable.DataTable().draw();
				}
			});
			
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup", function(e){
				e.preventDefault();
				
				var words = $( this ).val() || "";
				if( ! words ){
					_datatable.DataTable().search( "" );
					_datatable.DataTable().draw();
				}
			});
		
	})( jQuery );
//]]></script>

