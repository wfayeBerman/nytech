<?php

// REGISTER CUSTOM POST TYPE
	add_action( 'init', 'register_post_type_influencers');
	function register_post_type_influencers(){

		$labels = array(
			'name' => 'Influencers',
			'singular_name' => 'Influencer',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Influencer',
			'edit_item' => 'Edit Influencer',
			'new_item' => 'New Influencer',
			'view_item' => 'View Influencer',
			'search_items' => 'Search Influencers',
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

		register_post_type( 'influencers', $args);

	}

// DEFINE META BOXES
	$influencersMetaBoxArray = array();

// ADD META BOXES
	add_action( "admin_init", "admin_init_influencers" );
	function admin_init_influencers(){
		global $influencersMetaBoxArray;
		generateMetaBoxes($influencersMetaBoxArray);
	}

// SAVE POST TO DATABASE
	add_action('save_post', 'save_influencers');
	function save_influencers(){
		global $influencersMetaBoxArray;
		savePostData($influencersMetaBoxArray, $post, $wpdb);
	}

// SORTING CUSTOM SUBMENU

	add_action('admin_menu', 'register_sortable_influencers_submenu');

	function register_sortable_influencers_submenu() {
		add_submenu_page('edit.php?post_type=influencers', 'Sort Influencers', 'Sort', 'edit_pages', 'influencers_sort', 'sort_influencers');
	}

	function sort_influencers() {
		
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Sort Influencers</h2>';
		echo '</div>';

		listInfluencers('sort');
	}

// CUSTOM COLUMNS

	// add_action("manage_posts_custom_column",  "influencers_custom_columns");
	// add_filter("manage_edit-influencers_columns", "influencers_edit_columns");

	// function influencers_edit_columns($columns){
	// 	$columns = array(
	// 		"full_name" => "Influencer Name",
	// 	);

	// 	return $columns;
	// }
	// function influencers_custom_columns($column){
	// 	global $post;

	// 	switch ($column) {
	// 		case "full_name":
	// 			$custom = get_post_custom();
	// 			echo "<a href='post.php?post=" . $post->ID . "&action=edit'>" . $custom["first_name"][0] . " " . $custom["last_name"][0] . "</a>";
	// 		break;
	// 	}
	// }

// LISTING FUNCTION
	function listInfluencers($context, $idArray = null){
		global $post;
		global $influencersMetaBoxArray;
		
		switch ($context) {
			case 'sort':
				$args = array(
					'post_type'  => 'influencers',
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
					'post_type'  => 'influencers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);
				returnData($args, $influencersMetaBoxArray, 'json', 'influencers_data');
			break;

			case 'array':
				$args = array(
					'post_type'  => 'influencers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $influencersMetaBoxArray, 'array');
			break;

			case 'rest':
				$args = array(
					'post_type'  => 'influencers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true,
					'post__in' => $idArray
				);
				return returnData($args, $influencersMetaBoxArray, 'array');
			break;

			case 'checkbox':
				$args = array(
					'post_type'  => 'influencers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $influencersMetaBoxArray, 'array');

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
					'post_type'  => 'influencers',
					'order'   => 'ASC',
					'meta_key'  => 'custom_order',
					'orderby'  => 'meta_value_num',
					'nopaging' => true
				);

				$outputArray = returnData($args, $influencersMetaBoxArray, 'array');

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
