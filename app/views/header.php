<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
  <head>
    <meta charset="utf-8">
    <title><?= $title ?> | Aegis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description ?>">

    <!-- Los styles -->    
    <link href="<?= site_url('assets/css/bootstrap.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/responsive.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/animate.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= site_url('assets/css/app.css') ?>" rel="stylesheet">       
    <script src="<?= site_url('assets/js/library/css_browser_selector.js') ?>" type="text/javascript"></script>

    <!-- el HTML5 shimo, por IE6-8 supporto de HTML5 elementos -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?= site_url('/assets/images/favicon.ico') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= site_url('assets/ico/apple-touch-icon-114-precomposed.png')?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= site_url('assets/ico/apple-touch-icon-72-precomposed.png') ?>">
    <link rel="apple-touch-icon-precomposed" href="<?= site_url('assets/ico/apple-touch-icon-57-precomposed.png') ?>">
    <?= render_styles() ?>
</head>