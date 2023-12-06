<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">List Data Pembayaran</h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("cashier/general-payment/pay") ?>" class="btn btn-info" title="Pembayaran Baru"><b><i class="fa fa-plus"></i> Pembayaran Baru</b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<ul id="tab-poly" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#payment-tab1" data-toggle="tab"><i class="fa fa-refresh"></i> Belum Bayar</a></li>
			<li><a href="#payment-tab2" data-toggle="tab"><i class="fa fa-stethoscope"></i> Sudah Bayar</a></li>
		</ul>
		<div class="tab-content">
			<div id="payment-tab1" class="tab-pane tab-pane-padding active">
				<?php echo modules::run("cashier/general-payments/data_open/index" ) ?>
			</div>
			<div id="payment-tab2" class="tab-pane tab-pane-padding">
				<?php echo modules::run("cashier/general-payments/data_close/index" ) ?>
			</div>
		</div>
	</div>
</div>