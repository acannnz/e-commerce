<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
    <meta charset="utf-8" />
    <title>{{ template.title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="<?php echo $this->config->item('site_author'); ?>">
    <meta name="keyword" content="<?php echo $this->config->item('site_desc'); ?>">
    <meta name="description" content="">
	<meta name="mobile-web-app-capable" content="yes">
    
	<?php /*?><?php $favicon = config_item('site_favicon'); $ext = substr($favicon, -4); ?>
    <?php if ( $ext == '.ico') : ?>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_favicon'); ?>">
    <?php endif; ?>
    <?php if ($ext == '.png') : ?>
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_favicon'); ?>">
    <?php endif; ?>
    <?php if ($ext == '.jpg' || $ext == 'jpeg') : ?>
    <link rel="icon" type="image/jpeg" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_favicon'); ?>">
    <?php endif; ?>
    <?php if (config_item('site_appleicon') != '') : ?>
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_appleicon'); ?>" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_appleicon'); ?>" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_appleicon'); ?>" />
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>themes/intuitive/assets/images/<?php echo config_item('site_appleicon'); ?>" />
    <?php endif; ?><?php */?>
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/apple-icon-180x180.png">
    <link rel="shortcut icon" type="image/png" sizes="192x192"  href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url( "themes/default/assets/img/favicon" ); ?>/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <!-- css styles -->
    <?php if( isset($autocomplete) || isset($auto_complete) ): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/dev-plugins/autocomplete/autocomplete.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/dev-plugins/autocomplete/autocomplete.js.css">
    <?php endif;?>
    <?php if( isset($typeahead) ): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/bootstrap-typeahead/bootstrap-typeahead.css">
	<?php endif;?>
    <?php /*?><?php if( isset($fileinput) ): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/bootstrap-fileinput/bootstrap-fileinput.css">
	<?php endif;?><?php */?>
    <?php if( isset($simpleupload) ): ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/simple-upload/simple-upload.css">
	<?php endif;?>
    <?php if( isset($imagecrop) ): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/crop/crop.css">
	<?php endif;?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/css/base.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/green-white.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/green-white-custom.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/dev-plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/toastr/toastr.min.css">
    <link href="<?php echo base_url(); ?>themes/default/assets/js/plugins/fullsizable/jquery-fullsizable.css"  rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/css/logo.css" id="logo-css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/printjs/print.min.css">

    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.1/css/all.min.css"> -->
	
    
    <!-- ./css styles -->
    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/dev-other/dev-ie-fix.css">
    <![endif]-->
    
    <style>
    .dev-page{visibility: hidden;}
    .fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start {
		margin-right: 2px;
		font-size: 12px !important;
	}
    </style>
    
    
	<!-- javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/jquery/jquery.min.js"></script>       
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/modernizr/modernizr.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/json/json2.js"></script>    
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/moment/moment.js"></script>      
<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/full-calender/index.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/default/assets/js/plugins/printjs/print.min.js"></script>   
<!-- ./javascripts -->

    