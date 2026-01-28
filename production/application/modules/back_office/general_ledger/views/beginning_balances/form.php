<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('beginning_balances:date_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Tanggal" name="Tanggal" value="<?php echo @$beginning_balance_date ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>   
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('beginning_balances:description_label') ?></label>
            <div class="col-lg-9">
                <textarea id="Keterangan" name="Keterangan" value="<?php echo @$keterangan ?>" class="form-control"></textarea>
            </div>
        </div>   
    </div>
	<div class="col-md-6">
        <div class="form-group">
        	<a href="<?php echo $lookup_rate_currency ?>" data-toggle="form-ajax-modal" class="btn btn-block btn-primary"><b><i class="fa fa-book"></i> <?php echo lang("beginning_balances:setup_rate_currency")?></b></a>
        </div>   
    </div>
</div>
<div class="row">
	<div class="col-md-6">
    	<?php echo modules::run("general_ledger/beginning_balance/activa"); ?>
	</div>
	<div class="col-md-6">
    	<?php echo modules::run("general_ledger/beginning_balance/pasiva"); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
    	<h2 id="info_balance" class="text-center text-info"><?php echo lang("beginning_balances:balance_label"); ?></h2>
    </div>
</div>
<div class="row">
	<div class="col-md-6">
    	<a href="javascript:;" type="text" name="activa" id="activa" class="btn btn-danger col-md-12" ></a>
    </div>
	<div class="col-md-6">
    	<a href="javascript:;" type="text" name="pasiva" id="pasiva" class="btn btn-danger col-md-12" > </a>
    </div>
</div>

<div class="row">
	<a href="javascript:;" id="submit" class="btn btn-success pull-right" disabled="disabled"><?php echo lang("buttons:submit"); ?></a>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {
			
				$("a[id=\"submit\"]").on("click", function(e){
					e.preventDefault();	
					_return = false;
					_account_warning = {};
					
					if ( !confirm('<?php echo lang('beginning_balances:proceed_confirm')?>'))
					{
						return false;
					}
					
					var data_post = { 
								"Keterangan": $("#Keterangan").val(),
								"activa": [{ }], 
								"pasiva": [{ }], 
							}, activa = [], pasiva = [];

					var table_activa = $( "#dt-table-activa" ).DataTable().rows().data();
					
					table_activa.each(function (value, index) {
						var activa = {
							"Akun_ID": value.Akun_ID,
							"Nilai"	: value.Nilai,
						}
						
						if ( value.Currency_Code == '' )
						{
							_return = true;
							_account_warning = {
									"account_number" : value.Akun_No,
									"account_name" : value.Akun_Name,
								};
								
							return false;
						}
						
						data_post.activa.push(activa);
					});

					var table_pasiva = $( "#dt-table-pasiva" ).DataTable().rows().data();
					
					table_pasiva.each(function (value, index) {
						var pasiva = {
							"Akun_ID": value.Akun_ID,
							"Nilai"	: value.Nilai,
						}

						if ( value.Currency_Code == '' )
						{
							_return = true;
							_account_warning = {
									"account_number" : value.Akun_No,
									"account_name" : value.Akun_Name,
								};
								
							return false;
						}

						data_post.pasiva.push(pasiva);
					});
					
					if ( _return )
					{
						message = '<?php echo lang("beginning_balances:empty_currency_alert")?>';
						message = message.replace( /%d/g, _account_warning.account_number );
						message = message.replace( /%s/g, _account_warning.account_name );
						alert( message );
						return;
					}
					
					console.log(data_post);
				
					$.post('<?php echo base_url("general-ledger/beginning-balance/create")?>', data_post, function( response, status, xhr ){
						
						var response = $.parseJSON( response );
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);
						
						setTimeout(function(){
													
							//document.location.href = "<?php echo current_url(); ?>/" ;
							
							}, 3000 );
						
					})	
				});
		
			});
	})( jQuery );
//]]>
</script>