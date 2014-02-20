<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_partnership_types');
	function register_post_type_partnership_types(){

		$labels = array(
			'name' => 'Partnership Types',
			'singular_name' => 'Partnership Type',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Partnership Type',
			'edit_item' => 'Edit Partnership Type',
			'new_item' => 'New Partnership Type',
			'view_item' => 'View Partnership Type',
			'search_items' => 'Search Partnership Types',
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

		register_post_type( 'partnership_types', $args);

	}

// DEFINE META BOXES
	$partnership_typesMetaBoxArray = array(
	    "partnership_types_price_meta" => array(
	    	"id" => "partnership_types_price_meta",
	        "name" => "Price",
	        "post_type" => "partnership_types",
	        "position" => "side",
	        "priority" => "low",
	        "callback_args" => array(
	        	"input_type" => "input_text",
	        	"input_name" => "price"
	        )
	    ),
	);

// ADD META BOXES
	add_action( "admin_init", "admin_init_partnership_types" );
	function admin_init_partnership_types(){
		global $partnership_typesMetaBoxArray;
		generateMetaBoxes($partnership_typesMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_partnership_types');
	function save_partnership_types(){
		global $partnership_typesMetaBoxArray;
		savePostData($partnership_typesMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_partnership_types_submenu');

	function register_sortable_partnership_types_submenu() {
		add_submenu_page('edit.php?post_type=partnership_types', 'Sort Partnership Types', 'Sort', 'edit_pages', 'partnership_types_sort', 'sort_partnership_types');
	}

	function sort_partnership_types() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort Partnership Types</h2>';
		echo '</div>';

		listPartnershipTypes('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "partnership_types_custom_columns");
	// add_filter("manage_edit-partnership_types_columns", "partnership_types_edit_columns");

	// function partnership_types_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Partnership Type Name",
	// 	);

	// 	return $columns;
	// }
	// function partnership_types_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listPartnershipTypes($context, $idArray = null){
		global $post;
		global $partnership_typesMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'partnership_types',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				$loop = new WP_Query($args);

				echo '<ul class="sortable">';
				while ($loop->have_posts()) : $loop->the_post(); 
					// $output = get_post_meta($post->ID, 'first_name', true) . " " . get_post_meta($post->ID, 'last_name', true);
					$output = get_the_title();
					include(get_template_directory() . '/views/item_sortable.php');
				endwhile;
				echo '</ul>';
			break;
			
			case 'json':
				$args = array(
					'post_type'  => 'partnership_types',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $partnership_typesMetaBoxArray, 'json', 'partnership_types_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'partnership_types',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $partnership_typesMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'partnership_types',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $partnership_typesMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'partnership_types',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $partnership_typesMetaBoxArray, 'array');

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
					'post_type'  => 'partnership_types',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $partnership_typesMetaBoxArray, 'array');

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
