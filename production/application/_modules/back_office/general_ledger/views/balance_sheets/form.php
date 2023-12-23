<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form-account", "name" => "form-account") ); ?>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:component_label') ?> <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <select name="f[component]" class="form-control">
                    <option value="1" <?php echo (@$item->component == 1) ? "selected" : null ?> > Neraca </option>
                    <option value="2" <?php echo (@$item->component == 2) ? "selected" : null ?> > Laba - Rugi</option> 
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:group_label') ?></label>
            <div class="col-md-8">
                <select name="parent_ids" class="form-control" required>
                    <option value="0"> No Group</option>
                    <?php if (!empty($account_root)) : foreach($account_root as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" <?php echo (@$k == @$item->parent_ids ) ? "selected" : null ?> > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:sub_group_label') ?></label>
            <div class="col-md-8">
                <select name="f[parent_id]" class="form-control" required>
                    <option value="0"> No Sub Group</option>
                    <?php if (!empty($account_parent)) : foreach($account_parent as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" <?php echo (@$k == @$item->parent_id) ? "selected" : null ?> > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:normal_pos_label') ?> <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <select name="f[normal_pos]" class="form-control">
                    <option value="D" <?php echo (@$item->normal_pos == 'D') ? "selected" : null ?> > Debit </option>
                    <option value="K" <?php echo (@$item->normal_pos == 'K') ? "selected" : null ?> > Kredit</option> 
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:level_label') ?></label>
            <div class="col-md-8">
                <select name="f[level]" class="form-control" required>
                    <?php for($i = 1; $i <= 5; $i++) : ?>
                    <option value="<?php echo @$i ?>" <?php echo (@$i == @$item->level ) ? "selected" : null ?> > <?php echo @$i ?></option> 
                    <?php endfor; ?>
                </select>
            </div>
        </div>
	</div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:account_number_label')?></label>
            <div class="col-md-8">
                <input type="text" id="account_number" name="f[account_number]" value="<?php echo @$item->account_number ?>" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:account_name_label')?></label>
            <div class="col-md-8">
                <input type="text" id="account_name" name="f[account_name]" value="<?php echo @$item->account_name ?>" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:currency_label') ?></label>
            <div class="col-md-8">
                <select name="f[currency_code]" class="form-control" required>
                    <?php if (!empty($currencies)) : foreach($currencies as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" <?php echo (@$k == @$item->currency_code) ? "selected" : null ?> > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group row">
        	<div class="col-md-6">
                <label class="col-md-8 control-label"><?php echo lang('accounts:integration_label')?></label>
                <div class="col-md-4">
                    <label class="switch">
                        <input type="hidden" value="0" name="f[integration]" />
                        <input type="checkbox" <?php if(@$item->integration == 1){ echo "checked=\"checked\""; } ?> name="f[integration]" value="1">
                        <span></span>
                    </label>
                </div>
			</div>
            <div class="col-md-6">
                <label class="col-md-8 control-label"><?php echo lang('accounts:integration_source_label')?></label>
                <div class="col-md-4">
                    <select name="f[integration_source]" class="form-control">
                        <option value="" ></option>
                        <option value="GC" <?php echo (@$item->integration_source == "GC") ? "selected" : null ?> > GC </option>
                        <option value="AR" <?php echo (@$item->integration_source == "AR") ? "selected" : null ?> > AR</option> 
                        <option value="AP" <?php echo (@$item->integration_source == "AP") ? "selected" : null ?> > AP</option> 
                    </select>
                </div>
        	</div>
        </div>
        <div class="form-group row">
        	<div class="col-md-6">
                <label class="col-md-8 control-label"><?php echo lang('accounts:convert_permanent_label')?></label>
                <div class="col-md-4">
                    <label class="switch">
                        <input type="hidden" value="0" name="f[convert_permanent]" />
                        <input type="checkbox" <?php if(@$item->convert_permanent == 1){ echo "checked=\"checked\""; } ?> name="f[convert_permanent]" value="1">
                        <span></span>
                    </label>
                </div>
			</div>
            <div class="col-md-4">
                <label class="col-md-6 control-label"><?php echo lang('accounting:state')?></label>
                <div class="col-md-6">
                    <label class="switch">
                        <input type="hidden" value="0" name="f[state]" />
                        <input type="checkbox" <?php if(@$item->state == 1){ echo "checked=\"checked\""; } ?> name="f[state]" value="1">
                        <span></span>
                    </label>
                </div>
        	</div>
        </div>
	</div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
    	<a href="javascript:;" id="submit_account" class="btn btn-primary" data-dismiss="modal" ><?php echo lang( 'buttons:submit' ) ?></a>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>