<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open() ?>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
            <label class="col-lg-2 control-label"><?php echo lang('facturs:periode_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
            </div>
            <label class="col-lg-2 control-label text-center"><?php echo lang('facturs:till_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="date_end" name="date_end" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
            </div>
        </div>
    </div>
	<div class="col-md-6">
    	<div class="row">
        	<div class="col-md-6">
                <label class="col-lg-3 control-label"><?php echo lang('types:type_label')?></label>
                <div class="col-lg-9">
                	<select id="type_id" name="type_id" class="form-control">
                    	<option value=""><?php echo lang('global:select-all') ?></option>
                    	<?php if( !empty($options_type)): foreach( $options_type as $k => $v ): ?>
                    	<option value="<?php echo $k ?>"><?php echo $v ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>
			<div class="col-md-6">        
                <div class="col-lg-1-offset col-lg-3">
                    <label class="switch">
                        <input type="checkbox" id="factur_cancel" name="factur_cancel" value="1">
                        <span></span>
                    </label>
                </div>
                <label class="col-lg-8 control-label"><?php echo lang('facturs:cancel_factur_label')?></label>
			</div>
        </div>
    </div>
</div>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
            <label class="col-lg-2 control-label"><?php echo lang('facturs:customer_label') ?></label>
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
	<div class="col-md-6 text-right">
    	<a href="javascript:;" id="btn-search" class="btn btn-primary"><b><i class="fa fa-search"></i> <?php echo lang("facturs:find_factur_list_label") ?></b></a>
    	<button type="reset" class="btn btn-warning"><b><i class="fa fa-ban"></i> <?php echo lang("buttons:reset") ?></b></button>
        <a href="<?php echo base_url("receivable/factur/create")?>"  class="btn btn-success"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
    </div>
</div>
<?php echo form_close()?>
<div class="table-responsive">
    <table id="dt-receivable-facturs" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('facturs:factur_number_label') ?></th>
                <th><?php echo lang('facturs:description_label') ?></th>
                <th><?php echo lang('facturs:date_label') ?></th>
                <th><?php echo lang('facturs:customer_label') ?></th>
                <th><?php echo lang('facturs:currency_label') ?></th>
                <th><?php echo lang('facturs:value_label') ?></th>
                <th><?php echo lang('facturs:proyek_label') ?></th>
                <th><?php echo lang('facturs:division_label') ?></th>
                <th><?php echo lang('facturs:user_label') ?></th>
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
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("receivable/factur/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.f = {
												"date_start" : $("#date_start").val(),
												"date_end" : $("#date_end").val(),
												"type_id" : $("#type_id").val(),
												"customer_id" : $("#Customer_ID").val(),
												"factur_cancel" : $("#factur_cancel:checked").val() || 0,
											}
									}
								},
							columns: [
									{ 
										data: "No_Faktur", 
										width: "140px",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Keterangan",
										render: function( val ){
												return val.substr(0, 45)
											} 
									},
									{ 
										data: "Tgl_Faktur",
										className: "text-center"
									},
									{ 
										data: "Nama_Customer", 
										render: function( val ){
												return val.substr(0, 45)
											} 
									},
									{ 
										data: "Currency_Code", 
									},
									{ 
										data: "Nilai_Faktur", 
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
										data: "Nama_Singkat", 
									},
									{ 
										data: "No_Faktur",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){							
												buttons = "<a href=\"<?php echo base_url("receivable/factur/edit") ?>/?No_Faktur=" + encodeURIComponent(val) + "\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"label label-info\"><i class=\"fa fa-eye\"></i></a>";
												return buttons
											}
									}
								],
							fnDrawCallback: function( settings ){ 
										$( window ).trigger( "resize" ); 
								},
						} );
						
					$( "#dt-receivable-facturs_length select, #dt-receivable-facturs_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-receivable-facturs" ).DataTableReceivableFacturs();
				
				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-receivable-facturs" ).DataTable().ajax.reload();
				});
				
				$("#btn-clear-customer").on("click", function(e){
					e.preventDefault();
					
					$("#Customer_ID, #Kode_Customer, #Nama_Customer").val( "" );
				});
				
			});
	})( jQuery );
//]]>
</script>