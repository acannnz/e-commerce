<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
    	<div class="row">
            <div class="col-md-5">                	
                <a href="<?php echo base_url( "common/customer_cooperations/picture_upload/{$profile_id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang( 'buttons:upload_picture' ) ?>" class="btn btn-block btn-lg btn-default">
                    <i class="fa fa-sm fa-plus"></i> <?php echo lang( 'buttons:upload_picture' ) ?>
                </a>
            </div>
            <div class="col-md-5">
                <a href="<?php echo base_url( "common/customer_cooperations/picture_capture/{$profile_id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang( 'buttons:take_picture' ) ?>" class="btn btn-block btn-lg btn-default">
                    <i class="fa fa-sm fa-camera"></i> <?php echo lang( 'buttons:take_picture' ) ?>
                </a>
            </div>
            <div class="col-md-2">
                <a href="<?php echo base_url( "common/customer_cooperations/picture_crop/{$profile_id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang( 'buttons:edit_picture' ) ?>" class="btn btn-block btn-lg btn-primary">
                    <i class="fa fa-pencil"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-3"></div>
    <div class="col-md-6">
		<?php if( $profile->personal_picture ): ?>
        <a href="javascript:;" class="thumbnail">
            <img src="<?php echo base_url( "resource/customer_cooperations/pictures" ) ?>/<?php echo $profile->personal_picture ?><?php echo (sprintf("?rand=%s", @time())) ?>">
        </a>
        <?php else: ?>
        <a href="javascript:;" class="thumbnail">
            <img src="<?php echo base_url( "resource/customer_cooperations/pictures" ) ?>/default_picture.jpg">
        </a>
        <?php endif ?>
    </div>
    <div class="col-md-3"></div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if( isset($is_ajax_request) ): ?>
        <a href="<?php echo base_url( "common/customer_cooperations/overview/{$profile_id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang("buttons:cancel") ?>" class="btn btn-block btn-cancel btn-default"><?php echo lang("buttons:cancel") ?></a>
        <?php else: ?>
        <a href="<?php echo base_url( "common/customer_cooperations/overview/{$profile_id}" ) ?>" title="<?php echo lang("buttons:cancel") ?>" class="btn btn-block btn-cancel btn-default"><?php echo lang("buttons:cancel") ?></a>
        <?php endif ?>
    </div>
</div>
