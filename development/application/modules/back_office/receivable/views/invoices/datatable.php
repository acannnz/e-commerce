<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open() ?>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
            <label class="col-lg-2 control-label"><?php echo lang('invoices:periode_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
            </div>
            <label class="col-lg-2 control-label text-center"><?php echo lang('invoices:till_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="date_end" name="date_end" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
            </div>
        </div>
    </div>
	<div class="col-md-6">
    	<div class="row">
            <label class="col-lg-2 control-label"><?php echo lang('invoices:customer_label') ?></label>
            <div class="col-lg-3">
	            <input type="hidden" id="Customer_ID" name="Customer_ID" class="form-control" value="" />
	            <input type="text" id="Kode_Customer" name="Kode_Customer" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="Nama_Customer" name="Nama_Customer" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_customers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" ><i class="fa fa-gear"></i></a>
                    <a href="javascript:;" title="" id="btn-clear-customer"  class="btn btn-danger" ><i class="fa fa-times"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
        	<label class="col-lg-2 control-label"><?php echo lang('invoices:view_label') ?></label>
            <div class="col-lg-3">
                <div class="radio">
                    <input type="radio" id="all_data" name="view_state" value="all" checked="checked"><label for="all_data"><?php echo lang('global:check-all') ?></label>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="radio">
                    <input type="radio" id="active_data" name="view_state" value="active" ><label for="active_data"><?php echo lang('global:active_status') ?></label>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="radio">
                    <input type="radio" id="cancel_data" name="view_state" value="cancel" ><label for="cancel_data"><?php echo lang('global:cancel_status') ?></label>
                </div>                    
            </div>
        </div>
    </div>
	<div class="col-md-offset-1 col-md-5">
    	<a href="javascript:;" id="btn-search" class="btn btn-primary"><b><i class="fa fa-search"></i> <?php echo lang("invoices:find_invoice_list_label") ?></b></a>
    	<button type="reset" class="btn btn-warning"><b><i class="fa fa-ban"></i> <?php echo lang("buttons:reset") ?></b></button>
        <a href="<?php echo base_url("receivable/invoices/create")?>"  class="btn btn-success pull-right"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
    </div>
</div>
<?php echo form_close()?>
<div class="table-responsive">
    <table id="dt-receivable-invoices" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('invoices:date_label') ?></th>
                <th><?php echo lang('invoices:invoice_number_label') ?></th>
                <th><?php echo lang('invoices:customer_label') ?></th>
                <th><?php echo lang('invoices:value_label') ?></th>
                <th><?php echo lang('invoices:remain_label') ?></th>
                <th><?php echo lang('invoices:proyek_label') ?></th>
                <th><?php echo lang('invoices:division_label') ?></th>
                <th></th>
            </tr>
        </thead>        
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTableReceivableFacturs: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 50, 75, 100, 150 ],
							order: [[0, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("receivable/invoices/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.f = {
												"date_start" : $("#date_start").val(),
												"date_end" : $("#date_end").val(),
												"Customer_ID" : $("#Customer_ID").val(),
												"view_state" : $("input[name=\"view_state\"]:checked").val() || '',
											}
									}
								},
							columns: [
									{ 
										data: "Tgl_Invoice",
										className: "text-center"
									},
									{ 
										data: "No_Invoice", 
										width: "140px",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Nama_Customer", 
										render: function( val ){
												return val ? val.substr(0, 45) : val;
											} 
									},
									{ 
										data: "Nilai", 
										className: "text-right", 
										render: function ( val, type, row, meta){
												return Number(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
											}
									},
									{ 
										data: "Sisa", 
										className: "text-right", 
										render: function ( val, type, row, meta){
												return Number(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
											}
									},
									{ 
										data: "Nama_Proyek",
									},
									{ 
										data: "Nama_Divisi", 
									},
									{ 
										data: "No_Invoice",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){		
												buttons = "<div class=\"btn-group\" role=\"group\" aria-label=\"\">";
													buttons += "<a href=\"<?php echo base_url("receivable/invoices/edit") ?>/?No_Invoice=" + encodeURIComponent(val) + "\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
													buttons += row.Cancel_Invoice == 1 ? "<button type=\"button\" class=\"btn btn-danger btn-xs\"><b><?php echo lang("buttons:cancel")?></b></button>" : '';
												buttons += "</div>";
												return buttons
											}
									}
								],
							fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
						} );
						
					$( "#dt-receivable-invoices_length select, #dt-receivable-invoices_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-receivable-invoices" ).DataTableReceivableFacturs();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-receivable-invoices" ).DataTable().ajax.reload();
				});
				
				$("#btn-clear-customer").on("click", function(e){
					e.preventDefault();
					
					$("#Customer_ID, #Kode_Customer, #Nama_Customer").val( "" );
				});
				
			});
	})( jQuery );
//]]>
</script>