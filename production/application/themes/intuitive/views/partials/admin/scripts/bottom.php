<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>
<!-- START PRELOADS -->
<audio id="audio-alert" src="<?php echo base_url( "themes/default/assets/audio" ); ?>/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="<?php echo base_url( "themes/default/assets/audio" ); ?>/fail.mp3" preload="auto"></audio>
<audio id="notif-long" src="<?php echo base_url( "themes/default/assets/audio" ); ?>/long-notif.mpeg" preload="auto"></audio>


<!-- END PRELOADS -->

<!-- javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/mcustomscrollbar/jquery.mousewheel.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/knob/jquery.knob.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/waypoint/waypoints.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/counter/jquery.counterup.min.js"></script>
<!-- ./javascripts -->
<!-- fullsizable -->
<script type="text/javascript" src="<?php echo base_url(); ?>themes/default/assets/js/plugins/fullsizable/jquery-fullsizable.min.js"></script>
<!-- ./fullsizable -->

<?php if( isset($web_stroge) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>themes/default/assets/js/plugins/web-stroge/web-stroge.js"></script>
<?php endif;?>    

<?php if( isset($form) ): ?>
<!-- javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/tags-input/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/maskedinput/maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>themes/default/assets/js/plugins/mask-number/mask-number.js"></script>
<?php if( isset($summernote) || isset($editor) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/summernote/summernote.min.js"></script>
<?php endif ?>
<?php if( isset($codemirror) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/codemirror.js"></script>        
<script type='text/javascript' src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/mode/xml/xml.js"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/mode/javascript/javascript.js"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/mode/css/css.js"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/mode/clike/clike.js"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/codemirror/mode/php/php.js"></script>
<?php endif ?>
<!-- ./javascripts -->
<?php endif ?>

<?php if( isset($datatables) ): ?>
<!-- javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/sortable/sortable.min.js"></script>
<?php if( isset($datatables_export) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/datatables/dataTables.buttons.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/datatables/buttons.html5.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/datatables/jszip.min.js"></script>        
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/datatables/buttons.print.min.js"></script>        
<?php endif; ?>
<script language="javascript">
"use strict";

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
		autoWidth: true,
		<?php /*?>dom: "<'datatable-tools'<'col-sm-5 col-xs-12'l><'col-sm-7 col-xs-12 custom-toolbar'f>r>t<'datatable-tools clearfix'<'col-md-5 col-xs-12'i><'col-md-7 col-xs-12'p>>",<?php */?>
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
				<?php /*?>"processing": "<div class='table-loader'><span class='loading'></span></div>",<?php */?>
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
			}
	});
	
</script>
<!-- ./javascripts -->
<?php endif ?>

<?php if( isset($highcharts) ): ?>
<!-- javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>themes/default/assets/js/plugins/highcharts/v4.1.8/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/default/assets/js/plugins/highcharts/v4.1.8/highcharts-3d.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/default/assets/js/plugins/highcharts/v4.1.8/modules/data.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/default/assets/js/plugins/highcharts/v4.1.8/modules/exporting.js"></script>
<!-- ./javascripts -->
<?php endif ?>

<?php if( isset($jstree) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/jstree/jstree.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/jstree/jstree.table.js"></script>
<?php endif ?>

<?php if( isset($autocomplete) || isset($auto_complete) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>/themes/intuitive/assets/js/plugins/autocomplete/autocomplete.js"></script>
<?php endif;?> 

<?php if( isset($typeahead) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>/themes/default/assets/js/plugins/bootstrap-typeahead/bootstrap-typeahead.js"></script>
<?php endif;?>
<?php /*?><?php if( isset($fileinput) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>themes/default/assets/js/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<?php endif;?><?php */?>     
<?php if( isset($imagecrop) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>themes/default/assets/js/plugins/crop/crop.js"></script>
<?php endif;?>   
<?php if( isset($webcam) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>themes/default/assets/js/plugins/webcamjs/webcam.min.js"></script>
<?php endif;?>
<?php if( isset($simpleupload) ): ?>
<script type="text/javascript" src="<?php echo base_url();?>themes/default/assets/js/plugins/simple-upload/simple-upload.min.js"></script>
<?php endif;?>    
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-loaders.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-layout.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-app.js"></script>
<?php /*?><?php if( isset($timeout) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/dev-timeout.js"></script>
<?php endif ?><?php */?>

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
