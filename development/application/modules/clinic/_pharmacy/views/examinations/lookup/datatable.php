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
	<div class="col-md-3">
    	<div class="form-group">
			<label class="control-label"><?php echo '&nbsp' ?></label>
            <div class="checkbox">
				<input type="checkbox" id="show_paid" value="1" class=""><label for="show_paid"> Tampilkan Sudah Closing Kasir</label>
			</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="dt-lookup-examinations" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th></th>
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
				DT_Lookup_Examinations: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							//dom: 'tip',
							lengthMenu: [ 25, 50, 100 ],
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							// bLengthChange: false,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("pharmacy/examinations/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
										params.show_paid = $("#show_paid").is(":checked") ? 1 : 0;
										
										params.date_from = $("#date_from").val();
										params.date_till = $("#date_till").val();
									}
								},
							columns: [
									{ 
										data: "NoReg",
										className: "text-center actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:select" ) ?>\" class=\"btn btn-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:select" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoReg",     
										width: "160px",
										orderable: true,
										searchable: true,
										className: "text-center",
										name: "a.NoReg",
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
					
					$( "#dt-lookup-examinations_length select, #dt-lookup-examinations_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-examinations" ).DT_Lookup_Examinations();
			
		
		$(".datepicker").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
			_datatable.DataTable().ajax.reload();
		});
		
		$("#show_paid").on("change", function (e) {
			_datatable.DataTable().ajax.reload();
		});
		
	})( jQuery );
//]]></script>

