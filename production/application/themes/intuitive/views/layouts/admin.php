<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

date_default_timezone_set("Asia/Hong_Kong");
?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code')?>" class="app">
    <head>
    	{{ template.metadata }}
        {{ template.partials.head }}

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
								
							},
						notif_long: function( message, fn, scope ){
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
										"timeOut": "26000",
										"extendedTimeOut": "1000",
										"showEasing": "swing",
										"hideEasing": "linear",
										"showMethod": "fadeIn",
										"hideMethod": "fadeOut"
									};

								try{ $( "#notif-long" ).get(0).play(); }catch(ex){}

								if( $.isFunction(fn) ){
									setTimeout(function(){
											fn.call( scope || $ )
										}, 1000)
								}
							},
					});
			})( jQuery );
		</script>
    </head>
    <body>
    	{{ template.partials.loader }}        
        <!-- page wrapper -->
        <div class="dev-page dev-page-sidebar-collapsed">
        	<!-- page header -->    
            <div class="dev-page-header">
            	{{ template.partials.header }}
            </div>
            <!-- page header -->
            
            <!-- page container -->
            <div class="dev-page-container">
            	<!-- page sidebar -->
				{{ if template.partials.aside }}
                <div class="dev-page-sidebar">
                	{{ template.partials.aside }}
                </div>
				{{ endif }}
                <!-- ./page sidebar -->
                <!-- page content -->
                <div class="dev-page-content">
                	<!-- page content container -->
                    <div class="container">
                        <div class="page-title">
                            <h1>{{ heading }}</h1>
                            {{ if heading_helper }}<p>{{ heading_helper }}</p>{{ endif }}
                            
                            <ul class="breadcrumb">
                                <li><a href="<?php echo base_url() ?>"><?php echo lang('nav') ?></a></li>
                                {{ if template.breadcrumbs }}
                                {{ template.breadcrumbs }}
                                {{ if uri }}
                                <li><a href="{{ uri }}">{{ name }}</a></li>
                                {{ else }}
                                <li>{{ name }}</li>
                                {{ endif }}
                            	{{ /template.breadcrumbs }}
                            	{{ endif }}
                            </ul>
                        </div>  
                        <div class="wrapper">
                        	{{ template.body }}
                        </div>
                    </div>
                </div>
                <!-- ./page content -->  
            </div>
            <!-- ./page container -->
            
            <!-- right bar -->
            <div class="dev-page-rightbar">
            	{{ template.partials.right }}
            </div>
            <!-- right bar -->
            
            <!-- page footer -->    
            <!--<div class="dev-page-footer dev-page-footer-fixed">-->
			<div class="dev-page-footer">
                {{ template.partials.footer }}
            </div>
            <!-- page footer -->
            
        </div>
        <!-- ./page wrapper -->
        <?php /*?>{{ template.partials.modal }}<?php */?>
    	{{ template.partials.bottom_scripts }}
	</body>
</html>