<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

date_default_timezone_set(config_item('timezone'));
?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code')?>" class="app">
    <head>
    	{{ template.metadata }}
        {{ template.partials.head }}
        {{ template.partials.scripts }}
    </head>
    <body>
        <!-- set loading layer -->
        <div class="dev-page-loading preloader"></div>
        <!-- ./set loading layer -->
        
        <!-- page wrapper -->
        <?php if( isset($navigation_minimized) ): ?>
        <div class="dev-page dev-page-sidebar-minimized">
        <?php else: ?>
        <div class="dev-page">
        <?php endif ?>
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
                        <div class="page-title">
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