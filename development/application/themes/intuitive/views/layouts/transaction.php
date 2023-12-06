<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

date_default_timezone_set(config_item('timezone'));
?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code')?>" class="app">
    <head>
    	{{ template.metadata }}
        {{ template.partials.head }}
        {{ template.partials.scripts }}
        <script language="javascript">
		(function( $ ){
				$.fn.extend({
						Modules_run: function( endpoint, params, fn, scope ){
								if( ! this.size() ){ return this }
								
								this
									.text( "<?php echo lang("global:ajax_loading") ?>" )
									.load( endpoint, params || {}, function( response, status, xhr ){
											if( $.isFunction(params) ){
												scope = fn;
												fn = params;
											}
											
											if( $.isFunction(fn) ){
												fn.call( scope || $, response, status )
											}
										});
								
								return this
							},
						Modules_post: function( endpoint, params, fn, scope ){
								if( ! this.size() ){ return this }
								
								var _this = this;
								
								$.post( endpoint, params || {} )
									.done(function( response, status, xhr ){
											_this.html( response );
										
											if( $.isFunction(params) ){
												scope = fn;
												fn = params;
											}
											
											if( $.isFunction(fn) ){
												fn.call( scope || $, response, status )
											}
										})
									.fail(function( xhr, status, error ){
											/*  */
										});
								
								return this
							}
					});
					
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
									
								if( $.isFunction(fn) ){
									setTimeout(function(){
											fn.call( scope || $ )
										}, 1000)
								}
								
							}
					});
			})( jQuery );
		</script>
    </head>
    <body>
        <!-- set loading layer -->
        <div class="dev-page-loading preloader"></div>
        <!-- ./set loading layer -->
        
        <!-- page wrapper -->
        <div class="dev-page dev-page-sidebar-collapsed">
        <?php /*?><div class="dev-page dev-page-sidebar-minimized"><?php */?>
        	<!-- page header -->    
            <div class="dev-page-header">
            	{{ template.partials.header }}
            </div>
            <!-- page header -->
            
            <!-- page container -->
            <div class="dev-page-container">
            	<!-- page sidebar -->
                <div class="dev-page-sidebar">
                	{{ template.partials.left }}
                </div>
                <!-- ./page sidebar -->
                <!-- page content -->
                <div class="dev-page-content">
                	<!-- page content container -->
                    <div class="container">
                        <?php /*?><div class="page-title">
                            <h1>{{ heading }}</h1>
                            {{ if heading_helper }}<p>{{ heading_helper }}</p>{{ endif }}
                            
                            <ul class="breadcrumb">
                                <li><a href="<?php echo base_url() ?>"><?php echo lang('nav:dashboard') ?></a></li>
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
                        </div><?php */?>
                        {{ template.body }}
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
            <?php /*?><div class="dev-page-footer dev-page-footer-closed"><?php */?>
            <div class="dev-page-footer dev-page-footer-fixed">
                {{ template.partials.footer }}
            </div>
            <!-- page footer -->
            
            {{ template.partials.search }}
        </div>
        <!-- ./page wrapper -->
        {{ template.partials.modal }}
    	{{ template.partials.bottom_scripts }}
	</body>
</html>