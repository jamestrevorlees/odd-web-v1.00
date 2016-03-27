<?php
/**
 * Generates additional field filters if appropriate.
 */
class Tribe__Events__Filterbar__Additional_Fields__Manager {
	const UNSUPPORTED_EARLY_PRO     = 0;
	const UNSUPPORTED_UPDATE_NEEDED = 1;

	/**
	 * Container for any additional field filters that have been created.
	 *
	 * @var array
	 */
	public static $filters = array();

	/**
	 * If additional fields are unsupported, contains a flag representing the reason.
	 *
	 * @var
	 */
	protected static $unsupported;


	/**
	 * Sets up additional field filters, if possible.
	 */
	public static function init() {
		// If PRO is not active we cannot support additional fields
		if ( ! class_exists( 'Tribe__Events__Pro__Main' ) ) {
			return;
		}

		// Register our additional fields or prompt the user if an update is required
		self::register_filters() || self::notify_update_needed();
	}

	/**
	 * Registers additional field filters unless the additional field data is not ready/still
	 * in need of an update.
	 *
	 * @return bool
	 */
	protected static function register_filters() {
		if ( ! self::additional_fields_ready() ) {
			return false;
		}

		foreach ( (array) tribe_get_option( 'custom-fields', array() ) as $field ) {
			/**
			 * Controls whether a filter is created or not for a particular additional field.
			 *
			 * @var bool  $create  create a filter object for this additional field
			 * @var array $field   additional field definition
			 */
			if ( ! apply_filters( 'tribe_events_filter_create_additional_field_filter', true, $field ) ) {
				continue;
			}

			/**
			 * Controls the title used for an additional field filter.
			 *
			 * @var string $label  default title for the additional field filter
			 * @var array  $field  additional field definition
			 */
			$title = apply_filters( 'tribe_events_filter_additional_field_title', $field[ 'label' ], $field );

			/**
			 * Controls the slug used for an additional field filter. This should generally be
			 * unique or else unexpected results could be returned when users apply the filter.
			 *
			 * @var string $slug   default slug for the additional field filter
			 * @var array  $field  additional field definition
			 */
			$slug = apply_filters( 'tribe_events_filter_additional_field_slug', $field[ 'name' ], $field );

			// For multichoice fields we need an extra leading underscore for our meta queries
			$meta_key = Tribe__Events__Pro__Custom_Meta::is_multichoice( $field )
				? '_' . $field[ 'name' ]
				: $field[ 'name' ];

			self::$filters[ $meta_key ] = new Tribe__Events__Filterbar__Filters__Additional_Field(
				$title, $slug, $meta_key
			);
		}

		return true;
	}

	/**
	 * Setup an admin notice to alert users they need to update their additional field data.
	 */
	public static function notify_update_needed() {
		if ( self::UNSUPPORTED_UPDATE_NEEDED === self::$unsupported ) {
			add_action( 'tribe_settings_above_tabs', array( __CLASS__, 'notify_update_msg' ) );
		}
	}

	/**
	 * If we are within the Filter Bar settings tab, render a helpful notice prompting the user to
	 * update their additional field data.
	 */
	public static function notify_update_msg() {
		// Only display the notice/update UI within the Filter Bar settings tab
		if ( 'filter-view' !== Tribe__Settings::instance()->currentTab ) {
			return;
		}

		$additional_field_tab = esc_url( add_query_arg( 'tab', 'additional-fields' ) );

		$message = sprintf(
			__( 'Please visit the settings screen for %1$sAdditional Fields%2$s and use the provided update link or you will not be able to use Additional Field-based filters.', 'tribe-events-filter-view' ),
			'<a href="' . $additional_field_tab . '">',
			'</a>'
		);

		echo "<div id='tribe-additional-field-update' class='notice notice-warning'> <p> $message </p> </div>";
	}

	/**
	 * Tests if we have a sufficiently recent version of Events Calendar PRO in place and
	 * if the additional field data in the database follows the currently expected pattern.
	 *
	 * @return bool
	 */
	public static function additional_fields_ready() {
		$pro = Tribe__Events__Pro__Main::instance();

		// If the custom meta tools object is not available, the active version of PRO is too early
		if ( ! property_exists( $pro, 'custom_meta_tools' ) ) {
			self::$unsupported = self::UNSUPPORTED_EARLY_PRO;
			return false;
		}

		// If updates are required for additional fields we cannot support them until the updates are made
		if ( $pro->custom_meta_tools->are_updates_needed() ) {
			self::$unsupported = self::UNSUPPORTED_UPDATE_NEEDED;
			return false;
		}

		// Otherwise, things should be good!
		return true;
	}
}