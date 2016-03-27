<?php
/**
 * Controls each individual filter.
 * Each filter is an instance of this class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) { die( '-1' ); }

if ( ! class_exists( 'Tribe__Events__Filterbar__Filter' ) ) {
	class Tribe__Events__Filterbar__Filter {

		/**
		 * @var string The type of filter.
		 */
		public $type = 'checkbox';

		/**
		 * @var string The name of the filter.
		 */
		public $name;

		/**
		 * @var string The filter slug.
		 */
		public $slug;

		/**
		 * @var int The priority (order) of the filter.
		 */
		public $priority;

		/**
		 * @var array The possible values for the filter.
		 */
		public $values;

		/**
		 * @var mixed The current selected value.
		 */
		public $currentValue;

		/**
		 * @var bool If the filter is currently active.
		 */
		public $isActiveFilter = false;

		/**
		 * @var array The query args the filter should add.
		 */
		public $queryArgs = array();

		public $joinClause = '';
		public $whereClause = '';

		/**
		 * @param string $name The name of the filter.
		 * @param string $slug The filter's slug.
		 */
		public function __construct( $name, $slug ) {
			$this->name = $name;
			$this->slug = $slug;

			$this->settings();
			$this->priority = $this->get_priority();
			$this->isActiveFilter = $this->is_active();
			$this->currentValue = $this->get_submitted_value();

			$this->setup_query_filters();
			$this->addHooks();
		}

		protected function get_submitted_value() {
			if ( isset( $_REQUEST[ 'tribe_' . $this->slug ] ) ) {
				return $_REQUEST[ 'tribe_' . $this->slug ];
			}
			return null;
		}

		/**
		 * Add the necessary action and filter hooks.
		 *
		 * @return void
		 */
		public function addHooks() {
			if ( $this->is_filtering() ) {
				add_action( 'tribe_events_filter_view_do_display_filters', array( $this, 'displayFilter' ), $this->priority );
				add_action( 'tribe_events_pre_get_posts', array( $this, 'addQueryArgs' ), 10 );
			}
			add_filter( 'tribe_events_all_filters_array', array( $this, 'allFiltersArray' ), 10, 1 );
		}

		public function is_filtering() {
			if ( ! $this->isActiveFilter ) {
				return false;
			}

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return true;
			}
			return ! is_admin();
		}

		/**
		 * Add the proper query arguments to the query.
		 *
		 * @param WP_Query $query
		 * @return void
		 */
		public function addQueryArgs( $query ) {

			if ( ! $query->tribe_is_event ) {
				// don't add the query args to other queries besides events queries
				return;
			}

			// we only want to apply the filters to a default render context
			if ( 'default' !== tribe_get_render_context( $query ) ) {
				return;
			}

			if ( isset( $_REQUEST[ 'tribe_' . $this->slug ] ) && $_REQUEST[ 'tribe_' . $this->slug ] != '' ) {
				if ( ! empty( $this->joinClause ) ) {
					add_filter( 'posts_join', array( $this, 'addQueryJoin' ), 11, 2 );
				}
				if ( ! empty( $this->whereClause ) ) {
					add_filter( 'posts_where', array( $this, 'addQueryWhere' ), 11, 2 );
				}
				foreach ( $this->queryArgs as $key => $value ) {
					$query->set( $key, $value );
				}

				$this->pre_get_posts( $query );
			}
		}

		/**
		 * Provides an opportunity for filter implementations to modify the query object directly
		 * when required: it will only be called from addQueryArgs() within the appropriate query
		 * render context and when the user has actually applied the filter.
		 *
		 * @param WP_Query $query
		 */
		protected function pre_get_posts( WP_Query $query ) {}

		/**
		 * Add the proper where clause to the query.
		 *
		 * @param string $posts_where The current WHERE clause.
		 * @param object $query The current query.
		 * @return string The new WHERE clause.
		 */
		public function addQueryWhere( $posts_where, $query ) {
			// Make sure it's an events query.
			if ( $query->tribe_is_event || $query->tribe_is_event_category ) {
				$posts_where .= $this->whereClause;
			}
			remove_filter( 'posts_where', array( $this, 'addQueryWhere' ), 11, 2 );
			return $posts_where;
		}

		/**
		 * Add the proper join clause to the query.
		 *
		 * @param string $posts_join The current JOIN clause.
		 * @param object $query The current query.
		 * @return string The new JOIN clause.
		 */
		public function addQueryJoin( $posts_join, $query ) {
			// Make sure it's an events query.
			if ( $query->tribe_is_event || $query->tribe_is_event_category ) {
				$posts_join .= $this->joinClause;
			}
			remove_filter( 'posts_join', array( $this, 'addQueryJoin' ), 11, 2 );
			return $posts_join;
		}

		/**
		 * Display the given filter in the list on the frontend.
		 *
		 * @return void
		 */
		public function displayFilter() {
			$values = apply_filters( 'tribe_events_filter_values', $this->get_values(), $this->slug );

			if ( ! empty( $values ) ) {
				?>
				<div class="tribe_events_filter_item<?php echo tribe_get_option( 'events_filters_layout', 'vertical' ) == 'horizontal' ? ' closed' : ''; ?>" id="tribe_events_filter_item_<?php echo esc_attr( $this->slug ); ?>">
				<?php
				switch ( $this->type ) {
					case 'select':
						// It's possible for multiple values to be specified in array form, but we can
						// only use one of those for the select filter
						$current_value = is_array( $this->currentValue ) ? current( $this->currentValue ) : $this->currentValue;
						?>
						<h3 class="tribe-events-filters-group-heading"><span></span><?php echo stripslashes( $this->title ); ?></h3>
						<div class="tribe-events-filter-group tribe-events-filter-select">
							<select name="<?php echo esc_attr( 'tribe_' . $this->slug ); ?>">
								<option value="" <?php selected( trim( $current_value ), '' ) ?>><?php esc_html_e( 'Select', 'tribe-events-filter-view' ); ?></option>
								<?php foreach ( $values as $option ):

								$data = array();
								if ( isset( $option['data'] ) && is_array( $option['data'] ) ) {
									foreach ( $option['data'] as $attr => $value ) {
										$data[] = 'data-' . esc_attr( $attr ) . '="' . trim( $value ) . '"';
									}
								}
								$data = join( ' ', $data );

								// output option to screen
								printf( '<option value="%s" %s %s >%s</option>',
									esc_attr( $option['value'] ),
									selected( trim( $current_value ), trim( $option['value'] ), false ),
									$data,
									esc_html( stripslashes( $option['name'] ) )
									);

								?>
								<?php
							endforeach ?>
							</select>
						</div>
		                <?php
					break;
					//Option for Chosen Dropdown
					case 'autocomplete':
						if ( ! isset( $this->currentValue ) )
							$this->currentValue = array();
						?>
						<h3 class="tribe-events-filters-group-heading"><span></span><?php echo stripslashes( $this->title ); ?></h3>
						<div class="tribe-events-filter-group tribe-events-filter-autocomplete">
							<select
								data-no-results-text="<?php esc_attr_e(  'No items match', 'tribe-events-filter-view' ); ?>"
								data-placeholder="<?php esc_attr_e(  'Select an Item', 'tribe-events-filter-view' ); ?>" multiple class="chosen-dropdown"
								name="<?php echo esc_attr( 'tribe_' . $this->slug . '[]' ); ?>">
								<?php foreach ( $values as $option ):

								$data = array();
								if ( isset( $option['data'] ) ) {
									foreach ( $option['data'] as $attr => $value ) {
										$data[] = 'data-' . esc_attr( $attr ) . '="' . trim( $value ) . '"';
									}
								}
								$data = join( ' ', $data );

								// output option to screen
								printf( '<option value="%s" %s %s >%s</option>',
									esc_attr( $option['value'] ),
									selected( $this->is_selected( trim( $option['value'] ) ) ),
									$data,
									esc_html( stripslashes( $option['name'] ) )
								);

								?>
								<?php
							endforeach ?>
							</select>
						</div>
		                <?php
					break;
					case 'checkbox':
						if ( ! isset( $this->currentValue ) ) {
							$this->currentValue = array();
						}
						?>
						<h3 class="tribe-events-filters-group-heading"><span></span><?php echo stripslashes( $this->title ); ?></h3>
						<div class="tribe-events-filter-group tribe-events-filter-checkboxes">
						<ul>
						<?php foreach ( $values as $option ):

							$data = array();
							if ( isset( $option['data'] ) && is_array( $option['data'] ) ) {
								foreach ( $option['data'] as $attr => $value ) {
									$data[] = 'data-' . esc_attr( $attr ) . '="' . trim( $value ) . '"';
								}
							}
							$data = join( ' ', $data );

							// Support CSS classes per list item
							$class = '';

							if ( isset( $option['class'] ) && ! empty( $option['class'] ) ) {
								$class = ' class="' . esc_attr( $option['class'] ) . '"';
							}

							// output option to screen
							printf( '<li%s><label><input type="checkbox" value="%s" %s name="%s" %s /><span title="%s">%s</span></label></li>',
								$class,
								esc_attr( $option['value'] ),
								checked( $this->is_selected( trim( $option['value'] ) ), true, false ),
								esc_attr( 'tribe_' . $this->slug . '[]' ),
								$data,
								esc_html( stripslashes( $option['name'] ) ),
								esc_html( stripslashes( $option['name'] ) )
								);
							?>
						<?php endforeach; ?>
						</ul>
						</div>
						<?php
					break;
					case 'radio':

						if ( ! isset( $this->currentValue ) ) {
							$current_value = '';
						} else {
							$current_value = is_array( $this->currentValue ) ? current( $this->currentValue ) : $this->currentValue;
						}
						?>
						<h3 class="tribe-events-filters-group-heading"><span></span><?php echo stripslashes( $this->title ); ?></h3>
						<div class="tribe-events-filter-group tribe-events-filter-radio">
						<ul>
						<?php foreach ( $values as $option ):

							$data = array();
							if ( isset( $option['data'] ) && is_array( $option['data'] ) ) {
								foreach ( $option['data'] as $attr => $value ) {
									$data[] = 'data-' . esc_attr( $attr ) . '="' . trim( $value ) . '"';
								}
							}
							$data = join( ' ', $data );

							// Support CSS classes per list item
							$class = '';

							if ( isset( $option['class'] ) && ! empty( $option['class'] ) ) {
								$class = ' class="' . esc_attr( $option['class'] ) . '"';
							}

							// output option to screen
							printf( '<li%s><label><input type="radio" value="%s" %s name="%s" %s /><span title="%s">%s</span></label></li>',
								$class,
								esc_attr( $option['value'] ),
								checked( trim( $option['value'] ), $current_value, false ),
								esc_attr( 'tribe_' . $this->slug ),
								$data,
								esc_html( stripslashes( $option['name'] ) ),
								esc_html( stripslashes( $option['name'] ) )
								);
							?>
						<?php endforeach; ?>
						</ul>
						</div>
						<?php
					break;
					case 'range':
						if ( ! empty( $this->currentValue ) && is_array( $this->currentValue ) ) {
							$current = reset( $this->currentValue );
						} else {
							$current = $values;
						}
						$min_value = preg_replace( '/[^.0-9]/', '', floor( $values['min'] ) );
						$max_value = preg_replace( '/[^.0-9]/', '', floor( $values['max'] ) );

						if ( $current['min'] != $min_value || $current['max'] != $max_value ) {
							$set_value = $current['min'] . '-' . $current['max'];
						} else {
							$set_value = '';
						}
						// Get our currency symbol
						$currency_symbol = tribe_get_option( 'defaultCurrencySymbol' );
						?>
							<h3 class="tribe-events-filters-group-heading"><span></span><?php echo stripslashes( $this->title ); ?></h3>
							<div class="tribe-events-filter-group tribe-events-filter-range">
								<span id="<?php echo esc_attr( 'tribe_events_filter_' . $this->slug ); ?>_display" class="tribe_events_slider_val"></span>
								<input type="hidden" id="<?php echo esc_attr( 'tribe_events_filter_' . $this->slug ); ?>" name="<?php echo esc_attr( 'tribe_' . $this->slug ); ?>" value="<?php echo esc_attr( $set_value ); ?>"  />
							<div id="<?php echo esc_attr( 'tribe_events_filter_' . $this->slug . '_slider' ); ?>"></div>
							</div>
							<script>
								<?php
								//Check to see if currency position setting is in front of or behind the value
								$reverse_position = tribe_get_option( 'reverseCurrencyPosition', false );
								?>
								jQuery(document).ready(function($) {
									$( "#<?php echo 'tribe_events_filter_' . $this->slug . '_slider'; ?>" ).slider({
										range: true,
										min: <?php echo $min_value; ?>,
										max: <?php echo $max_value; ?>,
										values: [ <?php echo preg_replace( '/[^.0-9]/', '', $current['min'] ); ?>, <?php echo preg_replace( '/[^.0-9]/', '', $current['max'] ); ?> ],
										slide: function( event, ui ) {
											<?php
												if ( $reverse_position ) {
											?>
											$( "#<?php echo 'tribe_events_filter_' . $this->slug; ?>_display" ).text( ui.values[ 0 ] + "<?php echo $currency_symbol; ?>" + "-" + ui.values[ 1 ] + "<?php echo $currency_symbol; ?>" );
											<?php } else { ?>
											$( "#<?php echo 'tribe_events_filter_' . $this->slug; ?>_display" ).text( "<?php echo $currency_symbol; ?>" + ui.values[ 0 ] + "-<?php echo $currency_symbol; ?>" + ui.values[ 1 ] );
											<?php } ?>
											if( ui.values[ 0 ] === <?php echo $min_value; ?> && <?php echo $max_value; ?> === ui.values[ 1 ] ) {
												$( "#<?php echo 'tribe_events_filter_' . $this->slug; ?>" ).val('');
											} else {
												$( "#<?php echo 'tribe_events_filter_' . $this->slug; ?>" ).val( ui.values[ 0 ] + "-" + ui.values[ 1 ] );
											}
										}
									});
								<?php if ( $reverse_position ) { ?>
									$( "#<?php echo 'tribe_events_filter_' . $this->slug; ?>_display" ).text( $( "#<?php echo 'tribe_events_filter_' . $this->slug . '_slider'; ?>" ).slider( "values", 0 ) + "<?php echo $currency_symbol; ?>" + "-" + $( "#<?php echo 'tribe_events_filter_' . $this->slug . '_slider'; ?>" ).slider( "values", 1 ) + "<?php echo $currency_symbol; ?>" );
								<?php } else { ?>
									$( "#<?php echo 'tribe_events_filter_' . $this->slug; ?>_display" ).text( "<?php echo $currency_symbol; ?>" + $( "#<?php echo 'tribe_events_filter_' . $this->slug . '_slider'; ?>" ).slider( "values", 0 ) + "-<?php echo $currency_symbol; ?>" + $( "#<?php echo 'tribe_events_filter_' . $this->slug . '_slider'; ?>" ).slider( "values", 1 ) );
								<?php } ?>
								});
							</script>
						<?php
					break;
					case 'multi-select':
						if ( ! isset( $this->currentValue ) ) {
							$this->currentValue = array();
						}
						?>
						<h3 class="tribe-events-filters-group-heading"><span></span><?php echo stripslashes( $this->title ); ?></h3>
						<div class="tribe-events-filter-group tribe-events-filter-multi-select">
						<select multiple="true" id="<?php echo esc_attr( 'tribe_events_filter_' . $this->slug ); ?>" name="<?php echo esc_attr( 'tribe_' . $this->slug ); ?>[]">
							<?php foreach ( $values as $option ):

							$data = array();
							if ( isset( $option['data'] ) ) {
								foreach ( $option['data'] as $attr => $value ) {
									$data[] = 'data-' . esc_attr( $attr ) . '="' . trim( $value ) . '"';
								}
							}
							$data = join( ' ', $data );

							// output option to screen
							printf( '<option value="%s" %s %s >%s</option>',
								esc_attr( $option['value'] ),
								selected( $this->currentValue, trim( $option['value'] ) ),
								$data,
								esc_html( stripslashes( $option['name'] ) )
							);
							?>
							<?php
						endforeach ?>
						</select>
						</div>
						<?php
					break;
				}
				?>
				</div>
				<?php
			}
		}

		/**
		 * Tests to see if the option either matches the current filter value or, in the
		 * case of multiple current values being set (such as if the filter is operating
		 * in checkbox mode) if the option is amonst those values.
		 *
		 * @param  $option
		 *
		 * @return bool
		 */
		protected function is_selected( $option ) {
			if ( is_array( $this->currentValue ) ) {
				return in_array( $option, $this->currentValue );
			} else {
				return $option == $this->currentValue;
			}
		}

		/**
		 * Add the filter to the All Filters array.
		 *
		 * @param array $filters The current array of filters.
		 * @return array The array of filters.
		 */
		public function allFiltersArray( $filters ) {
			$this_filter = array(
				'name' => $this->name,
				'type' => $this->type,
				'admin_form' => $this->get_admin_form(),
			);

			$filters[ $this->slug ] = $this_filter;

			return $filters;
		}

		public function get_admin_form() {
			if ( ! empty( $this->adminForm ) ) {
				return $this->adminForm;
			} else {
				return $this->get_title_field();
			}
		}

		protected function get_title_field() {
			$field = sprintf(
				__( 'Title: %s', 'tribe-events-filter-view' ),
				sprintf(
					'<input type="text" name="%s" value="%s">',
					$this->get_admin_field_name( 'title' ),
					esc_attr( stripslashes( $this->title ) )
				)
			);
			return $field;
		}

		/**
		 * Helper function: generates a list of field types that can be selected for those filters
		 * which support a choice of dropdown, checkbox and radio modes.
		 *
		 * @return string
		 */
		protected function get_multichoice_type_field() {
			$name  = $this->get_admin_field_name( 'type' );
			$field = sprintf( __( 'Type: %s %s %s', 'tribe-events-filter-view' ),
				sprintf( '<label><input type="radio" name="%s" value="select" %s /> %s</label>',
					$name,
					checked( $this->type, 'select', false ),
					__( 'Dropdown', 'tribe-events-filter-view' )
				),
				sprintf( '<label><input type="radio" name="%s" value="checkbox" %s /> %s</label>',
					$name,
					checked( $this->type, 'checkbox', false ),
					__( 'Checkboxes', 'tribe-events-filter-view' )
				),
				sprintf( '<label><input type="radio" name="%s" value="autocomplete" %s /> %s</label>',
					$name,
					checked( $this->type, 'autocomplete', false ),
					__( 'Autocomplete', 'tribe-events-filter-view' )
				)
			);

			return '<div class="tribe_events_active_filter_type_options">' . $field . '</div>';
		}

		protected function get_admin_field_name( $name ) {
			return 'tribe_filter_options['.$this->slug.']['.$name.']';
		}

		protected function settings() {
			$this->title = $this->get_title();
			$this->type = $this->get_type();
		}

		protected function get_title() {
			$current_active_filters = Tribe__Events__Filterbar__View::instance()->get_filter_settings();
			$title = isset( $current_active_filters[ $this->slug ]['title'] ) ? $current_active_filters[ $this->slug ]['title'] : $this->name;
			return apply_filters( 'tribe_events_filter_title', $title, $this->slug );
		}

		protected function get_type() {
			$current_active_filters = Tribe__Events__Filterbar__View::instance()->get_filter_settings();
			$type = isset( $current_active_filters[ $this->slug ]['type'] ) ? $current_active_filters[ $this->slug ]['type'] : $this->type;
			return apply_filters( 'tribe_events_filter_type', $type, $this->slug );
		}

		protected function get_priority() {
			$current_active_slugs = Tribe__Events__Filterbar__View::instance()->get_active_filters();
			$priority = array_search( $this->slug, $current_active_slugs );

			if ( $priority !== false ) {
				$priority = ++$priority;
			} else {
				$priority = 0;
			}
			return apply_filters( 'tribe_events_filter_priority', $priority, $this->slug );
		}

		protected function is_active() {
			$current_active_filters = get_option( Tribe__Events__Filterbar__Settings::OPTION_ACTIVE_FILTERS, false );
			if ( $current_active_filters === false ) {
				$active = true; // everything is active by default
			} else {
				$active = isset( $current_active_filters[ $this->slug ] );
			}
			return apply_filters( 'tribe_events_filter_is_active', $active, $this->slug );
		}

		protected function get_values() {
			// template method
			return array();
		}

		protected function setup_query_filters() {
			if ( $this->currentValue ) {
				$this->setup_query_args();
				$this->setup_join_clause();
				$this->setup_where_clause();
			}
		}

		protected function setup_query_args() {
			// template method
		}

		protected function setup_join_clause() {
			// template method.
		}

		protected function setup_where_clause() {
			// template method.
		}
	}
}
