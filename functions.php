<?php

// LOAD CONSTANTS
    define( 'PAGEDIR', get_stylesheet_directory_uri() );

// LOAD SCRIPTS

    add_action( 'admin_init', 'loadScripts' );
    add_action( 'wp_enqueue_scripts', 'loadScripts' );

    function loadScripts() {
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

    	wp_register_script('timepicker', get_template_directory_uri() . '/machines/libraries/timepicker/timepicker.js', array('jquery-ui-datepicker'), '1.0', false );
    	wp_enqueue_script('timepicker');

    	wp_register_script('easyListSplitter', get_template_directory_uri() . '/machines/libraries/easyListSplitter/easyListSplitter.js', array('jquery-ui-datepicker'), '1.0', false );
    	wp_enqueue_script('easyListSplitter');

    	wp_register_script('moment', get_template_directory_uri() . '/machines/libraries/moment/moment.min.js', array('jquery-ui-datepicker'), '1.0', false );
    	wp_enqueue_script('moment');

		wp_enqueue_style('spectrum-style', get_template_directory_uri() . '/machines/libraries/spectrum/spectrum.css');
    	wp_register_script('spectrum', get_template_directory_uri() . '/machines/libraries/spectrum/spectrum.js', array('jquery'), '1.0', false );
    	wp_enqueue_script('spectrum');

    	wp_register_script('dynamics', get_template_directory_uri() . '/machines/dynamics.js', array('timepicker'), '1.0', false );
    	wp_enqueue_script('dynamics');

    }

// CUSTOM POST TYPE PEOPLE 
	include(get_template_directory() . '/machines/functions/custom_post_people.php');

// GENERATE META BOXES
	function generateMetaBoxes($arrayData){
		foreach ($arrayData as $value) {
			add_meta_box($value['id'], $value['name'], "populateMetaBoxes", $value['post_type'], $value['position'], $value['priority'], $value['callback_args']);
		}
	}

// POPULATE META BOXES
	function populateMetaBoxes($post, $arguments){
		$argumentData = $arguments['args'];
		switch ($argumentData['input_type']) {
			case 'input_text':
				$custom = get_post_custom($post->ID);
				$inputValue = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];
				include(get_template_directory() . '/views/input_text.php');
			break;

			case 'input_editor':
				$custom = get_post_custom($post->ID);
				$inputValue = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];

				wp_editor( $inputValue, $inputID );

				include(get_template_directory() . '/views/js_disable_meta_box_sortable.php');
			break;

			case 'input_checkbox_single':
				$custom = get_post_custom($post->ID);
				$inputValue = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];
				$inputText = $argumentData['input_text'];
				include(get_template_directory() . '/views/input_checkbox_single.php');
			break;

			case 'input_checkbox_multi':
				$custom = get_post_custom($post->ID);
				$inputValue = unserialize($custom[$argumentData['input_name']][0]);
				$inputID = $argumentData['input_name'];
				$inputText = $argumentData['input_text'];
				$field_options = call_user_func($argumentData['input_source'], 'checkbox');
				include(get_template_directory() . '/views/input_checkbox_multi.php');
			break;

			case 'input_select':
				$custom = get_post_custom($post->ID);
				$inputValue = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];
				$inputText = $argumentData['input_text'];
				$field_options = call_user_func($argumentData['input_source'], 'select');
				include(get_template_directory() . '/views/input_select.php');
			break;

			case 'input_date':
				$custom = get_post_custom($post->ID);
				$date = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];
				if($date != ""){
					$dateTimeReturn = new DateTime("@$date");
					$otherTZ  = new DateTimeZone('America/New_York');
					$dateTimeReturn->setTimezone($otherTZ);
					$dateTimeString = $dateTimeReturn->format('m/d/Y H:i');
				}
				include(get_template_directory() . '/views/input_date.php');
			break;

			case 'input_hidden':
				$custom = get_post_custom($post->ID);
				$inputValue = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];
				include(get_template_directory() . '/views/input_hidden.php');
			break;

			case 'input_colorpicker':
				$custom = get_post_custom($post->ID);
				$inputValue = $custom[$argumentData['input_name']][0];
				$inputID = $argumentData['input_name'];
				$paletteSelection = "";
				if(isset($argumentData['input_palette'])){
					foreach ($argumentData['input_palette'] as $key => $value) {
						$paletteSelection .= "'" . $value . "', ";
					}
				}
				include(get_template_directory() . '/views/input_colorpicker.php');
			break;

		}
	}

// SAVE POST DATA
	function savePostData($arrayData){
		global $post;
		global $wpdb;
		if(isset($arrayData)){
			foreach ($arrayData as $value) {
				update_post_meta($post->ID, $value['callback_args']['input_name'], $_POST[$value['callback_args']['input_name']]);
			}
		}
		if($wpdb->get_row("SELECT meta_value FROM wp_postmeta WHERE post_id = $post->ID AND meta_key = 'custom_order'") == null){
			update_post_meta($post->ID, "custom_order", '-1');
		}
	}

