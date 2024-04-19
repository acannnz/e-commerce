<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('drug_payment:list_subtitle') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">	
            <ul id="tab-poly" class="nav nav-tabs nav-justified">
                <li class="active"><a href="#payment-tab1" data-toggle="tab"><i class="fa fa-refresh"></i> Belum Bayar</a></li>
                <li><a href="#payment-tab2" data-toggle="tab"><i class="fa fa-check"></i> Sudah Bayar</a></li>
            </ul>
            <div class="tab-content">
                <div id="payment-tab1" class="tab-pane tab-pane-padding active">
                	<?php $this->load->view('drug_payment/datatable/open', (isset($data) ? $data : NULL)); ?>
                </div>
                <div id="payment-tab2" class="tab-pane tab-pane-padding ">
                	<?php $this->load->view('drug_payment/datatable/close', (isset($data) ? $data : NULL)); ?>
                </div>
            </div>
        </div>
    </div>
</div>
