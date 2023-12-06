<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <label class="col-md-2 control-label"><?php echo lang('global:category')?></label>
            <div class="col-md-10">
                <select id="customer_category" class="form-control" >
                	<option value=""><?php echo lang("global:select-all")?></option>
                    <?php if (!empty($option_customer_category)) : foreach($option_customer_category as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
    	<div class="form-group">
        	<div class="col-md-10 col-md-offset-2">
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
</div>
<div class="table-responsive">
    <table id="dt-lookup-customers" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Kode</th>
                <th>Nama Customer</th>                
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php echo form_close() ?>
<script type="text/javascript">//<![CDATA[
(function( $ ){
		var datatable_searchable =  {
			init: function( _datatable ){
				$('#dt-lookup-customers tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
					}else {
						$('#dt-lookup-customers tbody tr.selected').removeClass('selected');
						$(this).addClass('selected');
					}
				} );
				
				$('#button').click( function () {
					table.row('.selected').remove().draw( false );
				} );		
		
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
				
				$( "#customer_category" ).on("change", function(e){
						e.preventDefault();
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(reloadTable, 400); 
						
					});
				
				function searchWord(){
					var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
					_datatable.DataTable().search( words );
					_datatable.DataTable().draw(true);	
				}

				function reloadTable(){
					_datatable.DataTable().ajax.reload();	
				}
			}
		}
		$.fn.extend({
				DT_Lookup_Customers: function(){
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
							//select: { style: 'single'},
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("common/customers/lookup_collection_back_office") ?>",
									type: "POST",
									data: function( params ){
										params.customer_category = $("#customer_category").val();
									}
								},
							columns: [
									{ 
										data: "Kode_Customer",
										className: "text-center actions",
										orderable: false,
										searchable: false,
										width: '100px',
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-xs btn-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "Kode_Customer",     
										className: "text-center",
										orderable: true,
										searchable: true,
										width: '130px',
										render: function(val){
											return '<b>' + val + '</b>'
										}
									},
									{ 
										data: "Nama_Customer",     
										orderable: true,
										searchable: true,
									},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-customers" ).DT_Lookup_Customers();
		
		datatable_searchable.init( _datatable );
		
	})( jQuery );
//]]></script>

