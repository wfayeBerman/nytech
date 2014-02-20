<!DOCTYPE HTML>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
	<head>
		<meta name="fragment" content="!">
		<title><?php bloginfo('name'); ?></title>
		<?php wp_head(); ?>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no">
		<link rel="stylesheet" href="<?php bloginfo( stylesheet_url ); ?>"/>

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo PAGEDIR; ?>/styles/styles.min.css" type="text/css" media="screen">
		<!--[if IE]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
	<body data-tempdir="<?php echo PAGEDIR; ?>">
		<?php
			generatePagesJSON(get_the_ID());
			populateJavascript(get_post_field('post_content', get_the_ID()), 'post');
		?>