<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/knob/jquery.knob.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>
    

<?php if( isset($form) ): ?>
<?php endif ?>

<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-loaders.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-layout-default.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-app.js"></script>
<?php if( isset($timeout) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-timeout.js"></script>
<?php endif ?>
<?php if( isset($dev_viewport) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-viewport.js"></script>
<?php endif ?>

<?php
if($this->session->flashdata('message')): 
	$message = $this->session->flashdata('message');
	$alert = $this->session->flashdata('response_status');
?>
<script type="text/javascript">//<![CDATA[
(function($){
	$( document ).ready(function(){
			toastr.<?php echo $alert ?>("<?php echo $message ?>", "<?php echo lang('response_status')?>");
			toastr.options = {
					"closeButton": true,
					"debug": false,
					"positionClass": "toast-bottom-right",
					"onclick": null,
					"showDuration": "300",
					"hideDuration": "1000",
					"timeOut": "5000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
		});
	})( jQuery );
</script>
<?php endif ?>
