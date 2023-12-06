<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
    <!-- javascript -->
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/jquery/jquery.min.js"></script>       
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/bootstrap/bootstrap.min.js"></script>
    
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/toastr/toastr.min.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/raphael/raphael-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-clock.js"></script>        
    <!-- ./javascript -->
    
    <?php
	if($this->session->flashdata('message')):
	$message = $this->session->flashdata('message');
	$alert = $this->session->flashdata('response_status');
	?>
	<script type="text/javascript">
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
    
    
    