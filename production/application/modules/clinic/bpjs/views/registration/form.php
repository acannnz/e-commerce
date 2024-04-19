<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<div class="form-group">
	<?php echo form_label(lang('label:code_bpjs'), 'Kode_Supplier_BPJS', ['class' => 'control-label col-md-3']) ?>
	<div class="col-md-9">
		<div class="input-group">
			<?php echo form_input([
				'type' => 'text',
				'name' => 'f[Kode_Supplier_BPJS]',
				'value' => set_value('f[Kode_Supplier_BPJS]', @$mapping->code, TRUE),
				'id' => 'Kode_Supplier_BPJS',
				'class' => 'form-control registration_bpjs',
				'readonly' => 'readonly',
			]); ?>

			<span class="input-group-btn">
				<a href="javascript:;" data-action-url="<?php echo @$lookup_registration_bpjs ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:registration_bpjs')) ?>" data-act="ajax-modal" class="btn btn-default btn-md"><i class="fa fa-search"></i></a>
				<a href="javascript:;" data-target-class="registration_bpjs" class="btn btn-default btn-clear-bpjs"><i class="fa fa-times"></i></a>
			</span>
		</div>
	</div>
</div>

<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var _form_actions = {
			init: function() {
				$('a.btn-clear-bpjs').on('click', function(e) {
					var _target_class = $(this).data('target-class');
					$('.' + _target_class).val('');
				});
			}
		};

		$(document).ready(function(e) {
			_form_actions.init();

		});
	})(jQuery);
	//]]>
</script>