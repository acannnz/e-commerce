<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<script type="text/javascript">
	//<![CDATA[
	function lookupbox_row_selected(response) {
		var _response = JSON.parse(response);
		if (_response) {
			try {
				$('#tglEstRujuk').val($('#lookupTglEstRujuk').val());
				$('#kdppk').val(_response.kdppk);
				$('#KeteranganReferal').val(_response.nmppk)
				$('#PoliReferal').val(_response.poli)

				// clear all, before update
				$('#khusus').val(0);
				$('#kdKhusus').val('');
				$('#kdSubSpesialis').val('');
				$('#catatan').val('');
				$('#spesialis').val(0);
				$('#kdSubSpesialis1').val('');
				$('#kdSarana').val('');

				var referralCondition = $('input[name="BPJSRujukan"]:checked').val();
				if (referralCondition == 'BPJSRujukanKhusus') {
					$('#khusus').val(1);
					$('#kdKhusus').val($('#lookupKdKhusus').val());
					$('#kdSubSpesialis').val($('#lookupKdKhususSubSpesialis').val());
					$('#catatan').val($('#lookupCatatan').val());
				} else if (referralCondition == 'BPJSRujukanSpesialis') {
					$('#spesialis').val(1);
					$('#kdSpesialis').val($('#lookupKdSpesialis1').val());
					$('#kdSubSpesialis1').val($('#lookupKdSubSpesialis1').val());
					var sarana = $('#isSarana').is(':checked') ? $("#lookupkdSarana").val() : 0;
					$('#kdSarana').val(sarana);
				}

				$("#btn-close-lookup-faskes").trigger('click');

			} catch (e) {
				console.log(e);
			}
		}
	}
	//]]>
