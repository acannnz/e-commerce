<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row row-condensed">
    <div class="col-lg-9 col-md-6">
    	<div class="wrapper">
			<?php echo Modules::run( "welcome/reports/registrations" ) ?>
            <?php echo Modules::run( "welcome/reports/reservations" ) ?>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <?php echo Modules::run( "welcome/reports/charts" ) ?>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="wrapper">
            <div class="dev-table">
            	<?php echo Modules::run( "welcome/statistics/patients" ) ?>
                <?php echo Modules::run( "welcome/statistics/reservations" ) ?>
                <?php echo Modules::run( "welcome/statistics/registrations" ) ?>
            </div>
        </div>
	</div>
</div>


