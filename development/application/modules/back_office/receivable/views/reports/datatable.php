<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open() ?>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:periode_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $house->system_date ?>" class="form-control datepicker">
            </div>
            <label class="col-lg-2 control-label text-center"><?php echo lang('credit_debit_notes:till_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="date_end" name="date_end" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $house->system_date ?>" class="form-control datepicker">
            </div>
        </div>
    </div>
	<div class="col-md-6">
        <label class="col-lg-3 control-label"><?php echo lang('types:type_label')?></label>
        <div class="col-lg-5">
            <select id="type_id" name="type_id" class="form-control">
                <option value="all"><?php echo lang('global:select-all') ?></option>
                <?php if( !empty($options_type)): foreach( $options_type as $k => $v ): ?>
                <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
    </div>
</div>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:supplier_label') ?></label>
            <div class="col-lg-3">
	            <input type="hidden" id="supplier_id" name="supplier_id" class="form-control" value="" />
	            <input type="text" id="supplier_code" name="supplier_code" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="supplier_name" name="supplier_name" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_suppliers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" ><i class="fa fa-gear"></i></a>
                    <a href="javascript:;" title="" id="btn-clear-supplier"  class="btn btn-danger" ><i class="fa fa-times"></i></a>
                </div>
            </div>
        </div>
    </div>
	<div class="col-md-6 text-right">
    	<div class="row">
        	<div class="col-md-8">
                <a href="javascript:;" id="btn-search" class="btn btn-primary col-lg-12 col-sm-12"><b><i class="fa fa-search"></i> <?php echo lang("credit_debit_notes:find_credit_debit_note_list_label") ?></b></a>
            </div>
            <div class="col-md-4">
                <button type="reset" class="btn btn-warning"><b><i class="fa fa-ban"></i> <?php echo lang("buttons:reset") ?></b></button>
                <a href="<?php echo base_url("payable/credit_debit_notes/create")?>"  class="btn btn-success"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
    		</div>
        </div>
    </div>
</div>
<?php echo form_close()?>
<div class="table-responsive">
    <table id="dt-payable-credit_debit_notes" class="table" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('credit_debit_notes:date_label') ?></th>
                <th><?php echo lang('credit_debit_notes:evidence_number_label') ?></th>
                <th><?php echo lang('credit_debit_notes:supplier_label') ?></th>
                <th><?php echo lang('credit_debit_notes:value_label') ?></th>
                <th><?php echo lang('credit_debit_notes:description_label') ?></th>
                <th></th>
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
				DataTablePayableCredit_debit_notes: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 50, 75, 100, 150 ],
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("payable/credit_debit_notes/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.f = {
												"date_start" : $("#date_start").val(),
												"date_end" : $("#date_end").val(),
												"type_id" : $("#type_id").val() || "all",
												"supplier_id" : $("#supplier_id").val() || "all",
											}
										
									}
								},
							fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
							columns: [
									{ 
										data: "voucher_date", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "voucher_number",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "supplier_name",
									},
									{ 
										data: "value", 
										className: "text-right", 
										render: function ( val, type, row, meta){
												return Number(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
											}
									},
									{ data: "description" },
									{ 
										data: "voucher_cancel", 
										className: "a-center",
										orderable: false,
										render: function ( val, type, row ){
												if (1 == val) 
												{
													return  "<span class=\"label label-danger\"><?php echo lang( "buttons:cancel" ) ?></span>";
												}
												
												if (row.close_book == 1)
												{
													return "<span class=\"label label-danger\"><?php echo lang( "buttons:close" ) ?></span>";
												}

												if (row.posted == 1)
												{
													return "<span class=\"label label-success\"><?php echo lang( "buttons:posting" ) ?></span>";
												}
												
												return "<span class=\"label label-success\"><?php echo lang( "buttons:open" ) ?></span>";
												
											}
									},
									{ 
										data: "id",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){							
												buttons = "<a href=\"<?php echo base_url("payable/credit_debit_notes/edit") ?>/" + val + "\" data-toggle=\"form-ajax-modal\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"label label-info\"><i class=\"fa fa-eye\"></i></a>";
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-payable-credit_debit_notes_length select, #dt-payable-credit_debit_notes_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-payable-credit_debit_notes" ).DataTablePayableCredit_debit_notes();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-payable-credit_debit_notes" ).DataTable().ajax.reload();
				});
				
				$("#btn-clear-supplier").on("click", function(e){
					e.preventDefault();
					
					$("#supplier_id, #supplier_code, #supplier_name").val( "" );
				});
				
			});
	})( jQuery );
//]]>
</script>