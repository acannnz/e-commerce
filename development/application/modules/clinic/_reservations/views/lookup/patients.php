<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('reservations:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
                    var _patient = JSON.parse(response);
					//console.log(_patient);
                    if( _patient ){
                                var _form = $( "form[name=\"form_reservations\"]" );
                                
                                _form.find( "input[name=\"f[NRM]\"]" ).val( _patient.NRM);
                                _form.find( "input[name=\"f[Nama]\"]" ).val( _patient.NamaPasien );
								_form.find( "input[name=\"f[Phone]\"]" ).val( _patient.Phone);
								_form.find( "input[name=\"f[Email]\"]" ).val( _patient.Email);
								_form.find( "#Alamat" ).val(_patient.Alamat);
								_form.find( "#JenisKerasamaID" ).val( _patient.JenisKerjasamaID );
								
                                $( '#lookup-ajax-modal' ).remove();
								$("body").removeClass("modal-open");
                    }
			}
			//]]></script>
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
				<table id="dt-lookup-patients" class="table table-sm table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th></th>
							<th>NRM</th>
							<th>Nama Pasien</th>
							<th>JK</th>                
							<th>Jenis</th>
							<th>Phone</th>
							<th>Alamat</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>


        </div>
        <div class="modal-footer">
        	
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
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
						lengthMenu: [ 15, 30 ],
						processing: true,
						serverSide: true,								
						paginate: true,
						ordering: true,
						select: { style: 'single'},
						order: [[1, 'asc']],
						searching: true,
						info: true,
						responsive: true,
						//scrollCollapse: true,
						//scrollY: "200px",
						ajax: {
								url: "<?php echo base_url("reservations/patients/lookup_collection") ?>",
								type: "POST",
								data: function( params ){}
							},
						columns: [
								{ 
									data: "NRM",
									name: "a.NRM",
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
									data: "NRM",     
									name: "a.NRM",
									className: "text-center",
									orderable: true,
									searchable: true,
									render: function(val){
										return '<b>' + val + '</b>'
									}
								},
								{ 
									data: "NamaPasien",    
									name: "b.NamaPasien", 
									orderable: true,
									searchable: true,
								},
								{ data: "JenisKelamin", name: "b.JenisKelamin", className: "text-center", orderable: true, searchable: true},
								{ data: "JenisPasien", name: "b.JenisPasien", orderable: true, searchable: true},
								{ data: "Phone", name: "b.Phone", orderable: true, searchable: true},
								{ 
									data: "Alamat", name: "b.Alamat", orderable: true, searchable: true,
									render: function ( val ){
										return val ? val.substr(0,30) : '';
									}
								},
							]
					} );
				
				return _this
			}
		});
	
	var _datatable = $( "#dt-lookup-patients" ).DT_Lookup_Reservations();

	$('#dt-lookup-patients tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}else {
			$('#dt-lookup-patients tbody tr.selected').removeClass('selected');
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
	
	$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup change", function(e){
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
