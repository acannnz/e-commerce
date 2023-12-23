<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url()); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="modal-title">Regional</h3>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
							<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
							<div class="input-group-btn">
								<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-regional" class="table table-bordered table-hover table-icd" width="100%">
					<thead>
						<tr>
							<th align-text="center"><i class="fa fa-cog"></i></th>
							<th><?php echo 'Kabupaten' ?></th>
							<th><?php echo 'Kecamatan' ?></th>
							<th><?php echo 'Desa' ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<?php echo lang('patients:referrer_lookup_helper') ?>
		</div>
	</div>
</div>
<?php echo form_close() ?>

	<script type="text/javascript">
		//<![CDATA[
		function lookupbox_row_selected(response) {
			var _response = JSON.parse(response)

			if (_response) {
				//console.log(_response);
				try {
					$("#Kabupaten").val(_response.KabupatenNama);
					$("#Kecamatan").val(_response.KecamatanNama);
					$("#Desa").val(_response.DesaNama);
					$("#KodeRegional").val(_response.DesaId);

					$("[data-dismiss=modal]").trigger({
						type: "click"
					});
					lookup_ajax_modal.hide();
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();

				} catch (e) {
					console.log();
				}

			}
		}


		(function($) {
			$.fn.extend({
				DT_Lookup_Regional: function() {
					var _this = this;

					if ($.fn.DataTable.isDataTable(_this.attr("id"))) {
						return _this
					}

					var _datatable = _this.DataTable({
						dom:'tip',
						processing: true,
						serverSide: false,
						paginate: true,
						ordering: true,
						searching: true,
						info: true,
						responsive: true,
						scrollCollapse: true,
						ajax: {
							url: "<?php echo base_url("common/patients/datatable_regional_collection") ?>",
							type: "POST",
							data: function(params) {
								params.provinsi = $("#Provinsi").val();
							}
						},
						columns: [{
								data: "DesaNama",
								className: "actions text-center",
								orderable: false,
								searchable: false,
								width: "20px",
								render: function(val, type, row) {
									var data = row;
									var json = JSON.stringify(data).replace(/"/g, '\\"');
									return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang("buttons:apply") ?>\" class=\"btn btn-primary btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang("buttons:apply") ?></span></a>";
								}
							},
							{
								data: "KabupatenNama"
							},
							{
								data: "KecamatanNama",
								render: function(val, type, row) {
									return val
								}
							},
							{
								data: "DesaNama",
								render: function(val, type, row) {
									return val
								}
							}
						]
					});
					$("#dt-lookup-regional_filter input").addClass("form-control");
					return _this
				}
			});

			var _datatable = $("#dt-lookup-regional").DT_Lookup_Regional();
			var timer = 0;
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 300); 
				
			});
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keypress", function(e){
				if ( (e.which || e.keyCode) == 13 ) {
					e.preventDefault();
					return false
				}
			});	
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup change", function(e){
				e.preventDefault();

				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 300); 
				
			});
		
		function searchWord(){
			var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
							_datatable.DataTable().search( words );
			_datatable.DataTable().draw(true);	
		}

		})(jQuery);
		//]]>
	</script>