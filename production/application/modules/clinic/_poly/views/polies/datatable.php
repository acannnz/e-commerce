<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<!--<div class="row">
        <div class="col-md-6">
            <h3 class="text-info">Daftar Pemeriksaan Poli Umum</h3>
            <p>Pasien Poli Umum akan dikelola mulai dari sini.</p>
        </div>
	</div>-->
</div>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('poly:list_heading') ?></h3>
	</div>
	<div class="panel-body">
		<ul id="tab-poly" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#poly-tab1" data-toggle="tab"><i class="fa fa-refresh"></i> Belum Periksa</a></li>
			<li><a href="#poly-tab2" data-toggle="tab"><i class="fa fa-stethoscope"></i> Sudah Periksa</a></li>
		</ul>
		<div class="tab-content">
			<div id="poly-tab1" class="tab-pane tab-pane-padding active">
				<?php echo Modules::run("{$nameroutes}/polies/data_waiting/index"); ?>
			</div>
			<div id="poly-tab2" class="tab-pane tab-pane-padding">
				<?php echo Modules::run("{$nameroutes}/polies/data_checkup/index"); ?>
			</div>
		</div>
	</div>
</div>