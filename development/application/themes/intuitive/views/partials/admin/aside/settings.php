<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>

<?php if(config_item('enable_languages') == 'TRUE'): ?>
<ul class="dev-lang-navigation">
    <h4><span class="label"><?php echo lang('languages')?></span></h4>
    <div class="btn-group">
        <button type="button" class="btn btn-icon btn-danger dropdown-toggle" data-toggle="dropdown" title="<?php echo lang('languages')?>">
            <i class="fa fa-globe"></i>
        </button>
        <ul class="dropdown-menu text-left">
            <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
            <li>
                <a href="<?php echo base_url()?>set_language?lang=<?php echo $lang->name?>" title="<?php echo ucwords(str_replace("_"," ", $lang->name))?>">
                    <img src="<?php echo base_url()?>resource/images/flags/<?php echo $lang->icon ?>.gif" alt="<?php echo ucwords(str_replace("_"," ", $lang->name))?>"  /> <?php echo ucwords(str_replace("_"," ", $lang->name))?>
                </a>
            </li>
            <?php endif; endforeach; ?>
        </ul>
    </div>
</ul>
<?php endif ?>

<ul class="dev-page-navigation">
    <li class="title"><?php echo lang("nav:settings") ?></li>    
    <li><a href="<?php echo base_url('settings') ?>" title="<?php echo lang('nav:company_details') ?>"><i class="fa fa-building"></i> <span><?php echo lang('nav:company_details') ?></span></a></li>
    <li><a href="<?php echo base_url('settings/system') ?>" title="<?php echo lang('nav:system_settings') ?>"><i class="fa fa-globe"></i> <span><?php echo lang('nav:system_settings') ?></span></a></li>
    <li><a href="<?php echo base_url('settings/email') ?>" title="<?php echo lang('nav:email_settings') ?>"><i class="fa fa-envelope"></i> <span><?php echo lang('nav:email_settings') ?></span></a></li>
    <li><a href="<?php echo base_url('settings/templates') ?>" title="<?php echo lang('nav:email_templates') ?>"><i class="fa fa-envelope-o"></i> <span><?php echo lang('nav:email_templates') ?></span></a></li>
    <li><a href="<?php echo base_url('settings/permissions') ?>" title="<?php echo lang('nav:staff_permissions') ?>"><i class="fa fa-road"></i> <span><?php echo lang('nav:staff_permissions') ?></span></a></li>
    <?php /*?><li><a href="<?php echo base_url('settings/departments') ?>" title="<?php echo lang('nav:departments') ?>"><i class="fa fa-cog"></i> <span><?php echo lang('nav:departments') ?></span></a></li><?php */?>
    <li><a href="<?php echo base_url('settings/theme') ?>" title="<?php echo lang('nav:theme_settings') ?>"><i class="fa fa-paint-brush"></i> <span><?php echo lang('nav:theme_settings') ?></span></a></li>
    <?php /*?><li><a href="<?php echo base_url('settings/fields/department') ?>" data-toggle="ajax-modal" title="<?php echo lang('nav:custom_fields') ?>"><i class="fa fa-cog"></i> <span><?php echo lang('nav:custom_fields') ?></span></a></li><?php */?>
    <?php /*?><li><a href="<?php echo base_url('settings/translations') ?>" title="<?php echo lang('nav:translations') ?>"><i class="fa fa-language"></i> <span><?php echo lang('nav:translations') ?></span></a></li><?php */?>                          
    <li><a href="<?php echo base_url('settings/api') ?>" title="<?php echo lang('nav:api') ?>" class="text-warning"><i class="fa fa-cloud"></i> <span><?php echo lang('nav:api') ?></span></a></li>
    <?php /*?><li><a href="<?php echo base_url('settings/database/backup') ?>" title="<?php echo lang('nav:database_backup') ?>" class="text-danger"><i class="fa fa-database"></i> <span><?php echo lang('nav:database_backup') ?></span></a></li><?php */?>
    <li><a href="<?php echo base_url('') ?>" title="<?php echo lang('nav:back') ?>"><i class="fa fa-arrow-circle-left"></i> <span><?php echo lang('nav:back') ?></span></a></li>
</ul>


