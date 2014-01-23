<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_people');
	function register_post_type_people(){

		$labels = array(
			'name' => 'People',
			'singular_name' => 'Person',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Person',
			'edit_item' => 'Edit Person',
			'new_item' => 'New Person',
			'view_item' => 'View Person',
			'search_items' => 'Search People',
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

		register_post_type( 'people', $args);

	}

// DEFINE META BOXES
	$peopleMetaBoxArray = array(
	    "people_sample_text_meta" => array(
	    	"id" => "people_sample_text_meta",
	        "name" => "Sample Text",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "sample_text"
	        )
	    ),
	    "people_sample_date_meta" => array(
	    	"id" => "people_sample_date_meta",
	        "name" => "Sample Date",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_date",
	        	"input_name" => "sample_date"
	        )
	    ),
	    "people_sample_color_meta" => array(
	    	"id" => "people_sample_color_meta",
	        "name" => "Sample Color",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_colorpicker",
	        	"input_name" => "sample_color",
	        	"input_palette" => array(
	        		'rgb(0, 59, 168);',
	        		'rgb(102, 153, 51);',
					'rgb(53, 109, 211);',
					'rgb(95, 136, 211);',
	        	)
	        )
	    ),
	    "people_sample_editor_meta" => array(
	    	"id" => "people_sample_editor_meta",
	        "name" => "Sample Editor",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_editor",
	        	"input_name" => "sample_editor"
	        )
	    ),
	    "people_sample_checkbox_multi_meta" => array(
	    	"id" => "people_sample_checkbox_multi_meta",
	        "name" => "Sample Checkbox Multi",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_checkbox_multi",
	        	"input_source" => "listPeople",
	        	"input_name" => "sample_checkbox_multi"
	        )
	    ),
	    "people_sample_select_meta" => array(
	    	"id" => "people_sample_select_meta",
	        "name" => "Sample Select",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_select",
	        	"input_source" => "listPeople",
	        	"input_name" => "sample_select"
	        )
	    ),
	    "people_sample_checkbox_single_meta" => array(
	    	"id" => "people_sample_checkbox_single_meta",
	        "name" => "Sample Checkbox Single",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_checkbox_single",
	        	"input_name" => "people_sample_checkbox_single",
	        	"input_text" => "Sample Option"
	        )
	    ),
	    "people_sample_hidden_meta" => array(
	    	"id" => "people_sample_hidden_meta",
	        "name" => "Sample Hidden",
	        "post_type" => "people",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_hidden",
	        	"input_name" => "people_sample_hidden"
	        )
	    ),


	);

// ADD META BOXES
	add_action( "admin_init", "admin_init_people" );
	function admin_init_people(){
		global $peopleMetaBoxArray;
		generateMetaBoxes($peopleMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_people');
	function save_people(){
		global $peopleMetaBoxArray;
		savePostData($peopleMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_people_submenu');

	function register_sortable_people_submenu() {
		add_submenu_page('edit.php?post_type=people', 'Sort People', 'Sort', 'edit_pages', 'people_sort', 'sort_people');
	}

	function sort_people() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort People</h2>';
		echo '</div>';

		listPeople('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "people_custom_columns");
	// add_filter("manage_edit-people_columns", "people_edit_columns");

	// function people_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Person Name",
	// 	);

	// 	return $columns;
	// }
	// function people_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listPeople($context, $idArray = null){
		global $post;
		global $peopleMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'people',
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
					'post_type'  => 'people',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $peopleMetaBoxArray, 'json', 'people_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'people',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $peopleMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'people',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $peopleMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'people',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $peopleMetaBoxArray, 'array');

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
					'post_type'  => 'people',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $peopleMetaBoxArray, 'array');

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
