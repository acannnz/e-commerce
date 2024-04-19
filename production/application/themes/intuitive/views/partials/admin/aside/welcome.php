<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>

<?php if(config_item('enable_languages') == 'TRUE'): ?>
<ul class="dev-lang-navigation">
    <h4><span class="label"><?php echo lang('languages')?></h4>
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
    <li class="title"><?php echo lang("nav") ?></li>
    <li<?php if(in_array(@$page, array("", "welcome", "dashboard"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url() ?>"><i class="fa fa-desktop"></i><?php echo lang("nav:dashboard") ?></a>
    </li>
    
    <li class="title"><?php echo lang("nav:users") ?></li>
    <li><a href="<?php echo base_url( 'profile' ) ?>"><i class="fa fa-user-circle-o"></i> <?php echo lang("nav:my_profile") ?></a></li>
    <li><a href="<?php echo base_url( 'logout' ) ?>"><i class="fa fa-sign-out"></i> <b><?php echo lang("nav:logout") ?></b></a></li>
</ul>


