<?php

/**
 * Class Tribe__Events__Filterbar__Filters__Category
 */
class Tribe__Events__Filterbar__Filters__Category extends Tribe__Events__Filterbar__Filter {
	public $type = 'select';

	public function get_admin_form() {
		$title = $this->get_title_field();
		$type = $this->get_multichoice_type_field();
		return $title.$type;
	}

	protected function get_values() {
		$terms = array();

		// Load all available event categories
		$source = get_terms( Tribe__Events__Main::TAXONOMY, array( 'orderby' => 'name', 'order' => 'ASC' ) );
		if ( empty( $source ) || is_wp_error( $source ) ) return array();

		// Preprocess the terms
		foreach ( $source as $term ) {
			$terms[ (int) $term->term_id ] = $term;
			$term->parent = (int) $term->parent;
			$term->depth = 0;
			$term->children = array();
		}

		// Initally copy the source list of terms to our ordered list
		$ordered_terms = $terms;

		// Re-order!
		foreach ( $terms as $id => $term ) {
			// Skip root elements
			if ( 0 === $term->parent ) continue;

			// Reposition child terms within the ordered terms list
			unset( $ordered_terms[ $id ] );
			$term->depth = $terms[ $term->parent ]->depth + 1;
			$terms[ $term->parent ]->children[ $id ] = $term;
		}

		// Finally flatten out and return
		return $this->flattened_term_list( $ordered_terms );
	}

	/**
	 * Flatten out the hierarchical list of event categories into a single list of values,
	 * applying formatting (non-breaking spaces) to help indicate the depth of each nested
	 * item.
	 *
	 * @param array $term_items
	 * @param array $existing_list
	 * @return array
	 */
	protected function flattened_term_list( array $term_items, array $existing_list = null ) {
		// Pull in the existing list when called recursively
		$flat_list = is_array( $existing_list ) ? $existing_list : array();

		// Add each item - including nested items - to the flattened list
		foreach ( $term_items as $term ) {
			$flat_list[] = array(
				'name'  => str_repeat( '&nbsp;', $term->depth * 2 ) . $term->name,
				'value' => $term->term_id,
				'data'  => array( 'slug' => $term->slug ),
				'class' => 'tribe-events-category-' . $term->slug,
			);

			if ( ! empty( $term->children ) ) {
				$child_items = $this->flattened_term_list( $term->children, $existing_list );
				$flat_list = array_merge( $flat_list, $child_items );
			}
		}

		return $flat_list;
	}

	/**
	 * This method will only be called when the user has applied the filter (during the
	 * tribe_events_pre_get_posts action) and sets up the taxonomy query, respecting any
	 * other taxonomy queries that might already have been setup (whether by The Events
	 * Calendar, another plugin or some custom code, etc).
	 *
	 * @see Tribe__Events__Filterbar__Filter::pre_get_posts()
	 *
	 * @param WP_Query $query
	 */
	protected function pre_get_posts( WP_Query $query ) {
		$new_rules      = array();
		$existing_rules = (array) $query->get( 'tax_query' );
		$values         = (array) $this->currentValue;

		$new_rules[] = array(
			'taxonomy' => Tribe__Events__Main::TAXONOMY,
			'operator' => 'IN',
			'terms'    => $values,
		);

		/**
		 * Controls the relationship between different taxonomy queries.
		 *
		 * If set to an empty value, then no attempt will be made by the additional field filter
		 * to set the meta_query "relation" parameter.
		 *
		 * @var string $relation "AND"|"OR"
		 */
		$relationship = apply_filters( 'tribe_events_filter_taxonomy_relationship', 'AND' );

		/**
		 * If taxonomy filter meta queries should be nested and grouped together.
		 *
		 * The default is true in WordPress 4.1 and greater, which allows for greater flexibility
		 * when combined with taxonomy queries added by other filters/other plugins.
		 *
		 * @var bool $group
		 */
		$nest = apply_filters( 'tribe_events_filter_nest_taxonomy_queries',
			version_compare( $GLOBALS['wp_version'], '4.1', '>=' )
		);

		if ( $nest ) {
			$new_rules = array(
				__CLASS__ => $new_rules,
			);
		}

		$tax_query = array_merge_recursive( $existing_rules, $new_rules );

		// Apply the relationship (we leave this late, or the recursive array merge would potentially cause duplicates)
		if ( ! empty( $relationship ) && $nest ) {
			$tax_query[ __CLASS__ ][ 'relation' ] = $relationship;
		} elseif ( ! empty( $relationship ) ) {
			$tax_query[ 'relation' ] = $relationship;
		}

		// Apply our new meta query rules
		$query->set( 'tax_query', $tax_query );
	}
}
