<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('postings:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('postings:periode_label') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="date_end" name="date_end" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('buttons:search') ?></label>
					<input type="text" id="search_text" name="search_text" class="form-control">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<a href="javascript:;" id="btn-search" class="btn btn-block btn-primary"><b><i class="fa fa-search"></i> <?php echo lang("postings:find_posting_list_label") ?></b></a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<a href="<?php echo $posting_url ?>" data-toggle="form-ajax-modal" class="btn btn-block btn-danger"><b><i class="fa fa-exchange"></i> <?php echo lang("buttons:posting")?></b></a>
				</div>    
			</div>
		</div>
		
		<?php echo form_open( base_url("general-cashier/postings/posting"), array("id" => "form_postings")) ?>
		<div class="table-responsive">
			<table id="dt-postings" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>
							<div class="checkbox" title="<?php echo lang("global:select_all"); ?>">
								<input type="checkbox" id="check-all" name="checked-all" class="" />
								<label for="check-all">&nbsp;</label>
							</div>
						</th>
						<th><?php echo lang('postings:date_label') ?></th>
						<th><?php echo lang('postings:evidence_number_label') ?></th>
						<th><?php echo lang('postings:value_label') ?></th>
						<th><?php echo lang('postings:type_transaction_label') ?></th>
						<th><?php echo lang('postings:description_label') ?></th>
					</tr>
				</thead>        
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo form_close()?>
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
							searching: false,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("general-cashier/postings/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.date_start = $("#date_start").val();
										params.date_end = $("#date_end").val();
										params.search_text = $("#search_text").val();
									}
								},
							fnDrawCallback: function( settings ){ 
								$( window ).trigger( "resize" ); 
							},
							columns: [
									{ 
										data: "No_Bukti",
										className: "text-right",
										orderable: false,
										width: '50px',
										render: function ( val, type, row, meta ){
												return 	'<div class="checkbox">' +
															'<input type="checkbox"  id="row'+ meta.row +'" name="postings[][No_Bukti]" data class=\"post-check\" value ="'+ val +'" >' +
															'<label for="row'+ meta.row +'">&nbsp;</label>' +
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
										data: "Type_Transaksi",
										className: "text-center"
									},
									{ 
										data: "Keterangan",
									},									
								],
								createdRow: function ( row, data, index ){
										$( "input.post-check", row ).on( "click", function(e){
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
						? $(".post-check").closest( 'tr' ).addClass('danger')
						: $(".post-check").closest( 'tr' ).removeClass('danger');
				});
				
				$("#btn-clear-supplier").on("click", function(e){
					e.preventDefault();
					
					$("#supplier_id, #supplier_code, #supplier_name").val( "" );
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