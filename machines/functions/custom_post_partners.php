<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_partners');
	function register_post_type_partners(){

		$labels = array(
			'name' => 'Partners',
			'singular_name' => 'Partner',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Partner',
			'edit_item' => 'Edit Partner',
			'new_item' => 'New Partner',
			'view_item' => 'View Partner',
			'search_items' => 'Search Partners',
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

		register_post_type( 'partners', $args);

	}

// DEFINE META BOXES
	$partnersMetaBoxArray = array(
	    "partners_website_meta" => array(
	    	"id" => "partners_website_meta",
	        "name" => "Website",
	        "post_type" => "partners",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "website"
	        )
	    ),
	    "partners_partnership_type_meta" => array(
	    	"id" => "partners_partnership_type_meta",
	        "name" => "Partnership Type",
	        "post_type" => "partners",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_select",
	        	"input_source" => "listPartnershipTypes",
	        	"input_name" => "partnership_type"
	        )
	    ),

	);

// ADD META BOXES
	add_action( "admin_init", "admin_init_partners" );
	function admin_init_partners(){
		global $partnersMetaBoxArray;
		generateMetaBoxes($partnersMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_partners');
	function save_partners(){
		global $partnersMetaBoxArray;
		savePostData($partnersMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_partners_submenu');

	function register_sortable_partners_submenu() {
		add_submenu_page('edit.php?post_type=partners', 'Sort Partners', 'Sort', 'edit_pages', 'partners_sort', 'sort_partners');
	}

	function sort_partners() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort Partners</h2>';
		echo '</div>';

		listPartners('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "partners_custom_columns");
	// add_filter("manage_edit-partners_columns", "partners_edit_columns");

	// function partners_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Partner Name",
	// 	);

	// 	return $columns;
	// }
	// function partners_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listPartners($context, $idArray = null){
		global $post;
		global $partnersMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'partners',
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
					'post_type'  => 'partners',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $partnersMetaBoxArray, 'json', 'partners_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'partners',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $partnersMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'partners',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $partnersMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'partners',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $partnersMetaBoxArray, 'array');

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
					'post_type'  => 'partners',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $partnersMetaBoxArray, 'array');

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
