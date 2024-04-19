<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__update", 
		"name" => "form_crud__update",
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label('ID Section *', 'input_id', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<p class="form-control-static"><?php echo @$item->SectionID; ?></p>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Section *', 'input_section', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[SectionName]', set_value('f[SectionName]', @$item->SectionName, TRUE), [
                    'id' => 'input_section', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Penanggung Jawab *', 'input_pic', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[PenanggungJawab]', set_value('f[PenanggungJawab]', @$item->PenanggungJawab, TRUE), [
                    'id' => 'input_pic', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Layanan', 'input_service', ['class' => 'col-sm-4 col-xs-12 col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_dropdown('f[TipePelayanan]', $populate_service, set_value('f[TipePelayanan]', @$item->TipePelayanan, TRUE), [
                    'id' => 'input_service', 
                    'placeholder' => '',
                    'class' => 'form-control',
                    'style' => '' 
                ]); ?>
        </div>
    </div>
    <div class="form-group">
		<?php echo form_label('Unit Bisnis', 'input_business', ['class' => 'col-sm-4 col-xs-12 col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_dropdown('f[UnitBisnisID]', $populate_business, set_value('f[UnitBisnisID]', @$item->UnitBisnisID, TRUE), [
                    'id' => 'input_business', 
                    'placeholder' => '',
                    'class' => 'form-control',
                    'style' => ''
                ]); ?>
        </div>
    </div>
    <div class="form-group">
		<?php echo form_label('Grup Laporan', 'input_group', ['class' => 'col-sm-4 col-xs-12 col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_dropdown('f[GroupSection]', $populate_report, set_value('f[GroupSection]', @$item->GroupSection, TRUE), [
                    'id' => 'input_group', 
                    'placeholder' => '',
                    'class' => 'form-control',
                    'style' => ''
                ]); ?>
        </div>
    </div>
    <br>
    <div class="form-group">
		<?php echo form_label('Pelanggan', 'input_customer', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[Customer_ID]', set_value('f[Customer_ID]', @$item->Customer_ID, TRUE), [
                    'id' => 'input_customer', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <hr>
    <div class="form-group">
		<?php echo form_label('Rekening Debit', 'input_acc_debit', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[PiutangSHAkun_ID]', set_value('f[PiutangSHAkun_ID]', '', TRUE), [
                    'id' => 'input_acc_debit', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Rekening Diskon', 'input_acc_discount', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[DiskonAkun_ID]', set_value('f[DiskonAkun_ID]', '', TRUE), [
                    'id' => 'input_acc_discount', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
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
			'content' => '<i class="fa fa-pencil" aria-hidden="true"></i> ' . lang('button:update'),
			'class' => 'btn btn-success'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__update"]' );
				_form.appForm({onSuccess: function(result){ location.reload(); }});
			});
	})( jQuery );
//]]>
</script>

