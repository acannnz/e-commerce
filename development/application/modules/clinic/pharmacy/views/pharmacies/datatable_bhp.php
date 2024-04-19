<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Daftar Realisasi BHP</h3>
		<!-- <ul class="panel-btn">
			<li><a href="<?php echo base_url("pharmacy/selling") ?>" class="btn btn-info" title="Realisasi Obat"><b><i class="fa fa-plus"></i> Realisasi Obat</a></b></li>
		</ul> -->
	</div>
	<div class="panel-body">
		<ul id="tab-pharmacy" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#pharmacy-tab1" data-toggle="tab"><i class="fa fa-refresh"></i> Belum Realisasi</a></li>
			<li><a href="#pharmacy-tab2" data-toggle="tab"><i class="fa fa-check" aria-hidden="true"></i> Sudah Realisasi</a></li>
		</ul>
		<div class="tab-content">
			<div id="pharmacy-tab1" class="tab-pane tab-pane-padding active">
				<?php echo modules::run("pharmacy/pharmacies/data_open_bhp/index" ) ?>
			</div>
			<div id="pharmacy-tab2" class="tab-pane tab-pane-padding">
				<?php echo modules::run("pharmacy/pharmacies/data_close_bhp/index" ) ?>
			</div>
		</div>
	</div>
</div>