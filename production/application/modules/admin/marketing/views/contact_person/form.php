<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_contact_person', 
		'name' => 'form_contact_person', 
		'rule' => 'form', 
		'class' => ''
	]); ?>


<div class="panel-body table-responsive">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="form-group">
				<?php echo form_label(lang('label:name'), 'Nama', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[Nama]', set_value('c[Nama]', @$item->Nama, TRUE), [
							'id' => 'Nama', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:address'), 'Alamat', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_textarea([
							'name' => 'c[Alamat]', 
							'value' => set_value('c[Alamat]', @$item->Alamat, TRUE),
							'id' => 'Alamat', 
							'placeholder' => '',
							'rows' => 2,
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:office_phone'), 'No_Telepon_Kantor', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[No_Telepon_Kantor]', set_value('c[No_Telepon_Kantor]', @$item->No_Telepon_Kantor, TRUE), [
							'id' => 'No_Telepon_Kantor', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:house_phone'), 'No_Telepon_Rumah', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[No_Telepon_Rumah]', set_value('c[No_Telepon_Rumah]', @$item->No_Telepon_Rumah, TRUE), [
							'id' => 'No_Telepon_Rumah', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:mobile'), 'No_Handphone', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[No_Handphone]', set_value('c[No_Handphone]', @$item->No_Handphone, TRUE), [
							'id' => 'No_Handphone', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:fax'), 'No_Fax', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[No_Fax]', set_value('c[No_Fax]', @$item->No_Fax, TRUE), [
							'id' => 'No_Fax', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:email'), 'Alamat_Email', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[Alamat_Email]', set_value('c[Alamat_Email]', @$item->Alamat_Email, TRUE), [
							'id' => 'Alamat_Email', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:birth_date'), 'Tgl_Lahir', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[Tgl_Lahir]', set_value('c[Tgl_Lahir]', @$item->Tgl_Lahir, TRUE), [
							'id' => 'Tgl_Lahir', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:position'), 'Jabatan', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[Jabatan]', set_value('c[Jabatan]', @$item->Jabatan, TRUE), [
							'id' => 'Jabatan', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:note'), 'Keterangan', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_textarea([
							'name' => 'c[Keterangan]', 
							'value' => set_value('c[Keterangan]', @$item->Keterangan, TRUE),
							'id' => 'Keterangan', 
							'placeholder' => '',
							'rows' => 2,
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group text-right">
				<button id="js-btn-submit" type="button" class="btn btn-primary" data-dismiss="modal"><?php echo lang( 'buttons:save' ) ?></button>
				<button class="btn btn-default" type="button" data-dismiss="modal"><?php echo lang( 'buttons:close' ) ?></button> 
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _row_index = '<?php echo $row_index ?>';
		var _contact_person_data = $("#dt_contact_person").DataTable().row( _row_index ).data();
		var _form = $("#form_contact_person");
		var _form_actions = {
				init: function(){
						
					if( _row_index == ''){return;}
					
					$.each(_contact_person_data, function(key, val){
						_form.find('#'+ key ).val( val );
					});
					
					
				}
			}
				
		$( document ).ready(function(e) {
				
				_form_actions.init();
					
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var contact_person_data = {
						Nama : _form.find('input[name="c[Nama]"]').val(),
						Alamat : _form.find('textarea[name="c[Alamat]"]').val(),
						No_Telepon_Kantor : _form.find('input[name="c[No_Telepon_Kantor]"]').val(),
						No_Telepon_Rumah : _form.find('input[name="c[No_Telepon_Rumah]"]').val(),
						No_Handphone : _form.find('input[name="c[No_Handphone]"]').val(),
						Fax : _form.find('input[name="c[Fax]"]').val(),
						Alamat_Email : _form.find('input[name="c[Alamat_Email]"]').val(),
						Tgl_Lahir : _form.find('input[name="c[Tgl_Lahir]"]').val(),
						Jabatan : _form.find('input[name="c[Jabatan]"]').val(),
						Keterangan : _form.find('textarea[name="c[Keterangan]"]').val(),
					}
					
					if(_row_index == ''){
						$("#dt_contact_person").DataTable().row.add( contact_person_data ).draw();
					}else {
						$.each(contact_person_data, function(key, val){
							_contact_person_data[key] = val;
						});
						
						$("#dt_contact_person").DataTable().row( _row_index ).data( _contact_person_data ).draw();
					}
					
					$("#ajaxModal").modal("hide");
				});

			});

	})( jQuery );
//]]>
</script>
