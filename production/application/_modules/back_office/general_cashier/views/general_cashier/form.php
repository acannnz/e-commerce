<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->posted);exit;

?>
<?php echo form_open( current_url(), array("name" => "form_general_cashier") ); ?>
<input type="hidden" id="data_debit" data-debit="{}" data-headers="{}" data-details="{}" />
<input type="hidden" id="data_credit" data-credit="{}" data-headers="{}" data-details="{}" />
<input type="hidden" id="customer_id" value="<?php echo @$item->customer_id?>">
<input type="hidden" id="supplier_id" value="<?php echo @$item->supplier_id?>">
<div class="row form-group">
    <div class="col-lg-12 text-right">
        <a href="<?php echo base_url("general_cashier/general_cashier/create")?>"  class="btn btn-success"><b><?php echo lang( 'buttons:create' ) ?></b></a>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('general_cashier:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" id="evidence_number" name="evidence_number" value="<?php echo @$item->evidence_number ?>" class="form-control" readonly="readonly">
            </div>
        </div>
	</div>
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('general_cashier:transaction_type_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <select id="transaction_type" name="transaction_type" class="form-control"  <?php echo (@$is_edit) ? "disabled" : NULL ?> required>
                	<option value=""></option>
                    <option value="BKK" <?php echo ( "BKK" == @$item->transaction_type ) ? "selected" : NULL ?> > Buku Kas Keluar</option>
                    <option value="BKM" <?php echo ( "BKM" == @$item->transaction_type ) ? "selected" : NULL ?> > Buku Kas Masuk</option>
                    <option value="BBK" <?php echo ( "BBK" == @$item->transaction_type ) ? "selected" : NULL ?> > Buku Bank Keluar</option>
                    <option value="BBM" <?php echo ( "BBM" == @$item->transaction_type ) ? "selected" : NULL ?> > Buku Bank Masuk</option>
                    <option value="MUT" <?php echo ( "MUT" == @$item->transaction_type ) ? "selected" : NULL ?> > MUTASI</option>
                </select>
            </div>
        </div>
	</div>
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('general_cashier:date_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" id="transaction_date" name="transaction_date" value="<?php echo @$item->transaction_date ?>" data-date-min-date="<?php echo $house->system_date ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> placeholder="" class="form-control datepicker" required>
            </div>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('general_cashier:description_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <textarea id="description" name="description" class="form-control" required><?php echo @$item->description ?></textarea>
            </div>
        </div>
	</div>
	<div class="col-md-8">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('general_cashier:from_to_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" id="from_to" name="from_to" value="<?php echo @$item->from_to ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> placeholder="" class="form-control" required>
            </div>
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
    	<?php if ($item->posted) : ?>
			<h3  class="text-danger"><?php echo lang("general_cashier:posted_data")?></h3>
    	<?php endif;
		 	if ($item->close_book) :?>
			<h3  class="text-danger"><?php echo lang("general_cashier:close_book_data")?></h3>
    	<?php endif; ?>
	</div>
	<div class="col-md-6">
		<h3 id="general_cashier_value" class="pull-right text-danger"><?php echo "Rp. ".number_format($item->value, 2, ".", ","); ?></h3>
	</div>
</div>

<h2 class="text-primary"><i class="fa fa-sitemap"></i> <?php echo lang('general_cashier:transaction_debit_sub') ?></h2>
<div class="row form-group">
	<?php echo  modules::run("general_cashier/transaction_debit", @$item, @$is_edit) ?>
</div>

<h2 class="text-primary"><i class="fa fa-sitemap"></i> <?php echo lang('general_cashier:transaction_credit_sub') ?></h2>
<div class="row form-group">
	<?php echo  modules::run("general_cashier/transaction_credit", @$item, @$is_edit) ?>
</div>

<div class="row form-group">
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('general_cashier:debit_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" id="total_debit" name="f[debit]" value="<?php echo @$item->debit ?>" class="form-control" readonly="readonly">
            </div>
        </div>
	</div>
	<div class="col-md-4">
        <div class="form-group">
            <div class="col-lg-12 text-center">
                <h3 id="text_balance" class="text-danger">0</h3>
            </div>
        </div>
	</div>
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('general_cashier:credit_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" id="total_credit" name="f[credit]" value="<?php echo @$item->credit ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-lg-12 text-right">
    	<button type="submit" id="btn-submit" class="btn btn-primary"  <?php echo (@$is_edit) ? "disabled" : NULL ?> disabled="disabled"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"  <?php echo (@$is_edit) ? "disabled" : NULL ?>><?php echo lang( 'buttons:reset' ) ?></button>
        <a href="<?php echo base_url("general_cashier/general_cashier/cancel/$item->id")?>"  class="btn btn-danger" data-toggle="ajax-modal" <?php echo (@$is_edit && !$item->close_book && !$item->posted ) ? NULL : "disabled" ?>><b><?php echo lang( 'buttons:cancel' ) ?></b></a>
        <a href="<?php echo base_url("general_cashier/general_cashier/create")?>"  class="btn btn-success"><b><?php echo lang( 'buttons:create' ) ?></b></a>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){		

		
		$( document ).ready(function(e) {
			
			$("#transaction_type").on("change", function(){
				
				if ( $(this).val() == "" )
				{
					$("#evidence_number").val( "" );					
					return false;
				}
				
				var data_post = { "transaction_type" : $(this).val() };
				
				$.get("<?php echo base_url("general_cashier/gen_evidence_number")?>", data_post, function( response, status, xhr ){

					var response = $.parseJSON(response);

					if( response.status == "error"){
						$.alert_error(response.message);
						return false
					}
					
					$("#evidence_number").val( response.evidence_number );					
					
				})					
			});
				
			$("form[name=\"form_general_cashier\"]").on("submit", function(e){
				e.preventDefault();
				
				var debit_data = {};
				var credit_data = {};
				
				var header_data = {
						"evidence_number" : $("#evidence_number").val(),
						"transaction_type" : $("#transaction_type").val(),
						"transaction_date" : $("#transaction_date").val(),
						"description" : $("#description").val(),
						"from_to" : $("#from_to").val(),
						"credit" : Number( $("#total_credit").val().replace(/[^0-9\.]+/g,"") ),
						"debit" : Number( $("#total_debit").val().replace(/[^0-9\.]+/g,"") ),
				};
				
				var _form_gc = $( "form[name=\"form_general_cashier\"]" );
				var _object_data_debit = _form_gc.find( "input[id=\"data_debit\"]" );
				var _object_data_credit = _form_gc.find( "input[id=\"data_credit\"]" );
				
				var retrieve_debit = $("#dt_debit_details").DataTable().rows().data();
				var retrieve_debit_header = _object_data_debit.data("headers");
				var retrieve_debit_detail = _object_data_debit.data("details");

				var retrieve_credit = $("#dt_credit_details").DataTable().rows().data();
				var retrieve_credit_header = _object_data_credit.data("headers");
				var retrieve_credit_detail = _object_data_credit.data("details");
				
				console.log("credit header:", retrieve_credit_header);
				console.log("credit details:", retrieve_credit_detail);
				
				// Retrive data Debit according table data
				retrieve_debit.each(function (v, k) {
					
					v.account_id.toString();
					debit_data[v.account_id] = v;					

					// Retrive data header (inovice or voucher) Debit
					if ( v.integration_source == "AR" || v.integration_source == "AP" )
					{
						debit_data[v.account_id]["headers"] = {};
						$.each(retrieve_debit_header[v.account_id], function ( key, value ) {
							
							header_number = ( v.integration_source == "AP" ) ? value.voucher_number : value.invoice_number;
							
							debit_data[v.account_id]["headers"][header_number] = value;						
							debit_data[v.account_id]["headers"][header_number]["details"] = {};
							
							// Retrive data detail inovice or voucher ( factur data )
							$.each(retrieve_debit_detail[header_number], function ( i, d ) {
								
								debit_data[v.account_id]["headers"][header_number]["details"][d.factur_number] = d;						
							
							});
							
							
						});
					
					} 
					
				});

				// Retrive data Credit according datatable
				retrieve_credit.each(function (v, k) {

					v.account_id.toString();
					credit_data[v.account_id] = v;					
					
					// Retrive data header (inovice or voucher) Credit
					if ( v.integration_source == "AR" || v.integration_source == "AP" )
					{
						credit_data[v.account_id]["headers"] = {};
						$.each(retrieve_credit_header[v.account_id], function ( key, value ) {
							
							header_number = ( v.integration_source == "AP" ) ? value.voucher_number : value.invoice_number;
							
							credit_data[v.account_id]["headers"][header_number] = value;						
							credit_data[v.account_id]["headers"][header_number]["details"] = {};
							
							// Retrive data detail inovice or voucher ( factur data ) Credit
							$.each(retrieve_credit_detail[header_number], function ( i, d ) {
								
								credit_data[v.account_id]["headers"][header_number]["details"][d.factur_number] = d;						
							
							});
							
							
						});
					
					} 
					
				});				
				
				console.log("debit :", debit_data);
				console.log("credit :", credit_data);
				console.log("header :", header_data);
				
				/*
				header_data = JSON.parse(JSON.stringify(header_data));
				credit_data = JSON.parse(JSON.stringify(credit_data));
				debit_data = JSON.parse(JSON.stringify(debit_data));*/
				
				$.post($(this).attr("action"), { header_data, debit_data, credit_data }, function( response, status, xhr ){

					var response = $.parseJSON(response);

					if( response.status == "error"){
						$.alert_error(response.message);
						return false
					}
					
					$.alert_success("<?php echo lang('global:created_successfully')?>");
					
					var id = response.id;
					
					setTimeout(function(){
												
						<?php /*?>document.location.href = "<?php echo base_url("payable/general_cashier/edit"); ?>/"+ id ;<?php */?>
						
					}, 3000 );
					
				})	
				
			});
							
		});
	})( jQuery );
//]]>
</script>
