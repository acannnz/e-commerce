<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__create", 
		"name" => "form_crud__create", 
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label('ID Section *', 'input_id', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<?php echo form_input('f[SectionID]', set_value('f[SectionID]', '', TRUE), [
                    'id' => 'input_id', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Section *', 'input_section', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[SectionName]', set_value('f[SectionName]', '', TRUE), [
                    'id' => 'input_section', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Penanggung Jawab *', 'input_pic', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[PenanggungJawab]', set_value('f[PenanggungJawab]', '', TRUE), [
                    'id' => 'input_pic', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Layanan', 'input_service', ['class' => 'col-sm-4 col-xs-12 col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_dropdown('f[TipePelayanan]', $populate_service, set_value('f[TipePelayanan]', '', TRUE), [
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
            <?php echo form_dropdown('f[UnitBisnisID]', $populate_business, set_value('f[UnitBisnisID]', 0, TRUE), [
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
            <?php echo form_dropdown('f[GroupSection]', $populate_report, set_value('f[GroupSection]', '', TRUE), [
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
            <?php echo form_input('f[Customer_ID]', set_value('f[Customer_ID]', '', TRUE), [
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
			'content' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . lang('button:submit'),
			'class' => 'btn btn-primary'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__create"]' );
				_form.find('input[type="checkbox"].checkbox,input[type="radio"].radio').iCheck({
						checkboxClass: 'icheckbox_square-blue',
						radioClass: 'iradio_square-blue',
						increaseArea: '20%'
					});
				
				_form.appForm({
						onSuccess: function(result){
								try{
									$("#dt_ref_section").DataTable().ajax.reload();
								} catch(e){
									location.reload(); 
								}
							}
					});
			});
	})( jQuery );
//]]>
</script>
