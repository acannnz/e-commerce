<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code')?>" class="bg-dark">
    <head>
        {{ template.metadata }}
        {{ template.partials.head }}
    </head>
    <body>
		{{ template.partials.loader }}
        <!-- page wrapper -->
        <div class="dev-page dev-page-login dev-page-login-v2">                      
            <div class="dev-page-login-block">
                {{ template.partials.header }}
                <div class="dev-page-login-block__form">
                    {{ template.body }}
                </div>
                {{ template.partials.footer }}
            </div>            
        </div>
        <!-- ./page wrapper -->
        <?php /*?>{{ template.partials.modal }}<?php */?>
        {{ template.partials.bottom_scripts }}
    </body>
</html>