<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( base_url("general-ledger/general/print_journal_transactions") )?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('general:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:from_date_label')?></label>
					<div class="col-md-9 input-group">
						<input type="text" id="date_start" name="f[date_start]" value="<?php echo date("Y-m-d") ?>" data-date-min-date="<?php echo config_item('Tanggal Mulai System') ?>" class="form-control datepicker" />
						<div class="input-group-addon"><?php echo lang('general_ledger:till_date_label')?></div>
						<input type="text" id="date_till" name="f[date_till]" value="<?php echo date("Y-m-d") ?>" class="form-control datepicker"   />
					</div>
				</div>    
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:currency_label')?></label>
					<div class="col-md-9">
						<select id="Currency_ID" name="f[Currency_ID]" class="form-control" required>
							<option value="0"><?php echo lang("global:select-all")?></option>
							<?php if (!empty($currencies)) : foreach($currencies as $k => $v) : ?>
							<option value="<?php echo @$k ?>" > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>    
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:journal_type_label')?></label>
					<div class="col-md-9">
						<select id="journal_type" name="f[journal_type]" class="form-control">
							<?php if (!empty($journal_type)) : foreach($journal_type as $row) : ?>
							<option value="<?php echo @$row ?>" > <?php echo @$row ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>    			
				<div class="form-group">
					<label class="col-md-3 control-label">&nbsp;</label>
					<div class="col-md-9">
						<a href="javascript:;" id="search_transaction" class="btn btn-success btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></a>	
					</div>
				</div>				
			</div>
		
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:debit_label') ?></label>
					<div class="col-md-9">
						<input type="text" id="debit" name="f[debit]" value="" placeholder="" class="form-control text-right" readonly>
					</div>
				</div>    
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:credit_label') ?></label>
					<div class="col-md-9">
						<input type="text" id="credit" name="f[debit]" value="" placeholder="" class="form-control text-right" readonly>
					</div>
				</div>    
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:balance_label') ?></label>
					<div class="col-md-9">
						<input type="text" id="balance" name="f[balance]" value="" placeholder="" class="form-control text-right" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">&nbsp;</label>
					<div class="col-md-9">
						<button type="submit" formtarget="_blank" id="print_journal_transactions" class="btn btn-primary btn-block" ><b><i class="fa fa-print"></i> <?php echo lang( 'buttons:print' ) ?></b></button>
					</div>
				</div> 
			</div>
		</div>
		
		<?php echo  modules::run("general_ledger/general/details") ?>
	</div>
</div>
		
<?php echo form_close() ?>