<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_speakers');
	function register_post_type_speakers(){

		$labels = array(
			'name' => 'Speakers',
			'singular_name' => 'Speaker',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Speaker',
			'edit_item' => 'Edit Speaker',
			'new_item' => 'New Speaker',
			'view_item' => 'View Speaker',
			'search_items' => 'Search Speakers',
			'not_found' => 'Nothing found',
			'not_found_in_trash' => 'Nothing found in trash',
			'parent_item_colon' => ''
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title', 'editor', 'thumbnail')
		);

		register_post_type( 'speakers', $args);

	}

// DEFINE META BOXES
	$speakersMetaBoxArray = array(
	    "speakers_first_name_meta" => array(
	    	"id" => "speakers_first_name_meta",
	        "name" => "First Name",
	        "post_type" => "speakers",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "first_name"
	        )
	    ),
	    "speakers_last_name_meta" => array(
	    	"id" => "speakers_last_name_meta",
	        "name" => "Last Name",
	        "post_type" => "speakers",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "last_name"
	        )
	    ),
	    "speakers_company_meta" => array(
	    	"id" => "speakers_company_meta",
	        "name" => "Compnay",
	        "post_type" => "speakers",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "company"
	        )
	    ),
	    "speakers_work_title_meta" => array(
	    	"id" => "speakers_work_title_meta",
	        "name" => "Work Title",
	        "post_type" => "speakers",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "work_title"
	        )
	    ),
	);

// ADD META BOXES
	add_action( "admin_init", "admin_init_speakers" );
	function admin_init_speakers(){
		global $speakersMetaBoxArray;
		generateMetaBoxes($speakersMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_speakers');
	function save_speakers(){
		global $speakersMetaBoxArray;
		savePostData($speakersMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_speakers_submenu');

	function register_sortable_speakers_submenu() {
		add_submenu_page('edit.php?post_type=speakers', 'Sort Speakers', 'Sort', 'edit_pages', 'speakers_sort', 'sort_speakers');
	}

	function sort_speakers() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort Speakers</h2>';
		echo '</div>';

		listSpeakers('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "speakers_custom_columns");
	// add_filter("manage_edit-speakers_columns", "speakers_edit_columns");

	// function speakers_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Speaker Name",
	// 	);

	// 	return $columns;
	// }
	// function speakers_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listSpeakers($context, $idArray = null){
		global $post;
		global $speakersMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'speakers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				$loop = new WP_Query($args);

				echo '<ul class="sortable">';
				while ($loop->have_posts()) : $loop->the_post(); 
					$output = get_post_meta($post->ID, 'first_name', true) . " " . get_post_meta($post->ID, 'last_name', true);
					include(get_template_directory() . '/views/item_sortable.php');
				endwhile;
				echo '</ul>';
			break;
			
			case 'json':
				$args = array(
					'post_type'  => 'speakers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $speakersMetaBoxArray, 'json', 'speakers_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'speakers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $speakersMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'speakers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $speakersMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'speakers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $speakersMetaBoxArray, 'array');

				$field_options = array();
				foreach ($outputArray as $key => $value) {
					$checkBoxOption = array(
						"id" => $value['post_id'],
						"name" => $value['the_title'],
					);
					$field_options[] = $checkBoxOption;
				}

				return $field_options;

			break;

			case 'select':
				$args = array(
					'post_type'  => 'speakers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $speakersMetaBoxArray, 'array');

				$field_options = array();
				foreach ($outputArray as $key => $value) {
					$checkBoxOption = array(
						"id" => $value['post_id'],
						"name" => html_entity_decode($value['the_title'])
					);
					$field_options[] = $checkBoxOption;
				}

				return $field_options;

			break;
		}
	}

?>
