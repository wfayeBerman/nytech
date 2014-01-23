<?php

	require_once realpath(dirname(__FILE__).'/../../../../..').'/wp-load.php';
	echo get_post_field('post_content', $_POST['pageID']);



?>