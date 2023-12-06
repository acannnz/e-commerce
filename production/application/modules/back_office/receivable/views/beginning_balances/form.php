<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_beginning_balances"));?>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('beginning_balances:customer_label') ?> <span class="text-danger">*</span></label>
    <input type="hidden" id="Customer_ID" name="Customer_ID"  value="<?php echo @$item->Customer_ID ?>"/>
    <input type="hidden" id="No_Invoice" name="No_Invoice"  value="<?php echo @$item->No_Invoice ?>"/>
    <div class="col-md-2">
	    <input type="text" id="Kode_Customer" name="Kode_Customer"  value="<?php echo @$item->Kode_Customer ?>" class="form-control" readonly />
    </div>
    <div class="col-md-7 input-group">
        <input type="text" id="Nama_Customer" name="Nama_Customer"  value="<?php echo @$item->Nama_Customer ?>" class="form-control" readonly />
        <div class="input-group-btn">
            <a href="<?php echo @$lookup_customers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip <?php echo (@$is_edit) ? 'disabled' : NULL; ?>" ><i class="fa fa-gear"></i></a>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:division_name_label')?> <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <select id="DivisiID" name="DivisiID" class="form-control" <?php echo (@$is_edit) ? 'disabled' : NULL; ?>>
            <?php if( !empty($options_division)): foreach( $options_division as $k => $v ): ?>
            <option value="<?php echo $k ?>" <?php echo ($k == @$item->DivisiID ) ? "selected" : NULL ?>><?php echo $v ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:project_name_label')?> <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <select id="Kode_Proyek" name="Kode_Proyek" class="form-control" <?php echo (@$is_edit) ? 'disabled' : NULL; ?>>
            <?php if( !empty($options_project)): foreach( $options_project as $k => $v ): ?>
            <option value="<?php echo $k ?>" <?php echo ($k == @$item->Kode_Proyek ) ? "selected" : NULL ?>><?php echo $v ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:type_label')?> <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <select id="JenisPiutang_ID" name="JenisPiutang_ID" class="form-control" <?php echo (@$is_edit) ? 'disabled' : NULL; ?>>
            <?php if( !empty($options_type)): foreach( $options_type as $k => $v ): ?>
            <option value="<?php echo $k ?>" <?php echo ($k == @$item->JenisPiutang_ID ) ? "selected" : NULL ?>><?php echo $v ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang("beginning_balances:date_label")?> <span class="text-danger">*</span></label>
    <div class="col-md-9">
        <input type="text" id="Tgl_Saldo" name="Tgl_Saldo" class="form-control datepicker" value="<?php echo date( "Y-m-t", strtotime( config_item("Tanggal Mulai System")." last day of previous month" ))?>" readonly />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('types:type_label')?> <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <select id="Currency_ID" name="Currency_ID" class="form-control" <?php echo (@$is_edit) ? 'disabled' : NULL; ?>>
            <?php if( !empty($options_currency)): foreach( $options_currency as $k => $v ): ?>
            <option value="<?php echo $k ?>" <?php echo ($k == @$item->Currency_ID ) ? "selected" : NULL ?>><?php echo $v ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang("beginning_balances:value_label")?> <span class="text-danger">*</span></label>
    <div class="col-md-9">
        <input type="text" id="Nilai" name="Nilai"  value="<?php echo number_format(@$item->Nilai, 2, '.', ',') ?>"  class="form-control currency" value="" required="required" />
    </div>
</div>
<div class="form-group text-right">
    <div class="col-md-12">
    	<button type="submit" id="btn-submit" name="btn-submit" class="btn btn-primary"><b> <i class="fa fa-save"></i> <?php echo lang("buttons:submit")?></b></button>
    	<button type="reset" id="btn-reset" class="btn btn-warning"><b><i class="fa fa-ban"></i> <?php echo lang("buttons:reset")?></b></button>
    </div>
</div>

<?php echo form_close(); ?>


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {
			
				$(".currency").on("focus",function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val == 0)
					{
						$(this).val("")
					} else {
						$(this).val(val)
					}
                });

				$(".currency").on("blur",function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val > 0)
					{
						val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
						var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
						$(this).val( mask );
					} else {
						$(this).val( "0.00" );
					}
                });
			
				$("form[id=\"form_beginning_balances\"]").on("submit", function(e){
					e.preventDefault();	

					var data_post = { f : {
							"Customer_ID" : $("#Customer_ID").val(),
							"No_Invoice" : $("#No_Invoice").val(),
							"Kode_Customer" : $("#Kode_Customer").val(),
							"Kode_Proyek" : $("#Kode_Proyek").val(),
							"DivisiID" : $("#DivisiID").val(),
							"Currency_ID" : $("#Currency_ID").val(),
							"JenisPiutang_ID" : $("#JenisPiutang_ID").val(),
							"Tgl_Saldo" : $("#Tgl_Saldo").val(),
							"Nilai" : parseFloat( $("#Nilai").val().replace(/[^0-9\.-]+/g,"") ),
						}	}
				
					$.post( $(this).attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);
	
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						} 
						
						$.alert_success( response.message );
						
						$("#form_beginning_balances").trigger("reset");
						$("#customer_id").val("");
						
						$( "#ajax-modal" ).remove();
						$("body").removeClass("modal-open");

						$( "#dt-table-details" ).DataTable().ajax.reload();
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>