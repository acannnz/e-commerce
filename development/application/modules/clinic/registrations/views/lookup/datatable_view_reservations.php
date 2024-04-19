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
    <table id="dt-lookup-reservations" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>NoReservasi</th>
                <th>NRM</th>
                <th>Nama</th>                
                <th>Phone</th>
                <th>Alamat</th>
                <th>Section</th>
                <th>Dokter</th>
                <!--<th>Hari</th>-->
                <th>Tanggal</th>
                <th>Pukul</th>
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
							//select: { style: 'single'},
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("registrations/reservation_collection") ?>",
									type: "POST",
									data: function( params ){
										params.is_register = 0;
										params.today = 1;
									}
								},
							columns: [
									{ 
										data: "NoReservasi",
										className: "actions",
										orderable: false,
										searchable: false,
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoReservasi",     
										orderable: true,
										searchable: true,
									},
									{ data: "NRM", orderable: true, searchable: true},
									{ data: "Nama", orderable: false, searchable: false},
									{ data: "Phone", orderable: true, searchable: true},
									{ 
										data: "Alamat", 
										orderable: true, 
										searchable: true,
										// render: function ( val ){
										// 	return val.substr(0,30);
										// },
									},
									{ data: "SectionName", orderable: true, searchable: true},
									{ data: "Nama_Supplier", orderable: true, searchable: true},
									//{ data: "UntukHari", orderable: true, searchable: true},
									{ 
										data: "UntukTanggal", 
										orderable: true, 
										searchable: true,
										render: function ( val ){
											return val.substr(0,10);
										},
									},
									{ data: "KeteranganWaktu", orderable: true, searchable: true},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-reservations" ).DT_Lookup_Reservations();

		$('#dt-lookup-reservations tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('selected') ) {
				$(this).removeClass('selected');
			}else {
				$('#dt-lookup-reservations tbody tr.selected').removeClass('selected');
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

