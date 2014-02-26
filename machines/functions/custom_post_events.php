<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_events');
	function register_post_type_events(){

		$labels = array(
			'name' => 'Events',
			'singular_name' => 'Event',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Event',
			'edit_item' => 'Edit Event',
			'new_item' => 'New Event',
			'view_item' => 'View Event',
			'search_items' => 'Search Events',
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

		register_post_type( 'events', $args);

	}

// DEFINE META BOXES
	$eventsMetaBoxArray = array(
	    "events_event_start_meta" => array(
	    	"id" => "events_event_start_meta",
	        "name" => "Event Start",
	        "post_type" => "events",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_date",
	        	"input_name" => "event_start"
	        )
	    ),
	    "events_event_end_meta" => array(
	    	"id" => "events_event_end_meta",
	        "name" => "Event End",
	        "post_type" => "events",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_date",
	        	"input_name" => "event_end"
	        )
	    ),
	    "events_event_speakers_meta" => array(
	    	"id" => "events_event_speakers_meta",
	        "name" => "Event Speakers",
	        "post_type" => "events",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_checkbox_multi",
	        	"input_source" => "listSpeakers",
	        	"input_name" => "event_speakers"
	        )
	    ),
	);

// ADD META BOXES
	add_action( "admin_init", "admin_init_events" );
	function admin_init_events(){
		global $eventsMetaBoxArray;
		generateMetaBoxes($eventsMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_events');
	function save_events(){
		global $eventsMetaBoxArray;
		savePostData($eventsMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_events_submenu');

	function register_sortable_events_submenu() {
		add_submenu_page('edit.php?post_type=events', 'Sort Events', 'Sort', 'edit_pages', 'events_sort', 'sort_events');
	}

	function sort_events() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort Events</h2>';
		echo '</div>';

		listEvents('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "events_custom_columns");
	// add_filter("manage_edit-events_columns", "events_edit_columns");

	// function events_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Event Name",
	// 	);

	// 	return $columns;
	// }
	// function events_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listEvents($context, $idArray = null){
		global $post;
		global $eventsMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'events',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				$loop = new WP_Query($args);

				echo '<ul class="sortable">';
				while ($loop->have_posts()) : $loop->the_post(); 
					$output = get_the_title( $post->ID );
					// $output = get_post_meta($post->ID, 'first_name', true) . " " . get_post_meta($post->ID, 'last_name', true);
					include(get_template_directory() . '/views/item_sortable.php');
				endwhile;
				echo '</ul>';
			break;
			
			case 'json':
				$args = array(
					'post_type'  => 'events',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $eventsMetaBoxArray, 'json', 'events_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'events',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $eventsMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'events',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $eventsMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'events',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $eventsMetaBoxArray, 'array');

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
					'post_type'  => 'events',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $eventsMetaBoxArray, 'array');

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
