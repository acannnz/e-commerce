<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_examination_inpatient" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th><?php echo lang('poly:evidence_number_label')?></th>
                        <th><?php echo lang('poly:date_label')?></th>                        
                        <th><?php echo lang('poly:doctor_label')?></th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$form_service_create ?>" data-toggle="form-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Biaya</b></a>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		$.fn.extend({
				dt_examination_inpatient: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "NoBukti", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-edit btn-xs\"><i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:edit')?></a>")
												} 
										},
										{ 
											data: "NoBukti", 
											className: "text-center", 
											render: function(val){
												return '<b>'+ val +'</b>';
											}
										},
										{ 
											data: "Tanggal", 
											className: "text-center",
											render: function(val, type, row){
												return row.Tanggal +' '+ row.Jam
											}
										},
										{ data: "NamaDokter"},
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){										
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												form_ajax_modal.show('<?php echo $form_service_edit?>/'+ data.NoBukti);
										});
											
										$( row ).on( "click", "a.btn-edit", function(e){
												e.preventDefault();												
												form_ajax_modal.show('<?php echo $form_service_edit?>/'+ data.NoBukti);												
										})
									}
							} );
							
						$( "#dt_examination_inpatient_length select, #dt_examination_inpatient_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_examination_inpatient" ).dt_examination_inpatient();

			});

	})( jQuery );
//]]>
</script>