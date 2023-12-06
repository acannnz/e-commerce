<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row">
	<a href="<?= $lookup_form ?>" data-toggle="form-ajax-modal" class="btn btn-primary"><i class="fa fa-search"></i> <?= lang('buttons:search')?></a>
</div>
<div class="table-responsive">
	<table id="dt-mcu" class="table table-bordered table-hover" width="100%">
		<thead>
			<tr>
				<th><?php echo lang('label:code') ?></th>
				<th><?php echo lang('label:code_bpjs') ?></th>
				<th><?php echo lang('label:service') ?></th>
				<th style="text-align:center"><i class="fa fa-gear"></i></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				dataTableMCU: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							processing: false,
							serverSide: false,								
							paginate: true,
							ordering: false,
							lengthChange: false,
							lengthMenu: [5],
							searching: false,
							info: true,
							<?php if (!empty($collection)):?>
							data: <?php print_r(json_encode($collection, JSON_NUMERIC_CHECK));?>,
							<?php endif; ?>
							columns: [
									{data: "JasaID", className: 'text-center'},
									{data: "JasaIDBPJS", className: 'text-center'},
									{data: "JasaName"},
									{data: "JasaID", render: function(val){return '-'}},
								]
						} );
					
					return _this
				}
			});
		
		var _form = {
			init: function(){
				_form.getKhususCollection();
			},
			getSaranaCollection: function(){
				var _option = '';
				$.ajax({
					url: "<?php echo config_item('bpjs_api_baseurl')."/spesialis/sarana" ?>",
					type: "GET",
					dataType: "JSON",
					beforeSend: function (request) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function( data ) {
					$.each(data.collection, function(index, value){
						_option += '<option value="'+ value.kdSarana +'">'+ value.nmSarana +'</option>';
					});
					
					$('#lookupkdSarana').html(_option);
				});
			},
		}
		
		$(document).ready(function(e) {
			//_form.init();
			$( "#dt-mcu" ).dataTableMCU();
		});
		
	})( jQuery );
//]]></script>