<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>
<!-- .aside -->
<aside class="bg-<?php echo $this->config->item('sidebar_theme') ?> b-r aside-md hidden-print" id="nav">
    <section class="vbox">    
        <?php if(config_item('enable_languages') == 'TRUE'){ ?>
        <header class="header bg-dark text-center clearfix">
          <div class="btn-group">
            <button type="button" class="btn btn-sm btn-info btn-icon" title="<?php echo lang('languages')?>"><i class="fa fa-globe"></i></button>
            <div class="btn-group hidden-nav-xs">
              <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"> <?php echo lang('languages')?>
              <span class="caret">
              </span> </button>
              <!-- Load Languages -->
                <ul class="dropdown-menu text-left">
                <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
                <li>
                    <a href="<?php echo base_url()?>set_language?lang=<?php echo $lang->name?>" title="<?php echo ucwords(str_replace("_"," ", $lang->name))?>">
                        <img src="<?php echo base_url()?>resource/images/flags/<?php echo $lang->icon?>.gif" alt="<?php echo ucwords(str_replace("_"," ", $lang->name))?>"  /> <?php echo ucwords(str_replace("_"," ", $lang->name))?>
                    </a>
                </li>
                <?php endif; endforeach; ?>
                </ul>
            </div>
          </div>
        </header>
        <?php } ?>
    
        <section class="scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                <!-- nav -->
                <nav class="nav-primary hidden-xs">
                    <ul class="nav">
                        <li class="<?php if($page == lang('home')){echo  "active"; }?>">
                            <a href="<?php echo base_url()?>"> <i class="fa fa-dashboard icon"> <b class="bg-info"></b> </i>
                        <span><?php echo lang('home')?></span> </a> </li>
                    </ul>
                </nav>
                <!-- / nav -->
              </div>
        </section>
    </section>
</aside>
<!-- /.aside -->