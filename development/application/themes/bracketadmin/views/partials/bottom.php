<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

?>
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="blur-svg">
	<defs>
		<filter id="blur-filter">
			<feGaussianBlur stdDeviation="3"></feGaussianBlur>
		</filter>
	</defs>
</svg>

<!-- START PRELOADS -->
<audio id="audio-alert" src="{{ base_theme }}/bracketadmin/audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="{{ base_theme }}/bracketadmin/audio/fail.mp3" preload="auto"></audio>
<!-- END PRELOADS -->

<?php /*?><script src="{{ base_theme }}/bracketadmin/js/jquery-1.11.1.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/jquery-migrate-1.2.1.min.js"></script><?php */?>
<script src="{{ base_theme }}/bracketadmin/js/bootstrap.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/modernizr.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/jquery.sparkline.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/toggles.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/retina.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/jquery.cookies.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/mask-number/mask-number.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/web-storage/web-storage.js"></script>

<script src="{{ base_theme }}/bracketadmin/vendor/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/moment/moment.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/select2/select2.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/icheck/js/icheck.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/bootstrap-typeahead/bootstrap-typeahead.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/jquery-validation/jquery.form.js"></script>

<script src="{{ base_theme }}/bracketadmin/vendor/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/datatable/js/jquery.dataTables.select.min.js"></script>
<script src="{{ base_theme }}/bracketadmin/vendor/datatable/TableTools/js/dataTables.tableTools.min.js"></script>
<script language="javascript">

<?php /*?>$.ajaxSetup({
	data: {
		<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
	}
});<?php */?>
		
jQuery.extend(true, jQuery.fn.dataTable.defaults, {
		processing: true,
		serverSide: true,								
		paginate: true,
		ordering: true,
		searching: true,
		info: true,
		responsive: true,			
		dom: "<'datatable-tools'<'col-md-4'l><'col-md-8 custom-toolbar'f>r>t<'datatable-tools clearfix'<'col-md-4'i><'col-md-8'p>>",
		language: {
				"decimal": "",
				"emptyTable": "<?php echo lang('datatable:empty_table'); ?>",
				"info": "<?php echo lang('datatable:info'); ?>",
				"infoEmpty": "<?php echo lang('datatable:empty_table'); ?>",
				"infoFiltered": "<?php echo lang('datatable:info_filtered'); ?>",
				"infoPostFix": "",
				"thousands": ",",
				"lengthMenu": "<?php echo lang('datatable:length_menu'); ?>",
				"loadingRecords": "<?php echo lang('datatable:loading_records'); ?>",
				"processing": "<div class='table-loader'><span class='loading'></span></div>",
				"search": "<?php echo lang('datatable:search'); ?>",
				"zeroRecords": "<?php echo lang('datatable:zero_records'); ?>",
				"paginate": {
						"first": "<?php echo lang('datatable:paginate_first'); ?>",
						"last": "<?php echo lang('datatable:paginate_last'); ?>",
						"next": "<i class='fa fa-angle-double-right'></i>",
						"previous": "<i class='fa fa-angle-double-left'></i>"
					},
				"aria": {
						"sortAscending": "<?php echo lang('datatable:sort_ascending'); ?>",
						"sortDescending": "<?php echo lang('datatable:sort_descending'); ?>"
					}
			},
		drawCallback: function(settings){
				try{
					jQuery("input").addClass('input-xs');
					jQuery("select").addClass('select input-xs');
					jQuery(".dataTables_wrapper").find("select").select2({
							minimumResultsForSearch: -1
						});				
					jQuery('input[type="checkbox"].checkbox').iCheck({
							checkboxClass: 'icheckbox_square-blue',
							radioClass: 'iradio_square-blue',
							increaseArea: '20%'
						});
					jQuery(".tip").tooltip({html: true});
					jQuery(".popnote").popover();
				} catch(e){ alert(e.message); }
			}
	});
$(".datepicker").datepicker({
	format: 'yyyy-mm-dd'
});

</script>

<?php
if($this->session->flashdata('message')): 
	$message = $this->session->flashdata('message');
	$alert = $this->session->flashdata('response_status');
?>
<script type="text/javascript">//<![CDATA[
(function($){
	$( document ).ready(function(e){
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
				};
				
			<?php if("success" == $alert): ?>try{ $( "#audio-alert" ).get(0).play(); }catch(ex){} <?php endif ?>
			<?php if("error" == $alert): ?>try{ $( "#audio-fail" ).get(0).play(); }catch(ex){} <?php endif ?>
		});
		
	})( jQuery );
	
</script>
<?php endif ?>

<script src="{{ base_theme }}/bracketadmin/js/layout.js"></script>
<script src="{{ base_theme }}/bracketadmin/js/app.js"></script>
