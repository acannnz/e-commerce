<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-loaders.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript">//<![CDATA[
$.ajaxSetup({
	data: {
		<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
	}
});

(function($){
	$( document ).ready(function(e){
			if($(".selectpicker").length > 0)
            $(".selectpicker").selectpicker();
		});
	})( jQuery )