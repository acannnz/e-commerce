<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>
<?php
if (isset($datatables)) {
    $sort = strtoupper(config_item('date_picker_format'));
?>
<script type="text/javascript">//<![CDATA[
(function( $ ){
		var _datatables = {
				init: function(){
						var _table = $( 'table.table-datatables' );						
						
						_table.dataTable({
								"bProcessing": true,
								"sDom": "<'row'<'col-sm-5'l><'col-sm-7'f>r>t<'row'<'col-sm-5'i><'col-sm-7'p>>",
								"sPaginationType": "full_numbers",
								"iDisplayLength": <?php echo config_item('rows_per_table')?>,
								"oLanguage": {
										"sProcessing": "<?php echo lang('processing')?>",
										"sLoadingRecords": "<?php echo lang('loading')?>",
										"sLengthMenu": "<?php echo lang('show_entries')?>",
										"sEmptyTable": "<?php echo lang('empty_table')?>",
										"sZeroRecords": "<?php echo lang('no_records')?>",
										"sInfo": "<?php echo lang('pagination_info')?>",
										"sInfoEmpty": "<?php echo lang('pagination_empty')?>",
										"sInfoFiltered": "<?php echo lang('pagination_filtered')?>",
										"sInfoPostFix":  "",
										"sSearch": "<?php echo lang('search')?>:",
										"sUrl": "",
										"oPaginate": {
												"sFirst":"<?php echo lang('first')?>",
												"sPrevious": "<?php echo lang('previous')?>",
												"sNext": "<?php echo lang('next')?>",
												"sLast": "<?php echo lang('last')?>"
											}
									},
								"tableTools": {
										"sSwfPath": "<?php echo base_url() ?>themes/ms/assets/js/datatables/tableTools/swf/copy_csv_xls_pdf.swf",
										"aButtons": [
											{
												"sExtends": "csv",
												"sTitle": "<?php echo $this->config->item('company_name').' - '.lang('invoices')?>"
											},
											{
												"sExtends": "xls",
												"sTitle": "<?php echo $this->config->item('company_name').' - '.lang('invoices')?>"
											},
											{
												"sExtends": "pdf",
												"sTitle": "<?php echo $this->config->item('company_name').' - '.lang('invoices')?>"
											},
										],
									},
								"aaSorting": [],
								"aoColumnDefs":[{
										"aTargets": ["no-sort"]
									  , "bSortable": false
								  },{
										"aTargets": ["col-currency"]
									  , "sType": "currency"
								  }]    
							});
					}
			};
		
		$( document ).ready(function() {
				_datatables.init();
				
				/*$("#table-strings").DataTable().page.len(-1).draw();
				if ($('#table-strings').length == 1) { 
					$('#table-strings_length, #table-strings_paginate').remove(); 
					$('#table-strings_filter input').css('width','200px'); 
				}*/
			});
	})( jQuery );
//]]></script>
<?php }  ?>