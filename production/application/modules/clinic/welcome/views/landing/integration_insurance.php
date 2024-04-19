<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-xs-12">
    	<div class="row">
        	<div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'bpjs/pcare' ) ?>" class="tile tile-default">
                    <i class="fa fa-handshake-o"></i>
                    <h3><?php echo lang("nav:bpjs_pcare") ?></h3>
                </a>
            </div>
            <div class="col-sm-6 col-xs-12">
                <a href="<?php echo base_url( 'integration/mandiri' ) ?>" class="tile tile-success">
                    <i class="fa fa-handshake-o"></i>
                    <h3><?php echo 'Mandiri In Health' ?></h3>
                </a>
            </div>
        </div>
	</div>
</div>
