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
        <div class="dev-page">
        	<!-- page header -->    
            <div class="dev-page-header">
            	{{ template.partials.header }}
            </div>
            <!-- page header -->
            
            <!-- page container -->
            <div class="dev-page-container">
            	<!-- page sidebar -->
                <div class="dev-page-sidebar">
                	{{ template.}}
                    {{ template.partials.left }}
                </div>
                <!-- ./page sidebar -->
                <!-- page content -->
                <div class="dev-page-content">
                	<!-- page content container -->
                    <div class="container">
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
            <div class="dev-page-footer dev-page-footer-fixed">
                {{ template.partials.footer }}
            </div>
            <!-- page footer -->
            
            <!-- page search -->
            {{ template.partials.search }}
            <!-- page search -->
        </div>
        <!-- ./page wrapper -->
        {{ template.partials.modal }}
    	{{ template.partials.bottom_scripts }}
	</body>
</html>