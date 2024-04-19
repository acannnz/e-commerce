<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-lg-4 col-md-12">
        <div class="page-subtitle">
            <h3><?php echo lang( 'departments:list_subtitle' ) ?></h3>
            <p><?php echo lang( 'departments:list_subtitle_helper' ) ?></p>
        </div> 
        <div class="list-group">
            <a href="<?php echo base_url( "settings/departments/create" ) ?>" data-toggle="ajax-modal" class="list-group-item list-group-item-info"><i class="fa fa-plus"></i> <?php echo lang( "button:create" ) ?></a>
            <?php foreach($collection as $i => $item): ?>
            <a href="<?php echo base_url( "settings/departments/update/{$item->id}" ) ?>" data-toggle="ajax-modal" title="<?php echo $item->department ?>" class="list-group-item"><span class="badge"><?php echo (int) ($i + 1) ?></span> <?php echo $item->department ?></a>
            <?php endforeach ?>
            <a href="<?php echo base_url( "settings/departments/create" ) ?>" data-toggle="ajax-modal" class="list-group-item list-group-item-info"><i class="fa fa-plus"></i> <?php echo lang( "button:create" ) ?></a>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
    </div>
    <div class="col-lg-4 col-md-12">
    </div>
</div>

