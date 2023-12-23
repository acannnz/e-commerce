<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__create", 
		"name" => "form_crud__create", 
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label(lang('label:date').' *', 'input_date', ['class' => 'control-label']) ?>
        <?php echo form_input('f[Tgl_Permintaan]', set_value('f[Tgl_Permintaan]', '', TRUE), [
				'id' => 'input_date', 
				'placeholder' => '', 
				'required' => 'required',
				'class' => 'form-control datepicker'
			]); ?>
    </div>
    <div class="form-group">
		<?php echo form_label(lang('label:request_number').' *', 'input_request_number', ['class' => 'control-label']) ?>
        <?php echo form_input('f[No_Permintaan]', set_value('f[No_Permintaan]', '', TRUE), [
				'id' => 'input_request_number', 
				'placeholder' => '',
				'required' => 'required', 
				'class' => 'form-control'
			]); ?>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo form_label(lang('label:warehouse').' *', 'input_section_id', ['class' => 'control-label']) ?>
			<?php echo form_dropdown('f[Gudang_ID]', $dropdown_section, '', [
                    'id' => 'input_section_id', 
                    'placeholder' => '',
                    'required' => 'required', 
                    'class' => 'form-control'
                ]); ?>
        </div>
        <div class="form-group col-md-6">
            <?php echo form_label(lang('label:date_needed').' *', 'input_date_request', ['class' => 'control-label']) ?>
        <?php echo form_input('f[Tgl_Dibutuhkan]', set_value('f[Tgl_Dibutuhkan]', '', TRUE), [
				'id' => 'input_date_request', 
				'placeholder' => '', 
				'required' => 'required',
				'class' => 'form-control datepicker'
			]); ?>
        </div>
        <div class="form-group col-md-6">
            <?php echo form_label(lang('label:procurement_type').' *', 'input_procurement_type', ['class' => 'control-label']) ?>
			<?php echo form_dropdown('f[JenisPengadaanID]', $dropdown_procurement, '', [
                    'id' => 'input_procurement_type', 
                    'placeholder' => '',
                    'required' => 'required', 
                    'class' => 'form-control'
                ]); ?>
        </div>
    </div>
    <div class="form-group col-md-12">
    	<?php echo modules::run("inventory/transactions/purchase_request/table_item", @$item ) ?>
    </div>
</div>
<div class="modal-footer">
    <?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'button',
			'content' => '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('button:cancel'),
			'class' => 'btn btn-default',
			'data-dismiss' => 'modal'
		]); ?>
	<?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'submit',
			'content' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . lang('button:submit'),
			'class' => 'btn btn-primary'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		
	})( jQuery );
//]]>
</script>
