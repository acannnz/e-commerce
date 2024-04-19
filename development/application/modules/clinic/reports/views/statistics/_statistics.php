<div class="wrapper">
	<div class="row">
		<div class="col-md-3 col-sm-12">
			<a href="<?= base_url('common/patients')?>" class="tile tile-default">
				<?= numb_format($widget_total_patient, 0) ?> 
				<p>Total Pasien</p>                            
				<div class="informer informer-primary"><span class="fa fa-user-md"></span></div>
			</a>
		</div>
		<div class="col-md-3 col-sm-12">
			<a href="javascript:;" class="tile tile-warning">
				<?= $widget_total_visite ?>
				<p>Kunjungan Hari Ini</p>
				<div class="informer informer-danger"><span class="fa fa-stethoscope"></span></div>
				<div class="informer informer-default dir-tr">21/02/2020</div>
			</a>
		</div>
		<div class="col-md-3 col-sm-12">
			<a href="javascript:;" class="tile tile-danger">
				<?= $widget_total_drug ?>
				<p>Penjualan Obat Hari Ini</p>
				<div class="informer informer-warning"><span class="fa fa-tasks"></span></div>
				<div class="informer informer-default dir-tr">21/02/2020</div>
			</a>
		</div>    
		<div class="col-md-3 col-sm-12">
			<a href="javascript:;" class="tile tile-danger">
				<?= $widget_total_receipt ?>
				<p>Penerimaan Obat Bulan Ini</p>
				<div class="informer informer-warning"><span class="fa fa-tasks"></span></div>
				<div class="informer informer-default dir-tr">21/02/2020</div>
			</a>
		</div>                            
	</div>
	
	<div class="row">
		<div class="col-md-3">
			<div class="form-group"> 
				<div class="input-group">
					<span class="input-group-addon">Tampilkan Per</span>
					<select id="filter-type" class="form-control text-center">
						<option value="month">Bulan</option>
						<option value="year">Tahun</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-3" id="filter-month-area">
			<div class="form-group"> 
				<div class="input-group">
					<span class="input-group-addon">PERIODE</span>
					<input type="text" id="filter-month" data-type="month" class="form-control text-center datepicker" data-date-format="YYYY-MM" value="<?= date('Y-m') ?>">
					
				</div>
			</div>
		</div>
		<div class="col-md-3" id="filter-year-area">
			<div class="form-group"> 
				<div class="input-group">
					<span class="input-group-addon">PERIODE</span>
					<input type="text" id="filter-year" data-type="year" class="form-control text-center datepicker" data-date-format="YYYY" value="<?= date('Y') ?>">
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div id="monthly-section-visit"></div>
		</div>
		<div class="col-md-12"><hr></div>
		<div class="col-md-12">
			<div id="monthly-type-visit"></div>
		</div>
	</div>

</div>
<script>
<?php /*?>var chart_grp = Highcharts.chart('monthly-section-visit', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Jumlah Pendaftaran Pasien'
    },
    subtitle: {
        text: 'Informasi Jumlah Pendaftaran Pasien Per Poli'
    },
    xAxis: {
        categories: ['Rawat Jalan', 'Rawat Inap'],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Pasien'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y} Pasien</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
	series: JSON.parse('<?= json_encode(array_values(@$graph_registered_patient), JSON_NUMERIC_CHECK)?>'),
});


      
      
	Highcharts.chart('monthly-type-patient', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Status Pemeriksaan Pasien Hari Ini'
    },
    subtitle: {
        text: 'Informasi data pasien belum Periksa &amp; sudah Periksa'
    },
    xAxis: {
        categories: ['Belum Periksa', 'Sudah Periksa'],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Pasien'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y} Pasien</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
	series: JSON.parse('<?= json_encode(array_values(@$graph_patient_state), JSON_NUMERIC_CHECK)?>'),
});<?php */?>
</script>

<script type="text/javascript">
( function($) {

	$(document).ready(function(e) {
		
		$('#monthly-section-visit').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: 'Statistik Kunjungan Pasien per Poli'
			},
			subtitle: {
				text: 'Kunjungan per Bulan'
			},
			xAxis: {
				categories: <?php print_r(json_encode($monthly_section_visit['categories']))?>,
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Jumlah Kunjungan'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">Tanggal {point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.0f} Pasien</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: <?= json_encode($monthly_section_visit['series'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?>,
		});
		
		
		$('#monthly-type-visit').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: 'Statistik Kunjungan Pasien per Tipe'
			},
			subtitle: {
				text: 'Kunjungan per Bulan'
			},
			xAxis: {
				categories: <?php print_r(json_encode($monthly_type_visit['categories']))?>,
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Jumlah Kunjungan'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">Tanggal {point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.0f} Pasien</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: <?= json_encode($monthly_type_visit['series'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?>,
		});
		
		$('#filter-year-area').hide();
		$('#filter-type').on('change', function(ev) {
			switch($(this).val()){
				case 'month':	
					$('#filter-month-area').show();
					$('#filter-year-area').hide();
					break;
				case 'year':	
					$('#filter-year-area').show();
					$('#filter-month-area').hide();
					break;
			}
		});
			
		$('#filter-month, #filter-year').on('dp.hide', function(ev) {
			
			params = {type: $(this).data('type'), date: $(this).val() };

			$.get('<?= site_url("{$nameroutes}/get_monthly_section_visit") ?>', params, function( response, status, xhr ){
				var monthChart = $('#monthly-section-visit').highcharts();
				
				 for (var i = monthChart.series.length-1; i>=0; i--) {
					monthChart.series[i].remove();
				}
				
				$.each(response.series, function(i, v){
					monthChart.addSeries(response.series[i]);
				});							
				
				monthChart.redraw();
			});		
			
			$.get('<?= site_url("{$nameroutes}/get_monthly_type_visit") ?>', params, function( response, status, xhr ){
				var typeChart = $('#monthly-type-visit').highcharts();
				
				 for (var i = typeChart.series.length-1; i>=0; i--) {
					typeChart.series[i].remove();
				}
				
				$.each(response.series, function(i, v){
					typeChart.addSeries(response.series[i]);
				});							
				
				typeChart.redraw();
			});					
		});
		
	});
	})( jQuery );
</script>
