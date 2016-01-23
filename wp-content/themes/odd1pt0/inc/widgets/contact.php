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

# Contact Information
class ProdoWidgetContact extends WP_Widget {
	# Constructor
	function __construct( ) {
		parent::__construct( 'prodo_widget_contact', __( 'Contacts', 'prodo' ), array( 'description' => 'Contact Information' ) );
	}

	# Frontend
	public function widget( $args, $instance ) {
		$title     = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Contacts', 'prodo' ) : $instance['title'], $instance, $this->id_base );
		$text      = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$phone     = apply_filters( 'widget_text', empty( $instance['phone'] ) ? '' : $instance['phone'], $instance );
		$location  = apply_filters( 'widget_text', empty( $instance['location'] ) ? '' : $instance['location'], $instance );
		$email     = apply_filters( 'widget_text', empty( $instance['email'] ) ? '' : $instance['email'], $instance );

		$location_url    = 'https://www.google.com/maps/preview?q=' . urlencode( $location );
		$location_as_url = isset( $instance['location_as_url'] );
		$email_as_url    = isset( $instance['email_as_url'] );

		echo $args['before_widget'];

		echo $args['before_title'] . $title . $args['after_title'];

		if ( ! empty( $text ) ) {
			echo wpautop( $text );
		}
		
		echo '
		<ul class="fa-ul">
			' . ( ! empty( $phone ) ? '<li><i class="fa-li fa fa-phone"></i>' . esc_html( $phone ) . '</li>' : '' ) . '
			' . ( ! empty( $location ) ? '<li><i class="fa-li fa fa-map-marker"></i>' . ( $location_as_url ? '<a href="' . esc_url( $location_url ) . '" target="_blank" class="normal">' : '' ) . esc_html( $location ) . ( $location_as_url ? '</a>' : '' ) . '</li>' : '' ) . '
			' . ( ! empty( $email ) ? '<li><i class="fa-li fa fa-envelope"></i>' . ( $email_as_url ? '<a href="mailto:' . esc_attr( antispambot( $email ) ) . '" class="normal">' : '' ) . esc_html( antispambot( $email ) ) . ( $email_as_url ? '</a>' : '' ) . '</li>' : '' ) . '
		</ul>';

		echo $args['after_widget'];
	}

	# Backend
	public function form( $instance ) {
		$instance = wp_parse_args( ( array ) $instance,
			array(
				'title'           => __( 'Contacts', 'prodo' ),
				'text'            => '',
				'phone'           => '',
				'location'        => '',
				'email'           => ''
			)
		);

		$title = strip_tags( $instance['title'] );
		$text = esc_textarea( $instance['text'] );
		$phone = strip_tags( $instance['phone'] );
		$location = strip_tags( $instance['location'] );
		$email = strip_tags( $instance['email'] );

		echo '
		<p>
			<label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title:', 'prodo' ) . '</label> 
			<input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" />
		</p>
		<textarea class="widefat" rows="6" cols="20" id="' . $this->get_field_id( 'text' ) . '" name="' . $this->get_field_name( 'text' ) . '">' . $text . '</textarea>
		<p>
			<label for="' . $this->get_field_id( 'phone' ) . '">' . __( 'Phone:', 'prodo' ) . '</label> 
			<input class="widefat" id="' . $this->get_field_id( 'phone' ) . '" name="' . $this->get_field_name( 'phone' ) . '" type="text" value="' . esc_attr( $phone ) . '" />
		</p>
		<p>
			<label for="' . $this->get_field_id( 'location' ) . '">' . __( 'Location:', 'prodo' ) . '</label> 
			<input class="widefat" id="' . $this->get_field_id( 'location' ) . '" name="' . $this->get_field_name( 'location' ) . '" type="text" value="' . esc_attr( $location ) . '" />
		</p>
		<p>
			<label for="' . $this->get_field_id( 'email' ) . '">' . __( 'Email:', 'prodo' ) . '</label> 
			<input class="widefat" id="' . $this->get_field_id( 'email' ) . '" name="' . $this->get_field_name( 'email' ) . '" type="text" value="' . esc_attr( $email ) . '" />
		</p>
		<p>
			<input class="checkbox" id="' . $this->get_field_id( 'location_as_url' ) . '" name="' . $this->get_field_name( 'location_as_url' ) . '" type="checkbox" ' . checked( isset( $instance['location_as_url'] ) ? $instance['location_as_url'] : 0, true, false ) . ' />
			<label for="' . $this->get_field_id( 'location_as_url' ) . '">' . __( 'Display Location as link', 'prodo' ) . '</label><br>

			<input class="checkbox" id="' . $this->get_field_id( 'email_as_url' ) . '" name="' . $this->get_field_name( 'email_as_url' ) . '" type="checkbox" ' . checked( isset( $instance['email_as_url'] ) ? $instance['email_as_url'] : 0, true, false ) . '>
			<label for="' . $this->get_field_id( 'email_as_url' ) . '">' . __( 'Display Email as link', 'prodo' ) . '</label>
		</p>';
	}

	# Updating
	public function update( $instance, $old_instance ) {
		return array(
			'title'           => strip_tags( $instance['title'] ),
			'text'            => stripslashes( wp_filter_post_kses( addslashes( $instance['text'] ) ) ),
			'phone'           => sanitize_text_field( $instance['phone'] ),
			'location'        => sanitize_text_field( $instance['location'] ),
			'email'           => sanitize_text_field( $instance['email'] ),
			'location_as_url' => isset( $instance['location_as_url'] ),
			'email_as_url'    => isset( $instance['email_as_url'] )
		);
	}
}
