<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        {{ template.partials.head }}
    </head>
    <body class="stickyheader leftpanel-collapsed">
        {{ template.partials.loader }}
        <section>
            <div class="leftpanel">
            	{{ template.partials.aside }}
            </div>
            <div class="mainpanel">
            	<div class="headerbar">
                	{{ template.partials.header }}
                </div>
                <div class="pageheader">
                    <?php if (isset($p_heading)): ?><h2><i class="fa fa-search"></i> <?php echo $p_heading; ?> <span>{{ app_description }}</span></h2>
                    <?php else: ?><h2><i class="fa fa-home"></i> <?php echo config_item('company_name'); ?> 
                        <?php /* <span> <?php echo str_replace('_',' ', ucfirst(config_item('department'))) ?> */ ?>
                    </span></h2><?php endif; ?>
                    <div class="breadcrumb-wrapper">
                        <span class="label"><?php echo lang('you_are_here'); ?>:</span>
                        <ol class="breadcrumb">
                            <li><a href="<?php echo base_url('') ?>"><?php echo lang('nav:dashboard'); ?></a></li>
                            {{ if template.breadcrumbs }}
                            {{ template.breadcrumbs }}
                            {{ if uri }}
                            <li class="breadcrumb-item"><a href="{{ uri }}" title="{{ name }}">{{ name }}</a></li>
                            {{ else }}
                            <li class="breadcrumb-item active">{{ name }}</li>
                            {{ endif }}
                            {{ /template.breadcrumbs }}
                            {{ endif }}
                        </ol>
                    </div>
                </div>
                <div class="contentpanel">
                    {{ template.body }}
                </div>
                <div class="rightpanel">
                </div>
            </div>
        </section>
        {{ template.partials.footer }}
        {{ template.partials.modal }}
        {{ template.partials.bottom_scripts }}
    </body>
</html>


