<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( base_url("general_ledger/export_general_ledger") )?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('general_ledger:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general_ledger:from_date_label')?></label>
					<div class="col-md-9">
						<div class="input-group">
							<input type="text" id="date_start" name="f[date_start]" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo config_item('Tanggal Mulai System') ?>" class="form-control datepicker" />
							<div class="input-group-addon"><?php echo lang('general_ledger:till_date_label')?></div>
							<input type="text" id="date_till" name="f[date_till]" value="<?php echo date("Y-m-t") ?>" class="form-control datepicker"   />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general_ledger:currency_label')?></label>
					<div class="col-md-3">
						<select id="Currency_ID" name="f[Currency_ID]" class="form-control" required>
							<option value="0"><?php echo lang("global:select-all")?></option>
							<?php if (!empty($currencies)) : foreach($currencies as $k => $v) : ?>
							<option value="<?php echo @$k ?>" > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
					<label class="col-md-3 control-label text-center"><?php echo lang('general_ledger:convert_label')?></label>
					<div class="col-md-3">
						<select id="convertCurrency_ID" name="f[convertCurrency_ID]" class="form-control">
							<option value=""><?php echo lang("global:select-none")?></option>
							<?php if (!empty($currencies)) : foreach($currencies as $k => $v) : ?>
							<option value="<?php echo @$k ?>" <?php echo (@$k == $currency_default ) ? "selected" : null ?> > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general_ledger:account_label')?></label>
					<div class="col-md-9">
						<div class="input-group">
							<input type="hidden" id="Akun_ID" name="f[Akun_ID]" />
							<input type="text" id="Akun_No" name="f[Akun_No]" class="form-control" />
							<div class="input-group-addon">&nbsp;</div>
							<input type="text" id="Akun_Name" name="f[Akun_Name]" class="form-control" />
							<div class="input-group-btn">
								<a href="<?php echo $lookup_accounts ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""><i class="fa fa-gear"></i></a>
							</div>
						</div>						
					</div>    	
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<a href="javascript:;" id="search_transaction" class="btn btn-success btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></a>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="beginning_balance" name="f[beginning_balance]" value="" placeholder="" class="form-control text-right">
								<div class="input-group-addon"><?php echo lang('general_ledger:beginning_balance_label') ?></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-addon"><?php echo lang('general_ledger:ending_balance_label') ?></div>
								<input type="text" id="ending_balance" name="f[ending_balance]" value="" placeholder="" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="debit" name="f[debit]" value="" placeholder="" class="form-control text-right">
								<div class="input-group-addon"><?php echo lang('general_ledger:debit_label') ?></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-addon"><?php echo lang('general_ledger:credit_label') ?></div>
								<input type="text" id="credit" name="f[debit]" value="" placeholder="" class="form-control">
							</div>
						</div>
					</div>
				</div>   
				<div class="form-group">
					<button type="submit" formtarget="_blank" id="print_journal_transactions" class="btn btn-primary btn-block" ><b><i class="fa fa-print"></i> <?php echo lang( 'buttons:print' ) ?></b></button>
				</div>
			</div>
		</div>
		
		<?php echo  modules::run("general_ledger/details") ?>

	</div>
</div>
<?php echo form_close() ?>
