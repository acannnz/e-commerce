<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="{{ app_description }}">
    <meta name="author" content="{{ app_author }}">
    
    <title>{{ template.title }} - {{ app_name }}</title>
    
    <link rel="apple-touch-icon" sizes="57x57" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ base_theme }}/bracketadmin/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ base_theme }}/bracketadmin/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ base_theme }}/bracketadmin/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ base_theme }}/bracketadmin/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ base_theme }}/bracketadmin/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ base_theme }}/bracketadmin/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ base_theme }}/bracketadmin/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <link href="{{ base_theme }}/bracketadmin/css/style.default.css" rel="stylesheet">
    <?php /*?><link href="{{ base_theme }}/bracketadmin/css/style.katniss.css" rel="stylesheet"><?php */?>
    <link href="{{ base_theme }}/bracketadmin/css/style.custom.css" rel="stylesheet">
    <link href="{{ base_theme }}/bracketadmin/css/font.helvetica-neue.css" rel="stylesheet">
    
    <?php /*?><link href="{{ base_theme }}/bracketadmin/js/datatable/css/jquery.dataTables.min.css" rel="stylesheet"><?php */?>
    <link href="{{ base_theme }}/bracketadmin/css/jquery.datatables.css" rel="stylesheet">
    <link href="{{ base_theme }}/bracketadmin/vendor/datatable/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
    
    <link href="{{ base_theme }}/bracketadmin/vendor/icheck/skins/all.css" rel="stylesheet">
    <link href="{{ base_theme }}/bracketadmin/vendor/select2/select2.css" rel="stylesheet">
    <link href="{{ base_theme }}/bracketadmin/vendor/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">
    <link href="{{ base_theme }}/bracketadmin/vendor/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="{{ base_theme }}/bracketadmin/vendor/bootstrap-typeahead/bootstrap-typeahead.css" rel="stylesheet">
    <?php /*?><link href="{{ base_theme }}/bracketadmin/vendor/tautocomplete/css/tautocomplete.css" rel="stylesheet"><?php */?>
    
	<script src="{{ base_theme }}/bracketadmin/js/jquery-1.11.1.min.js"></script>
	<script src="{{ base_theme }}/bracketadmin/js/jquery-migrate-1.2.1.min.js"></script>
    
	<?php /*?><script src="{{ base_theme }}/bracketadmin/js/datatable/js/jquery.dataTables.min.js"></script>
    <script src="{{ base_theme }}/bracketadmin/js/datatable/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="{{ base_theme }}/bracketadmin/js/datatable/js/jquery.dataTables.dtFilter.min.js"></script><?php */?>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    	<script src="{{ base_theme }}/bracketadmin/js/html5shiv.js"></script>
    	<script src="{{ base_theme }}/bracketadmin/js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{{ base_theme }}/bracketadmin/js/toastr/toastr.min.css">
	<script type="text/javascript" src="{{ base_theme }}/bracketadmin/js/toastr/toastr.min.js"></script>
	<script language="javascript">
	(function( $ ){		
			$.extend({
					alert_success: function( message, fn, scope ){
							toastr["success"]( message || "Success!" );								
							toastr.options = {
									"closeButton": false,
									"debug": false,
									"newestOnTop": false,
									"progressBar": false,
									"positionClass": "toast-top-right",
									"preventDuplicates": false,
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

							try{ $( "#audio-alert" ).get(0).play(); }catch(ex){}

							if( $.isFunction(fn) ){
								setTimeout(function(){
										fn.call( scope || $ )
									}, 1000)
							}
						},
					alert_warning: function( message, fn, scope ){
							toastr["warning"]( message || "Success!" );								
							toastr.options = {
									"closeButton": false,
									"debug": false,
									"newestOnTop": false,
									"progressBar": false,
									"positionClass": "toast-top-right",
									"preventDuplicates": false,
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
								
							try{ $( "#audio-alert" ).get(0).play(); }catch(ex){}
							
							if( $.isFunction(fn) ){
								setTimeout(function(){
										fn.call( scope || $ )
									}, 1000)
							}
							
						},
					alert_error: function( message, fn, scope ){
							toastr["error"]( message || "Error!" );								
							toastr.options = {
									"closeButton": false,
									"debug": false,
									"newestOnTop": false,
									"progressBar": false,
									"positionClass": "toast-top-right",
									"preventDuplicates": false,
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
								
							try{ $( "#audio-fail" ).get(0).play(); }catch(ex){}
							
							if( $.isFunction(fn) ){
								setTimeout(function(){
										fn.call( scope || $ )
									}, 1000)
							}
							
						}
				});
		})( jQuery );

		
	</script>