</script>
<div class="modal-dialog modal-xlg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" id="btn-close-lookup-faskes" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Pencarian Fasilitas Kesehatan Rujuk Lanjut</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="col-md-2">
							<div class="radio">
								<input type="radio" id="BPJSRujukanKhusus" name="BPJSRujukan" data-target="#optionSpecial" value="BPJSRujukanKhusus"><label for="BPJSRujukanKhusus"><?= lang('label:special_condition') ?></label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="radio">
								<input type="radio" id="BPJSRujukanSpesialis" name="BPJSRujukan" data-target="#optionSpecialist" value="BPJSRujukanSpesialis"><label for="BPJSRujukanSpesialis"><?= lang('label:specialist') ?></label>
							</div>
						</div>
					</div>
					<div id="optionSpecial" class="form-group displayOption" style="display:none">
						<div class="col-md-6">
							<div class="row">
								<label class="control-label"><?= lang('label:category') ?></label>
								<div class="input-group">
									<select id="lookupKdKhusus" name="lookupKdKhusus" class="form-control">
										<option>Loading...</option>
									</select>
									<span class="input-group-addon">&nbsp;</span>
									<select id="lookupKdKhususSubSpesialis" name="lookupKdKhususSubSpesialis" class="form-control">
										<option>-NONE-</option>
									</select>
								</div>
							</div>
							<div class="row">
								<label class="control-label"><?= lang('label:reason') ?></label>
								<input id="lookupCatatan" name="lookupCatatan" class="form-control">
							</div>
						</div>
					</div>
					<div id="optionSpecialist" class="form-group displayOption" style="display:none">
						<div class="col-md-6">
							<div class="row">
								<label class="control-label"><?= lang('label:specialist') ?></label>
								<div class="input-group">
									<select id="lookupKdSpesialis1" name="lookupKdSpesialis1" class="form-control">
										<option>Loading...</option>
									</select>
									<span class="input-group-addon">&nbsp;</span>
									<select id="lookupKdSubSpesialis1" name="lookupKdSubSpesialis1" class="form-control">
										<option>-NONE-</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 row">
									<div class="checkbox">
										<input type="checkbox" id="isSarana" name="isSarana" value="1" class=""><label for="isSarana"><?= lang('label:means') ?></label>
									</div>
								</div>
								<select id="lookupkdSarana" name="lookupkdSarana" class="form-control">
									<option>-NONE-</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<div class="row">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="lookupTglEstRujuk" id="lookupTglEstRujuk" class="form-control datepicker" placeholder="<?= lang('label:dateof_visit_plan') ?>">
									<div class="input-group-btn">
										<button type="button" id="btn-search-faskes" class="btn btn-primary"><i class="fa fa-search"></i> <?= lang('buttons:search') ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-faskes" class="table table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo lang('label:faskes') ?></th>
							<th><?php echo lang('label:class') ?></th>
							<th><?php echo lang('label:branch_office') ?></th>
							<th><?php echo lang('label:address') ?></th>
							<th><?php echo lang('label:phone') ?></th>
							<th><?php echo lang('label:distance') ?></th>
							<th><?php echo lang('label:total_referral') ?></th>
							<th><?php echo lang('label:capacity') ?></th>
							<th><?php echo lang('label:schedule') ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<?php ?>
		</div>
	</div>
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		$.fn.extend({
			dataTableInit: function() {
				var _this = this;

				if ($.fn.DataTable.isDataTable(_this.attr("id"))) {
					return _this
				}

				var _datatable = _this.DataTable({
					dom: 'tip',
					processing: true,
					serverSide: false,
					paginate: true,
					ordering: false,
					lengthMenu: [10],
					searching: true,
					info: true,
					responsive: true,
					scrollCollapse: true,
					deferLoading: 0, // disable ajax call on initialize table
					columns: [{
							data: "kdppk",
							className: "actions",
							orderable: false,
							searchable: false,
							width: "20px",
							render: function(val, type, row) {
								var sel = document.getElementById("lookupKdSpesialis1");
								var text = sel.options[sel.selectedIndex].text;

								var sel2 = document.getElementById("lookupKdKhusus");
								var text2 = sel2.options[sel2.selectedIndex].text;

								row.poli = text || text2;

								var json = JSON.stringify({
									kdppk: row.kdppk,
									nmppk: row.nmppk,
									poli: row.poli
								}).replace(/"/g, '\\"');
								return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){console.log(e)}' title=\"<?php echo lang("buttons:select") ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang("buttons:select") ?></span></a>";
							}
						},
						{
							data: "nmppk",
							width: "200px",
						},
						{
							data: "kelas",
							className: 'text-center'
						},
						{
							data: "nmkc",
							width: "150px",
						},
						{
							data: "alamatPpk",
							width: "200px",
						},
						{
							data: "telpPpk",
							width: "100px",
							className: 'text-center'
						},
						{
							data: "distance",
							width: "80px",
							className: 'text-right',
							render: function(val) {
								var distance = (~~val) / 1000;
								return distance.toFixed(2) + ' KM'
							}
						},
						{
							data: "jmlRujuk",
							width: "100px",
							className: 'text-center',
							render: function(val, type, row) {
								return val + ' (' + row.persentase + '%)'
							}
						},
						{
							data: "kapasitas",
							width: "100px",
							className: 'text-center'
						},
						{
							data: "jadwal",
							width: "150px",
							className: 'text-center'
						},
					]
				});

				return _this
			}
		});

		var _datatable = $("#dt-lookup-faskes").dataTableInit();

		var timer = 0;

		$("#btn-search-faskes").on("click", function(e) {
			$.alert_warning('Sedang Mencari Faskes!!');
			e.preventDefault();

			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(searchFaskes, 400);

		});

		function searchFaskes() {

			// Khusus:
			// spesialis/faskes/khusus/{KdKhusus:?}/kartu/{NoKartu:?}/tanggal/{TglRujuk:?}
			// Khusus Spesialis:
			// spesialis/faskes/khusus/{KdKhusus:?}/subspesialis/{KdSubspesialis:?}/kartu/{NoKartu:?}/tanggal/{TglRujuk:?}
			// Spesialis:
			// spesialis/faskes/subspesialis/{KdSubspesialis:?}/sarana/{KdSarana:0}/tanggal/{TglRujuk:?} 

			var NoKartu = $('#NoAnggota').val();
			var url = '';
			switch ($('input[name="BPJSRujukan"]:checked').val()) {
				case 'BPJSRujukanKhusus':
					if ($("#lookupKdKhusus").val() == "THA" || $("#lookupKdKhusus").val() == "HEM") {
						url = 'spesialis/faskes/khusus/' + $("#lookupKdKhusus").val() + '/subspesialis/' + $("#lookupKdKhususSubSpesialis").val() + '/kartu/' + NoKartu + '/tanggal/' + $('#lookupTglEstRujuk').val();
					} else {
						url = 'spesialis/faskes/khusus/' + $("#lookupKdKhusus").val() + '/kartu/' + NoKartu + '/tanggal/' + $('#lookupTglEstRujuk').val();
					}
					break
				case 'BPJSRujukanSpesialis':
					var sarana = $('#isSarana').is(':checked') ? $("#lookupkdSarana").val() : 0;
					url = 'spesialis/faskes/subspesialis/' + $("#lookupKdSubSpesialis1").val() + '/sarana/' + sarana + '/tanggal/' + $('#lookupTglEstRujuk').val();
					break
			}

			_datatable.DataTable().clear();

			$.ajax({
				url: "<?= config_item('bpjs_api_baseurl') ?>/" + url,
				type: "GET",
				dataType: "JSON",
				beforeSend: function(request) {
					request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
				},
				success: function(data) {
					if (data.found == 0) {
						$.alert_error('Tidak Terdapat Data Pada Tanggal Tersebut!!');
					} else {
						$.alert_success('Pencarian Faskes Berhasil!!');
						_datatable.DataTable().rows.add(data.collection).draw()
					}
				},
			});
		}

		var _form = {
			init: function() {
				_form.getKhususCollection();
				_form.getSpesialis1Collection();

				$('#lookupTglEstRujuk').datetimepicker({
					format: "DD-MM-YYYY"
				});

				$('input[name="BPJSRujukan"]').on('change', function() {
					var target = $(this).data('target');

					$(".displayOption").hide();
					$(target).show();
					$('#dt-lookup-faskes').DataTable().clear().draw();
				});

				$('#lookupKdKhusus').on('change', function(e) {
					var value = $(this).val();

					if (value == "THA" || value == "HEM") {
						$('#lookupKdKhususSubSpesialis').html('<option value="">Loading...</option>');
						_form.getKhususSubSpesialistCollection();
					} else {
						$('#lookupKdKhususSubSpesialis').html('<option value="">--NONE--</option>');
					}
				});

				$('#lookupKdSpesialis1').on('change', function(e) {
					$('#lookupKdSubSpesialis1').html('<option value="">Loading...</option>');
					kdSpesialis = $(this).val();
					_form.getSubSpesialis1Collection(kdSpesialis);
				});

				$("#isSarana").on("change", function(e) {
					if ($(this).is(":checked")) {
						$('#lookupkdSarana').html('<option value="">Loading...</option>');
						_form.getSaranaCollection();
					} else {
						$('#lookupkdSarana').html('');
					}
				});
			},
			getKhususCollection: function() {
				var _option = '<option value="">Pilih Kondisi Khusus</option>';

				$.ajax({
					url: "<?php echo config_item('bpjs_api_baseurl') . "/spesialis/khusus" ?>",
					type: "GET",
					dataType: "JSON",
					beforeSend: function(request) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(data) {
					$.each(data.collection, function(index, value) {
						_option += '<option value="' + value.kdKhusus + '">' + value.nmKhusus + '</option>';
					});

					$('#lookupKdKhusus').html(_option);
				});
			},
			getKhususSubSpesialistCollection: function() {
				var _option = '';
				var optionCollection = [{
						kdSubSpesialis: "3",
						nmSubSpesialis: "PENYAKIT DALAM"
					},
					{
						kdSubSpesialis: "8",
						nmSubSpesialis: "HEMATOLOGI - ONKOLOGI MEDIK"
					},
					{
						kdSubSpesialis: "26",
						nmSubSpesialis: "ANAK"
					},
					{
						kdSubSpesialis: "30",
						nmSubSpesialis: "ANAK HEMATOLOGI ONKOLOGI"
					}
				];

				$.each(optionCollection, function(index, value) {
					_option += '<option value="' + value.kdSubSpesialis + '">' + value.nmSubSpesialis + '</option>';
				});
				$("#lookupKdKhususSubSpesialis").html(_option);
			},
			getSpesialis1Collection: function() {
				var _option = '<option value="">Pilih Spesialis</option>';
				$.ajax({
					url: "<?php echo config_item('bpjs_api_baseurl') . "/spesialis" ?>",
					type: "GET",
					dataType: "JSON",
					beforeSend: function(request) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(data) {
					$.each(data.collection, function(index, value) {
						_option += '<option value="' + value.kdSpesialis + '">' + value.nmSpesialis + '</option>';
					});

					$('#lookupKdSpesialis1').html(_option);
				});
			},
			getSubSpesialis1Collection: function(kdSpesialis1) {
				var _option = '';
				$.ajax({
					url: "<?php echo config_item('bpjs_api_baseurl') . "/spesialis/subspesialis" ?>/" + kdSpesialis1,
					type: "GET",
					dataType: "JSON",
					beforeSend: function(request) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(data) {
					$.each(data.collection, function(index, value) {
						_option += '<option value="' + value.kdSubSpesialis + '">' + value.nmSubSpesialis + '</option>';
					});

					$('#lookupKdSubSpesialis1').html(_option);
				});
			},
			getSaranaCollection: function() {
				var _option = '';
				$.ajax({
					url: "<?php echo config_item('bpjs_api_baseurl') . "/spesialis/sarana" ?>",
					type: "GET",
					dataType: "JSON",
					beforeSend: function(request) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(data) {
					$.each(data.collection, function(index, value) {
						_option += '<option value="' + value.kdSarana + '">' + value.nmSarana + '</option>';
					});

					$('#lookupkdSarana').html(_option);
				});
			},
		}

		$(document).ready(function(e) {
			_form.init();
		});

	})(jQuery);
	//]]>
</script>