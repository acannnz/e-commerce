<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        {{ template.partials.head }}
    </head>
    <body class="signin">
        <section>
            <div class="signinpanel">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        {{ template.body }}
                    </div>
                </div>
                <div class="signup-footer">
                	<center><small>&copy; <?php echo @date('Y'); ?>. All Rights Reserved. {{ app_name }}</small></center>
                </div>
            </div>
        </section>
    	{{ template.partials.bottom_scripts }}
    </body>
</html>




