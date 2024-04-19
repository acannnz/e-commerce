<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code')?>" class="bg-dark">
    <head>
        {{ template.metadata }}
        {{ template.partials.head }}
        {{ template.partials.scripts }}
    </head>
    <body>
        <!-- page wrapper -->
        <div class="dev-page dev-page-lock-screen">                   
            <div class="dev-page-lock-screen-box">
                {{ template.body }}
            </div>
        </div>
        <!-- ./page wrapper -->
        {{ template.partials.modal }}
        {{ template.partials.bottom_scripts }}
    </body>
</html>