// RETURN DATA
	function returnData($loopArgs, $metaBoxData, $dataType, $jsonVarName = 'json_data', $taxonomyData = null){
		global $post;

		$loop = new WP_Query($loopArgs);
		$returnDataArray = array();
		
		foreach ($loop->posts as $key => $postValue) {
			$attachmentArray = getAttachmentArray($postValue->ID);

			$array = array(
				"post_id" => $postValue->ID,
				"the_title" => htmlspecialchars(get_the_title($postValue->ID), ENT_QUOTES),
				"the_content" => htmlspecialchars(get_post_field('post_content', $postValue->ID), ENT_QUOTES),
				"attachments" => $attachmentArray
			);

			if(!is_null($taxonomyData)){
				foreach ($taxonomyData as $value) {
					$array[$value] = getTaxonomyData($postValue->ID, $value);
				}
			}

			if(isset($metaBoxData)){
				foreach ($metaBoxData as $value) {
					$valueData = get_post_meta($postValue->ID, $value['callback_args']['input_name'], true);
					if(is_array($valueData)){
						$array[$value['callback_args']['input_name']] = $valueData;
					} else {
						$jsonTest = json_decode($valueData);
						if (($jsonTest != null) && (!is_numeric($jsonTest))) {
							$array[$value['callback_args']['input_name']] = json_decode($valueData);
						} else {
							$array[$value['callback_args']['input_name']] = htmlspecialchars($valueData, ENT_QUOTES);
						}
					}
				}
			}
			$returnDataArray[] = $array;
		}
		
		switch ($dataType) {
			case 'json':
				populateJSON($returnDataArray, $jsonVarName);
			break;
			
			case 'array':
				return $returnDataArray;
			break;
			
			default:
				print_r($returnDataArray);
			break;
		}
	}

// POPULATE JAVASCRIPT VARIABLE WITH JSON DATA
	function populateJSON($jsonData, $variableName){
		include(get_template_directory() . '/views/js_json.php');
	}

// POPULATE JAVASCRIPT VARIABLE WITH JSON DATA
	function populateJavascript($javascriptData, $variableName){
		include(get_template_directory() . '/views/js_javascript.php');
	}

// GET ATTACHMENTS AS AN ARRAY
	function getAttachmentArray($postID){
		$args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' =>'any', 'post_parent' => $postID ); 
		$attachments = get_posts( $args );
		if ( $attachments ) {
			$attachment_array = array();
			foreach ( $attachments as $attachment ) {
				$thumbNailArray = wp_get_attachment_image_src( $attachment->ID , 'thumbnail' );
				$fullSizeImage = wp_get_attachment_url( $attachment->ID , false );
				$attachmentData = array(
				    "full" => $fullSizeImage,
				    "thumb" => $thumbNailArray[0],
				);				
				$attachment_array[] = $attachmentData;
			}
		}
		return $attachment_array;
	}

// AJAX SORT
	add_action('wp_ajax_update_sort', 'updateSortAjax');

	function updateSortAjax() {
		global $wpdb;
		$sort_data = $_POST['sort_data'];
		foreach ($sort_data as $key => $value) {
			if( $wpdb->update('wp_postmeta',array('meta_value' => $value),array( 'post_id' => $key, 'meta_key' => 'custom_order' )) === FALSE){
				echo "error";
			} else {
				echo $key . ";" . $value . "\n";
			}
		}
		die();
	}

// GET TAXONOMY DATA
	function getTaxonomyData($postID, $taxonomyName){
		$terms = get_the_terms($postID, $taxonomyName);
		$returnDataArray = array();
		if($terms != ""){
			foreach ($terms as $term) {
				$returnDataArray[] = htmlspecialchars($term->name, ENT_QUOTES);
			}
		}
		return $returnDataArray;
	}

// CUSTOMIZE TINY MCE
	add_filter('tiny_mce_before_init', 'myformatTinyMCE' );
	function myformatTinyMCE($in){
		$in['wpautop']=false;
		return $in;
	}

// ADD MAIN MENU
	add_action( 'init', 'register_main_menu' );
	function register_main_menu() {
		register_nav_menus(
			array(
				'header-menu' => __( 'Header Menu' ),
				'extra-menu' => __( 'Extra Menu' )
				)
			);
	}

// GENEREATE PAGES JSON
	function generatePagesJSON($currentPageID){
		$allPageIDs = get_all_page_ids();
		$returnArray = array();
		foreach ($allPageIDs as $value) {
			if($currentPageID == $value){
				$current_page = true;
			} else {
				$current_page = false;
			}

			$itemArray = array(
				'wp_page_id' => $value,
				'pageID' => sanitize_title(get_the_title($value)),
				'current_page' => $current_page,
				'pageTitle' => get_the_title($value)
			);
			$returnArray[] = $itemArray;
		}
		populateJSON($returnArray, 'pages');
	}

// INCLUDE POST THUMBNAILS
	add_action( 'after_setup_theme', 'includePostThumbnails' );

	function includePostThumbnails(){
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 348, 277, false );
	}

// HIDE ADMIN BAR ON FRONT ENT
	add_filter( 'show_admin_bar' , 'my_function_admin_bar');
	function my_function_admin_bar(){
		return false;
	}

?>