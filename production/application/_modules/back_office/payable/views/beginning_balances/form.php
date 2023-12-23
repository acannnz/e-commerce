<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<?php echo form_open( current_url(), array("id" => "form_beginning_balances"));?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('beginning_balances:supplier_label') ?> <span class="text-danger">*</span></label>
			<input type="hidden" id="Supplier_ID" name="Supplier_ID"  value="<?php echo @$item->Supplier_ID ?>"/>
			<input type="hidden" id="No_Voucher" name="No_Voucher"  value="<?php echo @$item->No_Voucher ?>"/>
			<div class="col-md-9 input-group">
				<input type="text" id="Nama_Supplier" name="Nama_Supplier"  value="<?php echo @$item->Nama_Supplier ?>" class="form-control" readonly />
				<div class="input-group-btn">
					<a href="<?php echo @$lookup_suppliers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip <?php echo (@$is_edit) ? 'disabled' : NULL; ?>" ><i class="fa fa-gear"></i></a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('beginning_balances:division_name_label')?> <span class="text-danger">*</span></label>
			<div class="col-lg-9">
				<select id="DivisiID" name="DivisiID" class="form-control" <?php echo (@$is_edit) ? 'disabled' : NULL; ?>>
					<?php if( !empty($options_division)): foreach( $options_division as $k => $v ): ?>
					<option value="<?php echo $k ?>" <?php echo ($k == @$item->DivisiID ) ? "selected" : NULL ?>><?php echo $v ?></option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('beginning_balances:project_name_label')?> <span class="text-danger">*</span></label>
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
				<select id="JenisHutang_ID" name="JenisHutang_ID" class="form-control" <?php echo (@$is_edit) ? 'disabled' : NULL; ?>>
					<?php if( !empty($options_type)): foreach( $options_type as $k => $v ): ?>
					<option value="<?php echo $k ?>" <?php echo ($k == @$item->JenisHutang_ID ) ? "selected" : NULL ?>><?php echo $v ?></option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang("beginning_balances:date_label")?> <span class="text-danger">*</span></label>
			<div class="col-md-9">
				<input type="text" id="Tgl_Saldo" name="Tgl_Saldo" class="form-control datepicker" value="<?php echo $beginning_balance_date ?>" readonly />
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
		<div class="form-group">
			<div class="row">
				<div class="col-md-offset-3 col-md-9">
					<div class="col-md-6">
						<button type="reset" id="btn-reset" class="btn btn-warning btn-block"><b><i class="fa fa-ban"></i> <?php echo lang("buttons:reset")?></b></button>
					</div>
					<div class="col-md-6">
						<button type="submit" id="btn-submit" name="btn-submit" class="btn btn-primary btn-block"><b> <i class="fa fa-save"></i> <?php echo lang("buttons:submit")?></b></button>
					</div>
				</div>
			</div>
		</div>
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
							"Supplier_ID" : $("#Supplier_ID").val(),
							"No_Voucher" : $("#No_Voucher").val(),
							"Kode_Supplier" : $("#Kode_Supplier").val(),
							"Kode_Proyek" : $("#Kode_Proyek").val(),
							"DivisiID" : $("#DivisiID").val(),
							"Currency_ID" : $("#Currency_ID").val(),
							"JenisHutang_ID" : $("#JenisHutang_ID").val(),
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
						$("#supplier_id").val("");
						
						$( "#ajax-modal" ).remove();
						$("body").removeClass("modal-open");

						$( "#dt-table-details" ).DataTable().ajax.reload();
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>