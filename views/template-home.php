<?php
/*
 * Template Name: Home
 *
 * 
 *
 */
?>

<?php

$myIp = $_SERVER['REMOTE_ADDR'];

$allowedIPs = array(
	'38.98.105.2',		// Berman Group
	'172.56.35.248',	// Rene Cell
	'172.56.35.248',	// Wally Cell
	'74.68.158.12',		// Wally Home
);

// Check for ALLOWED IP
// if (in_array($myIp, $allowedIPs)) {

	if (strpos($_SERVER["REQUEST_URI"],'_escaped_fragment_') !== false) {
		$ajaxPageID = "";
		$pageURL = str_replace("/", "", $_SERVER["REQUEST_URI"]);
		$pageURL = str_replace("?_escaped_fragment_=", "", $pageURL);
		switch ($pageURL) {
			case "":
				$ajaxPageID = "sample-page";
			break;

			default:
				$pageURLarray = explode("/", $pageURL);
				$ajaxPageID = $pageURLarray[0];
			break;
			
		}
	    include(get_template_directory() . '/_ajax/' . $ajaxPageID . '.html');
	} else {
		get_header(); ?>
		<div class="wrapper">
			<section></section>
		</div>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/modernizr/modernizr.js"></script>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/moment/moment.min.js"></script>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/flowtype/flowtype.js"></script>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/underscore/underscore.js"></script>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/coolkitten/jquery.stellar.min.js"></script>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/coolkitten/waypoints.min.js"></script>
		<script type="text/javascript" src="<?php echo PAGEDIR; ?>/machines/libraries/coolkitten/jquery.easing.1.3.js"></script>
		<?php get_footer(); 
	}

// }