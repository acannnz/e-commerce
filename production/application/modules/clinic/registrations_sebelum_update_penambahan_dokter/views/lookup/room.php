<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Daftar Kamar</h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

					$('#NoBed').val(_response.NoBed || 1);
					$('#NoKamar').val(_response.NoKamar);
					$('#Kamar').val(_response.NoKamar);
					$('#lookup-ajax-modal').remove();
					$("body").removeClass("modal-open").removeAttr("style");
					
					} catch (e){console.log(e);}
				}
			}
			//]]></script>
            <div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<input type="search" id="lookupbox_search_words" value="" placeholder="Search..." class="form-control">
							<div class="input-group-btn">
								<button type="button" id="lookupbox_search_button" class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-sections" class="table table-sm table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo lang('registrations:room_label')?></th>
							<th><?php echo lang('registrations:section_label')?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				dtLookupRoom: function(){
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
							ajax: {
									url: "<?php echo base_url("registrations/room/lookup_collection") ?>",
									type: "GET",
									data: function( params ){
										var destination = $("#dt_registration_section").DataTable().row(0).data() || [];
										params.SectionID = destination.SectionID || '';
										params.KelasID = $('#KdKelas').val() || 'XX';
										params.Status = 'VC';
									}
								},
							columns: [
									{ 
										data: "NoKamar",
										className: "text-center actions",
										orderable: false,
										searchable: false,
										width: '100px',
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoKamar",     
										className: "text-center",
										orderable: true,
										searchable: true,
										render: function(val){
											return '<b>' + val + '</b>'
										}
									},
									{ 
										data: "SectionName",     
										orderable: true,
										searchable: true,
									},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-sections" ).dtLookupRoom();

		$('#dt-lookup-sections tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('selected') ) {
				$(this).removeClass('selected');
			}else {
				$('#dt-lookup-sections tbody tr.selected').removeClass('selected');
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
		
		function searchWord(){
			var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
							_datatable.DataTable().search( words );
			_datatable.DataTable().draw(true);	
		}
		
	})( jQuery );
//]]></script>


