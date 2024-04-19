<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label"><?php echo 'Tanggal Dari' ?></label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
				<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
				<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
			</div>
        </div>
	</div>
	<div class="col-md-8">
    	<div class="form-group">
			<label class="control-label"><?php echo '&nbsp' ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="dt-lookup-prescriptions" class="table table-sm table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>No Resep</th>
                    <th>No Registrasi</th>                
                    <th>Tanggal</th>
                    <th>Section</th>
                    <th>N.R.M</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Jenis</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
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
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("pharmacy/prescriptions/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
										params.Realisasi = 0;
										params.is_billing = 1;
										
										params.date_from = $("#date_from").val();
										params.date_till = $("#date_till").val();
									}
								},
							columns: [
									{ 
										data: "NoResep",
										className: "text-center actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoResep",     
										width: "160px",
										orderable: true,
										searchable: true,
										className: "text-center",
										name: "a.NoResep",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "NoRegistrasi",     
										width: "160px",
										orderable: true,
										searchable: true,
										className: "text-center",
										name: "a.NoRegistrasi",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Tanggal", orderable: true, searchable: true, className: "text-center",
										name: "a.Tanggal",
										render: function ( val, type, row ){
												return val.substr(0,10)
											}
									},
									{ data: "SectionName", orderable: true, searchable: true,name: "f.SectionName"},
									{ data: "NRM", orderable: true, searchable: true, className: "text-center",name: "b.NRM"},
									{ data: "NamaPasien", orderable: true, searchable: true,name: "c.NamaPasien"},
									{ data: "Nama_Supplier", orderable: true, searchable: true,name: "e.Nama_Supplier"},
									{ data: "JenisKerjasama", orderable: true, searchable: true, className: "text-center",name: "c.JenisKerjasama"},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-prescriptions" ).DT_Lookup_Reservations();
		
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
		
		$(".datepicker").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
			_datatable.DataTable().ajax.reload();
		});
		
	})( jQuery );
//]]></script>

