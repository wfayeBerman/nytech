<?php
/*
 * Template Name: Home
 *
 * 
 *
 */
?>

<?php
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