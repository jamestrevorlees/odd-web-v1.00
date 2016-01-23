<?php
/**
 * Prodo
 * WordPress Theme
 *
 * Web: https://www.facebook.com/a.axminenko
 * Email: a.axminenko@gmail.com
 *
 * Copyright 2015 Alexander Axminenko
 */

class ProdoPosts {
	# Initialization
	public static function init( ) {
		# Register Our Clients
		$args = array(
			'labels'             => array(
				'name'                 => __( 'Clients', 'prodo' ),
				'singular_name'        => __( 'Clients', 'prodo' ),
				'add_new'              => __( 'Add New', 'prodo' ),
				'add_new_item'         => __( 'Add New Client', 'prodo' ),
				'edit_item'            => __( 'Edit Client', 'prodo' ),
				'new_item'             => __( 'New Client', 'prodo' ),
				'all_items'            => __( 'All Clients', 'prodo' ),
				'view_item'            => __( 'View Clients', 'prodo' ),
				'search_items'         => __( 'Search Clients', 'prodo' ),
				'not_found'            => __( 'No Client found', 'prodo' ),
				'not_found_in_trash'   => __( 'No Client found in Trash', 'prodo' ),
				'menu_name'            => __( 'Our Clients', 'prodo' ),
				'parent_item_colon'    => '',
			),
			'exclude_from_search' => true,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => __( 'our-clients', 'prodo' ) ),
			'capability_type'     => 'page',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => null,
			'menu_icon'           => 'dashicons-heart',
			'supports'            => array( 'title', 'thumbnail' )
		); 
		register_post_type( 'our-clients', $args );

		# Register Our Team
		$args = array(
			'labels'             => array(
				'name'                 => __( 'Our Team', 'prodo' ),
				'singular_name'        => __( 'Our Team', 'prodo' ),
				'add_new'              => __( 'Add New', 'prodo' ),
				'add_new_item'         => __( 'Add New Member', 'prodo' ),
				'edit_item'            => __( 'Edit Member', 'prodo' ),
				'new_item'             => __( 'New Member', 'prodo' ),
				'all_items'            => __( 'All Members', 'prodo' ),
				'view_item'            => __( 'View Members', 'prodo' ),
				'search_items'         => __( 'Search Members', 'prodo' ),
				'not_found'            => __( 'No Member found', 'prodo' ),
				'not_found_in_trash'   => __( 'No Member found in Trash', 'prodo' ),
				'menu_name'            => __( 'Our Team', 'prodo' ),
				'parent_item_colon'    => '',
			),
			'exclude_from_search' => true,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => __( 'our-team', 'prodo' ) ),
			'capability_type'     => 'page',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => null,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail' ),
		); 
		register_post_type( 'our-team', $args );

		# Register Portfolio Taxonomy
		$args = array(
			'hierarchical'       => true,
			'labels'             => array(
				'name'                => __( 'Category', 'prodo' ),
				'singular_name'       => __( 'Category', 'prodo' ),
				'search_items'        => __( 'Search Categories', 'prodo' ),
				'all_items'           => __( 'All Categories', 'prodo' ),
				'parent_item'         => __( 'Parent Category', 'prodo' ),
				'parent_item_colon'   => __( 'Parent Category:', 'prodo' ),
				'edit_item'           => __( 'Edit Category', 'prodo' ), 
				'update_item'         => __( 'Update Category', 'prodo' ),
				'add_new_item'        => __( 'Add New Category', 'prodo' ),
				'new_item_name'       => __( 'New Category', 'prodo' ),
				'menu_name'           => __( 'Categories', 'prodo' ),
			),
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'portfolio-category' )
		);
		register_taxonomy( 'portfolio-category', 'portfolio', $args );

		# Register Portfolio
		$args = array(
			'labels'             => array(
				'name'                 => __( 'Portfolio', 'prodo' ),
				'singular_name'        => __( 'Portfolio', 'prodo' ),
				'add_new'              => __( 'Add New', 'prodo' ),
				'add_new_item'         => __( 'Add New Item', 'prodo' ),
				'edit_item'            => __( 'Edit Item', 'prodo' ),
				'new_item'             => __( 'New Item', 'prodo' ),
				'all_items'            => __( 'All Items', 'prodo' ),
				'view_item'            => __( 'View Items', 'prodo' ),
				'search_items'         => __( 'Search Items', 'prodo' ),
				'not_found'            => __( 'No Item found', 'prodo' ),
				'not_found_in_trash'   => __( 'No Item found in Trash', 'prodo' ),
				'menu_name'            => __( 'Portfolio', 'prodo' ),
				'parent_item_colon'    => '',
			),
			'exclude_from_search' => true,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => __( 'portfolio', 'prodo' ) ),
			'capability_type'     => 'page',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => null,
			'menu_icon'           => 'dashicons-portfolio',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'portfolio-category' ),
			'taxonomies'          => array( 'portfolio-category' ),
		);
		register_post_type( 'portfolio', $args );
	}
}

add_action( 'init', array( 'ProdoPosts', 'init' ) );