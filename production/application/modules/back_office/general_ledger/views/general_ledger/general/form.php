<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( base_url("general-ledger/general/export_general_ledger") )?>
<div class="row">
	<div class="col-md-3">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('general:from_date_label')?></label>
            <div class="col-md-8">
                <input type="text" id="date_start" name="date_start" value="<?php echo date("Y-m-d") ?>" data-date-min-date="<?php echo config_item('Tanggal Mulai System') ?>" class="form-control datepicker" />
            </div>
        </div>    
    </div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('general:till_date_label')?></label>
            <div class="col-md-8">
                <input type="text" id="date_till" name="date_till" value="<?php echo date("Y-m-d") ?>" class="form-control datepicker"   />
            </div>
        </div>    
    </div>
</div>

<div class="row">
	<div class="col-md-3">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('general:currency_label')?></label>
            <div class="col-md-8">
                <select id="Currency_ID" name="f[Currency_ID]" class="form-control" required>
                	<option value="0"><?php echo lang("global:select-all")?></option>
                    <?php if (!empty($currencies)) : foreach($currencies as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>    
    </div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('general:journal_type_label')?></label>
            <div class="col-md-8">
                <select id="journal_type" name="f[journal_type]" class="form-control">
                    <?php if (!empty($journal_type)) : foreach($journal_type as $row) : ?>
                    <option value="<?php echo @$row ?>" > <?php echo @$row ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>    
    </div>
    <div class="col-md-4">
    	<div class="form-group">
        	<a href="javascript:;" id="search_transaction" class="btn btn-success"><b><?php echo lang("buttons:refresh")?></b></a>
        </div>
    </div>
</div>

<?php echo  modules::run("general_ledger/general/details") ?>

<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-md-2 control-label"><?php echo lang('general:debit_label') ?></label>
            <div class="col-md-10">
                <input type="text" id="debit" name="f[debit]" value="" placeholder="" class="form-control text-right" readonly>
            </div>
        </div>    
	</div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-md-2 control-label"><?php echo lang('general:credit_label') ?></label>
            <div class="col-md-10">
                <input type="text" id="credit" name="f[debit]" value="" placeholder="" class="form-control text-right" readonly>
            </div>
        </div>    
	</div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-md-2 control-label"><?php echo lang('general:balance_label') ?></label>
            <div class="col-md-10">
                <input type="text" id="balance" name="f[balance]" value="" placeholder="" class="form-control text-right" readonly>
            </div>
        </div>    
	</div>
</div>

<div class="form-group">
    <div class="col-lg-12 text-right">
    	<button type="submit" formtarget="_blank" id="print_journal_transactions" class="btn btn-primary" ><?php echo lang( 'buttons:print' ) ?></a>
    </div>
</div>