<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('postings:create_heading'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('postings:periode_label') ?></label>
					<div class="col-lg-4">
						<input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
					<label class="col-lg-2 control-label text-center"><?php echo lang('postings:till_label') ?></label>
					<div class="col-lg-4">
						<input type="text" id="date_end" name="date_end" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('postings:customer_label') ?></label>
					<div class="col-lg-3">
						<input type="hidden" id="customer_id" name="customer_id" class="form-control" value="" />
						<input type="text" id="customer_code" name="customer_code" class="form-control" readonly />
					</div>
					<div class="col-md-7 input-group">
						<input type="text" id="customer_name" name="customer_name" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo @$lookup_customers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" ><i class="fa fa-gear"></i></a>
							<a href="javascript:;" title="" id="btn-clear-customer"  class="btn btn-danger" ><i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<a href="javascript:;" id="btn-search" class="btn btn-block btn-primary"><b><i class="fa fa-search"></i> <?php echo lang("postings:find_posting_list_label") ?></b></a>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<div class="checkbox">
						<input type="checkbox" id="check-all" name="checked-all" class="" />
						<label for="check-all"><?php echo lang("global:select_all"); ?></label>
					</div>
				</div>
				<div class="form-group">
					<a href="<?php echo $posting_cancel_url ?>" data-toggle="form-ajax-modal" class="btn btn-block btn-danger"><b><i class="fa fa-random"></i> <?php echo lang("buttons:posting")?></b></a>
				</div>    
			</div>
		</div>
		
		<?php echo form_open( base_url("receivable/postings/posting_cancel"), array("id" => "form_posting_cancel")) ?>
		<div class="table-responsive">
			<table id="dt-postings" class="table table-bordered table-striped table-sm" width="100%">
				<thead>
					<tr>
						<th></th>
						<th><?php echo lang('postings:date_label') ?></th>
						<th><?php echo lang('postings:posting_number_label') ?></th>
						<th><?php echo lang('postings:value_label') ?></th>
						<th><?php echo lang('postings:currency_label') ?></th>
						<th><?php echo lang('postings:description_label') ?></th>
						<th><?php echo lang('postings:customer_code_label') ?></th>
						<th><?php echo lang('postings:customer_name_label') ?></th>
					</tr>
				</thead>        
				<tbody>
				</tbody>
			</table>
		</div>
		<?php echo form_close()?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTableGeneralLedgerPostings: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: false,
							lengthMenu: [ 50, 75, 100, 150 ],
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("receivable/postings/datatable_collection_cancel") ?>",
									type: "POST",
									data: function( params ){
										
										params.date_start = $("#date_start").val();
										params.date_end = $("#date_end").val();
										params.Customer_ID = $("#customer_id").val();
									}
								},
							fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
							fnRowCallback : function( nRow, aData, iDisplayIndex ) {
								$( window ).trigger( "resize" ); 
									/*var index = iDisplayIndex + 1;
									$('td:eq(0)',nRow).html('<b>'+ index +'</b>');
									return nRow;*/
				
								},
							columns: [
									{ 
										data: "No_Bukti",
										className: "text-right",
										orderable: false,
										render: function ( val, type, row, meta ){
												return 	'<div class="checkbox">' +
															'<input type="checkbox"  id="row'+ meta.row +'" name="postings[row'+ meta.row +'][No_Bukti]" class=\"post-check\" value ="'+ val +'" >' +
															'<label for="row'+ meta.row +'">&nbsp;</label>' +
															'<input type="hidden" class="JTransaksi_ID" name="postings[row'+ meta.row +'][JTransaksi_ID]" value="'+ row.JTransaksi_ID +'" disabled>' +
														'</div>';
											}

									},
									{ 
										data: "Tgl_Transaksi", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val.substr(0,10) + "</b>"
											}
									},
									{ 
										data: "No_Bukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Nilai",
										className: "text-center",
										render: function( val ){
											return mask_number.currency_add( val );
										} 
									},
									{ 
										data: "Mata_Uang",
										className: "text-center"
									},
									{ 
										data: "Keterangan",
									},
									{ data: "Kode_Customer" },
									{ data: "Nama_Customer" },
									
								],
								columnDefs  : [

										{
											"targets": ["source"],
											"visible": false,
											"searchable": false
										}
									],		
								createdRow: function ( row, data, index ){
										$( "input.post-check", row ).on( "click", function(e){

											$(this).prop("checked") 
												? $("input[name=\"postings["+ $(this).prop('id') +"][JTransaksi_ID]\"").prop("disabled", false)
												: $("input[name=\"postings["+ $(this).prop('id') +"][JTransaksi_ID]\"").prop("disabled", true);

												$(this).prop("checked") 
													? $(row).addClass("danger")
													: $(row).removeClass("danger");
											})
									}				
						} );
						
					$( "#dt-postings_length select, #dt-postings_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-postings" ).DataTableGeneralLedgerPostings();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-postings" ).DataTable().ajax.reload();
				});
				
				$("#check-all").on("change", function(e){
					
					$(".post-check").prop('checked', $(this).prop("checked"));

					$(this).prop("checked") 
						? $(".JTransaksi_ID").prop("disabled", false)
						: $(".JTransaksi_ID").prop("disabled", true);

					$(this).prop("checked") 
						? $(".post-check").closest( 'tr' ).addClass('danger')
						: $(".post-check").closest( 'tr' ).removeClass('danger');
				});
								
				$("#btn-clear-customer").on("click", function(e){
					e.preventDefault();
					
					$("#customer_id, #customer_code, #customer_name").val( "" );
				});
				
				$("form[id=\"form_postings\"]").on("submit", function(e){
					e.preventDefault();	

					var data_post = $( this ).serialize();
				
					$.post( $(this).attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);
	
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						} 
						
						$.alert_success( response.message );
						
						$( "#dt-postings" ).DataTable().ajax.reload();
						
					})	
				});
				
			});
	})( jQuery );
//]]>
</script>