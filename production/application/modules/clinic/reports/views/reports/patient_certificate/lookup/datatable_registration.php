<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
        	<div class="col-md-6">
                <label class="col-md-3 control-label"><?php echo "Dari Tanggal" ?></label>
                <div class="col-md-3">
                    <input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
                </div>
                <label class="col-md-3 control-label text-center"><?php echo "Sampai Tanggal" ?></label>
                <div class="col-md-3">
                    <input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
                </div>
            </div>
        	<div class="col-md-6">
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
    <table id="dt-lookup-registration" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo "No. Registrasi"?></th>
                <th><?php echo "Tanggal"?></th>
                <th><?php echo "NRM"?></th>
                <th><?php echo "Nama Pasien"?></th>
                <th><?php echo "Tipe Pasien"?></th>                
                <th><?php echo lang("global:state")?></th>                
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
				DT_Lookup_Treated: function(){
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
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("reports/reports/lookup_registration_collection") ?>",
									type: "POST",
									data: function( params ){
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
										width: '100px',
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoReg",     
										orderable: true,
										searchable: true,
										width: '130px',
										className: "text-center",
										render: function( val ){
											return "<b>"+ val +"</b>";
										}
									},
									{ 
										data: "JamReg", 
										orderable: true, 
										searchable: true,
										className: "text-center",
									},
									{ 
										data: "NRM",     
										orderable: true,
										searchable: true,
										className: "text-center",
										render: function( val ){
											return "<b>"+ val +"</b>";
										}
									},
									{ data: "NamaPasien", orderable: true, searchable: true},
									{ 
										data: "JenisKerjasama", 
										orderable:true, 
										searchable:true,
										className: "text-center",
									},
									{ 
										data: "Status", 
										orderable:true, 
										searchable:true,
										className: "text-center",
									},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-registration" ).DT_Lookup_Treated();

		$('#dt-lookup-registration tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('selected') ) {
				$(this).removeClass('selected');
			}else {
				$('#dt-lookup-registration tbody tr.selected').removeClass('selected');
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
		
		$(".datepicker").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
			_datatable.DataTable().ajax.reload();
		});
		
	})( jQuery );
//]]></script>

