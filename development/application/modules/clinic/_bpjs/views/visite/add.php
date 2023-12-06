<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<script type="text/javascript">
//<![CDATA[
		var bpjsBridgingVisite = true;
		var bpjsVisite = {
				post: function(dataPost){					
					$.alert_warning('Mohon menunggu, sistem sedang melakukan proses sinkronisasi BPJS...');
					ajax_modal.show('<?php echo $process_url ?>');
				}
			};
			
//]]>
</script>
