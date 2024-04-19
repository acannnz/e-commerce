<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__create", 
		"name" => "form_crud__create", 
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label(lang('label:code').' *', 'input_code', ['class' => 'control-label']) ?>
        <?php echo form_input('f[Kode_Kelas]', set_value('f[Kode_Kelas]', '', TRUE), [
				'id' => 'input_code', 
				'placeholder' => '', 
				'required' => 'required',
				'class' => 'form-control'
			]); ?>
    </div>
    <div class="form-group">
		<?php echo form_label(lang('label:class_name').' *', 'input_name', ['class' => 'control-label']) ?>
        <?php echo form_input('f[Nama_Kelas]', set_value('f[Nama_Kelas]', '', TRUE), [
				'id' => 'input_name', 
				'placeholder' => '',
				'required' => 'required', 
				'class' => 'form-control'
			]); ?>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo form_label(lang('label:category').' *', 'input_category', ['class' => 'control-label']) ?>
			<?php echo form_dropdown('ff[Kategori_ID]', [0 => lang('label:category')], set_value('ff[Kategori_ID]', 0, TRUE), [
                    'id' => 'input_category', 
                    'placeholder' => '',
                    //'required' => 'required', 
                    'class' => 'form-control'
                ]); ?>
        </div>
        <div class="form-group col-md-6">
            <?php echo form_label(lang('label:subcategory').' *', 'input_subcategory', ['class' => 'control-label']) ?>
			<?php echo form_dropdown('f[SubKategori_ID]', [0 => lang('label:subcategory')], set_value('f[SubKategori_ID]', 0, TRUE), [
                    'id' => 'input_subcategory', 
                    'placeholder' => '',
                    'required' => 'required', 
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
		var category_id = 0;
		var subcategory_id = 0;
		
		var _form = $( 'form[name="form_crud__create"]' );
		_select_category = _form.find('select[id="input_category"]');
		_select_subcategory = _form.find('select[id="input_subcategory"]');
		
		function load_category_list(fn){
			v = category_id || 0;
			
			_select = _select_category;				
			_select.html('<option value="0"><?php echo lang('ajax:loading'); ?></option>');
			_select.attr('disabled', 'disabled');
			_select.load('<?php echo site_url("{$nameroutes}/get_category_list") ?>',{}, function(response, status){
					_select.removeAttr('disabled');
					_select.val(v);
					if ($.isFunction(fn)){
						setTimeout(function(){
								fn.call(_form, v);
							}, 300);
					}
				});
			
			return _select;
		}
		
		function load_subcategory_list(parent_id){
			parent_id = parent_id || 0;
			v = subcategory_id || 0;
			
			_select = _select_subcategory;				
			_select.html('<option value="0"><?php echo lang('ajax:loading'); ?></option>');
			_select.attr('disabled', 'disabled');
			_select.load('<?php echo site_url("{$nameroutes}/get_subcategory_list") ?>',{'category_id': parent_id}, function(response, status){
					_select.removeAttr('disabled');
					_select.val(v);
				});
			
			return _select;
		}
		
		$( document ).ready(function(){
				_form.appForm({onSuccess: function(result){ 
						try{
							$("#dt_ref_item_class").DataTable().ajax.reload();
						} catch(e){
							location.reload(); 
						}
					}});
				
				_select_category.on('change', function(e){
						e.preventDefault();
						
						var v = $(this).val() || 0;
						category_id = v;
						
						load_subcategory_list(v);
					});
				_select_subcategory.on('change', function(e){
						e.preventDefault();
						
						var v = $(this).val() || 0;
						subcategory_id = v;
					});
					
				setTimeout(function(){
						load_category_list(function(v){
								load_subcategory_list(v);
							});
					}, 300);
			});
	})( jQuery );
//]]>
</script>
