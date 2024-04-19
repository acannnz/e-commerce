<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
    <!-- page search -->
    <div class="dev-search">
        <div class="dev-search-container">
            <div class="dev-search-form">
            <?php echo form_open( current_url() ); ?>
                <div class="dev-search-field">
                    <input type="text" placeholder="Search..." value="">
                </div>                        
            <?php echo form_close() ?>
            </div>
            <div class="dev-search-results"></div>
        </div>
    </div>
    <!-- page search -->