<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_speakers_type');
	function register_post_type_speakers_type(){

		$labels = array(
			'name' => 'Speakers_type',
			'singular_name' => 'Speaker_type',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Speaker_type',
			'edit_item' => 'Edit Speaker_type',
			'new_item' => 'New Speaker_type',
			'view_item' => 'View Speaker_type',
			'search_items' => 'Search Speakers_type',
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
			'supports' => array('title', 'editor')
		);

		register_post_type( 'speakers_type', $args);

	}

// DEFINE META BOXES
	$speakers_typeMetaBoxArray = array();

// ADD META BOXES
	add_action( "admin_init", "admin_init_speakers_type" );
	function admin_init_speakers_type(){
		global $speakers_typeMetaBoxArray;
		generateMetaBoxes($speakers_typeMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_speakers_type');
	function save_speakers_type(){
		global $speakers_typeMetaBoxArray;
		savePostData($speakers_typeMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_speakers_type_submenu');

	function register_sortable_speakers_type_submenu() {
		add_submenu_page('edit.php?post_type=speakers_type', 'Sort Speakers_type', 'Sort', 'edit_pages', 'speakers_type_sort', 'sort_speakers_type');
	}

	function sort_speakers_type() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort Speakers_type</h2>';
		echo '</div>';

		listSpeakers_type('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "speakers_type_custom_columns");
	// add_filter("manage_edit-speakers_type_columns", "speakers_type_edit_columns");

	// function speakers_type_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Speaker_type Name",
	// 	);

	// 	return $columns;
	// }
	// function speakers_type_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listSpeakers_type($context, $idArray = null){
		global $post;
		global $speakers_typeMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'speakers_type',
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
					'post_type'  => 'speakers_type',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $speakers_typeMetaBoxArray, 'json', 'speakers_type_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'speakers_type',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $speakers_typeMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'speakers_type',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $speakers_typeMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'speakers_type',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $speakers_typeMetaBoxArray, 'array');

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
					'post_type'  => 'speakers_type',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $speakers_typeMetaBoxArray, 'array');

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
