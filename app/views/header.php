<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="path/to/favicon.png">

    <title><?= $page_title; ?></title>
    <meta name="description" content="<?= $page_description; ?>">

	<!-- Latest compiled and minified CSS from CDN -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">

    <!-- Modernizer -->
    <link href="<?= site_url('assets/js/library/modernizer.min.js') ?>" rel="stylesheet">

    <?= render_styles($styles); ?>

    <!-- Respond.js for IE8 support of media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
