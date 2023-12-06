<?php if( !defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="page-title">
    <h1><?php echo lang("nav:transaction") ?></h1>
    <p>List of awesome widgets that can be used everywhere in template.</p>
</div>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'dashboard' ) ?>" class="tile tile-primary">
            <i class="fa fa-desktop"></i> 
            <br />
            <b><?php echo lang("nav:dashboard") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'reservations' ) ?>" class="tile tile-primary">
            <i class="fa fa-plus-square"></i> 
            <br />
            <b><?php echo lang("nav:reservation") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'registrations' ) ?>" class="tile tile-primary">
            <i class="fa fa-tasks"></i> 
            <br />
            <b><?php echo lang("nav:registration") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'examinations' ) ?>" class="tile tile-primary">
            <i class="fa fa-stethoscope"></i> 
            <br />
            <b><?php echo lang("nav:examination") ?></b>
        </a>
    </div>
</div>

<div class="page-title">
    <h1><?php echo lang("global:common_title") ?></h1>
    <p>List of awesome widgets that can be used everywhere in template.</p>
</div>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'common/patients' ) ?>" class="tile tile-primary">
            <i class="fa fa-user-md"></i> 
            <br />
            <b><?php echo lang("nav:patients") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'common/patient-types' ) ?>" class="tile tile-primary">
            <i class="fa fa-group"></i> 
            <br />
            <b><?php echo lang("nav:patient_types") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'common/services' ) ?>" class="tile tile-primary">
            <i class="fa fa-ticket"></i> 
            <br />
            <b><?php echo lang("nav:services") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'common/icd' ) ?>" class="tile tile-primary">
            <i class="fa fa-tags"></i> 
            <br />
            <b><?php echo lang("nav:icd") ?></b>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'components/services' ) ?>" class="tile tile-primary">
            <i class="fa fa-heartbeat"></i> 
            <br />
            <b><?php echo lang("nav:comp_services") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'components/products' ) ?>" class="tile tile-primary">
            <i class="fa fa-medkit"></i> 
            <br />
            <b><?php echo lang("nav:comp_products") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'common/chart-templates' ) ?>" class="tile tile-primary">
            <i class="fa fa-th-list"></i> 
            <br />
            <b><?php echo lang("nav:chart_templates") ?></b>
        </a>
    </div>
</div>

<div class="page-title">
    <h1><?php echo lang("nav:inventory") ?></h1>
    <p>List of awesome widgets that can be used everywhere in template.</p>
</div>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/products' ) ?>" class="tile tile-primary">
            <i class="fa fa-barcode"></i> 
            <br />
            <b><?php echo lang("nav:products") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/products/class' ) ?>" class="tile tile-primary">
            <i class="fa fa-briefcase"></i> 
            <br />
            <b><?php echo lang("nav:product_class") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/products/category' ) ?>" class="tile tile-primary">
            <i class="fa fa-certificate"></i> 
            <br />
            <b><?php echo lang("nav:product_category") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/products/group' ) ?>" class="tile tile-primary">
            <i class="fa fa-cubes"></i> 
            <br />
            <b><?php echo lang("nav:product_group") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/products/group-type' ) ?>" class="tile tile-primary">
            <i class="fa fa-cube"></i> 
            <br />
            <b><?php echo lang("nav:product_group_type") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/products/unit' ) ?>" class="tile tile-primary">
            <i class="fa fa-ellipsis-h"></i> 
            <br />
            <b><?php echo lang("nav:product_unit") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'inventory/stock-out' ) ?>" class="tile tile-primary">
            <i class="fa fa-exchange"></i> 
            <br />
            <b><?php echo lang("nav:stock") ?></b>
        </a>
    </div>
</div>

<div class="page-title">
    <h1><?php echo lang("global:report") ?></h1>
    <p>List of awesome widgets that can be used everywhere in template.</p>
</div>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'reports' ) ?>" class="tile tile-primary">
            <i class="fa fa-plus-square"></i> 
            <br />
            <b><?php echo lang("global:report") ?></b>
        </a>
    </div>
</div>

<div class="page-title">
    <h1><?php echo lang("global:system_title") ?></h1>
    <p>List of awesome widgets that can be used everywhere in template.</p>
</div>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'settings' ) ?>" class="tile tile-primary">
            <i class="fa fa-gears"></i> 
            <br />
            <b><?php echo lang("nav:settings") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'user/accounts' ) ?>" class="tile tile-primary">
            <i class="fa fa-users"></i> 
            <br />
            <b><?php echo lang("nav:users") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'api/server/restful-keys' ) ?>" class="tile tile-primary">
            <i class="fa fa-cloud"></i> 
            <br />
            <b><?php echo lang("nav:web_services") ?></b>
        </a>
    </div>
</div>

<div class="page-title">
    <h1><?php echo lang("global:user_title") ?></h1>
    <p>List of awesome widgets that can be used everywhere in template.</p>
</div>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo base_url( 'profile/settings' ) ?>" class="tile tile-primary">
            <i class="fa fa-user"></i> 
            <br />
            <b><?php echo lang("nav:my_profile") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'profile/activities' ) ?>" class="tile tile-primary">
            <i class="fa fa-undo"></i> 
            <br />
            <b><?php echo lang("nav:activities") ?></b>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo base_url( 'logout' ) ?>" class="tile tile-primary">
            <i class="fa fa-power-off"></i> 
            <br />
            <b><?php echo lang("nav:logout") ?></b>
        </a>
    </div>
</div>
