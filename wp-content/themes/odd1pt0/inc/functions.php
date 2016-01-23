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
 
class ProdoTheme {
	# Social Icons
	public static function socialIcons( $format ) {
		global $prodoConfig;

		$result = '';
		
		if ( isset( $prodoConfig ) ) {
			foreach ( $prodoConfig as $param => $value ) {
				if ( substr_count( $param, 'social-' ) > 0 and ! empty( $value ) ) {
					$safe = substr( $param, 7 );
					$social = ucfirst( str_replace( '-', ' ', $safe ) );
					$result .= sprintf( $format, esc_attr( $safe ), esc_attr( $social ), esc_url( $value ) );
				}
			}
		}

		return $result;
	}

	# Slideshow Images
	public static function slideshowImages( $format, $source ) {
		if ( ! empty( $source ) ) {
			$result = '';
			$array = explode( ',', $source );

			if ( count( $array ) > 0 ) {
				foreach ( $array as $value ) {
					if ( ! empty( $value ) ) {
						$result .= sprintf( $format, esc_url( $value ) );
					}
				}

				return $result;
			}
		}

		return '';
	}

	# Slideshow Slides
	public static function slideshowSlides( $postId ) {
		$meta = get_post_meta( $postId );

		if ( count( $meta ) > 0 ) {
			$array = array( );

			foreach ( $meta as $param => $value ) {
				if ( substr_count( $param, 'slideshow-slide-' ) > 0 ) {
					if ( ! empty( $value[0] ) ) {
						$array[] = $value[0];
					}
				}
			}

			if ( count( $array ) > 0 ) {
				return $array;
			}
		}

		return false;
	}

	# Get Front Page Type
	public static function frontPageType( $postId ) {
		$type = get_post_meta( $postId, 'header-section', true );
		
		if ( $type == 'none' or empty( $type ) ) {
			return false;
		}

		return $type;
	}

	# Is Front Page
	public static function isFrontPage( $postId ) {
		if ( self::frontPageType( $postId ) !== false ) {
			return true;
		}

		return false;
	}

	# Load Front Page Templates
	public static function frontPage( $postId ) {
		$type = self::frontPageType( $postId );

		if ( $type === false ) {
			return false;
		} else if ( $type == 'video' ) {
			get_template_part( 'templates/front', 'video' );
		} else if ( $type == 'pattern' ) {
			get_template_part( 'templates/front', 'pattern' );
		} else if ( $type == 'image' ) {
			get_template_part( 'templates/front', 'image' );
		} else if ( $type == 'embed-video' ) {
			get_template_part( 'templates/front', 'embed' );
		} else if ( $type == 'slideshow-static' ) {
			get_template_part( 'templates/front', 'slides-static' );
		} else if ( $type == 'slideshow' ) {
			get_template_part( 'templates/front', 'slides-normal' );
		}

		return true;
	}

	# Front Page Sections
	public static function frontSections( ) {
		global $prodoConfig;

		$output = '';
		$sections = ( array ) @json_decode( get_option( 'prodo_sections', true ), true );

		if ( count( $sections ) > 0 ) {
			$count = count( $sections['page'] );

			if ( $count > 0 ) {
				for ( $i = 0; $i < $count; $i ++ ) {
					$post = $post_content = null;
					$post_template = '';

					if ( ! empty( $sections['page'][$i] ) ) {
						$post = get_page_by_path( stripslashes( $sections['page'][$i] ) );

						if ( $post !== null and isset( $post->post_content ) ) {
							$post_content = $post->post_content;
						}
					}
					
					if ( $post_content !== null ) {
						$current = apply_filters( 'the_content', do_shortcode( stripslashes( $post_content ) ) );

						if ( substr_count( $current, '<section' ) < 1 ) {
							$atts = array( );
							$addClass = $after = '';

							if ( $sections['layout'][$i] == 'parallax' ) {
								$atts['data-image'] = $sections['image'][$i];
							} else if ( $sections['layout'][$i] == 'video' ) {
								$atts['data-source'] = $sections['video'][$i];
								$atts['data-start'] = intval( $sections['video-start'][$i] );

								if ( ! $prodoConfig['multiple-videos'] ) {
									$atts['data-hide-on-another'] = 'true';
								}
							}

							if ( $sections['layout'][$i] == 'parallax' or $sections['layout'][$i] == 'video' ) {
								if ( $sections['overlay'][$i] == 'default' or $sections['overlay'][$i] == 'primary' ) {
									$atts['overlay'] = $sections['overlay'][$i];
								}
							}

							$classes = 'offsetTop offsetBottom';

							if ( $sections['background'][$i] == 'gray' ) {
								$addClass = 'alt-background';
							}

							if ( substr_count( $current, 'portfolio-items' ) > 0 ) {
								$classes = 'offsetTop';
								$after = '<div><div class="section offsetTop offsetBottomL" id="portfolio-details"></div></div>';
							}

							$id = ( $post !== null ) ? $post->post_name : '';
							$output .= self::sectionWrapper( $sections['layout'][$i], $current, $atts, $id, $addClass, $classes );

							if ( ! empty( $after ) ) {
								$output .= $after;
							}
						} else {
							$output .= $current;
						}
					}
				}
			}
		}

		return $output;
	}

	# Section Wrapper (Primary)
	public static function sectionWrapper( $type, $content, $atts = array( ), $id = '', $addClass = '', $class = 'offsetTop offsetBottom' ) {
		$atts_str = '';
		$atts_formated = array( );

		if ( count( $atts ) > 0 ) {
			foreach( $atts as $key => $value ) {
				if ( $key != 'overlay' ) {
					$atts_formated[] = $key . '="' . esc_attr( $value ) . '"';
				}
			}

			$atts_str = ' ' . implode( ' ', $atts_formated );
		}

		if ( $type == 'parallax' ) {
			$class = 'parallax';
			if ( isset( $atts['overlay'] ) ) {
				$overlay = $atts['overlay'] == 'primary' ? '<div class="parallax-overlay colored"></div>' : '<div class="parallax-overlay"></div>';
			} else {
				$overlay = '';
			}

			$content = '<div class="parallax-container">' . $overlay . '<div class="container offsetTopX offsetBottomX">' . $content . '</div></div>';
		} else if ( $type == 'video' ) {
			$class = 'video hidden-xs';
			if ( isset( $atts['overlay'] ) ) {
				$overlay = $atts['overlay'] == 'primary' ? '<div class="video-overlay colored"></div>' : '<div class="video-overlay"></div>';
			} else {
				$overlay = '';
			}

			$content = '<div class="video-container">' . $overlay . '<div class="container offsetTopX offsetBottomX">' . $content . '</div></div>';
		} else {
			$content = '<div class="container">' . $content . '</div>';
		}

		return "\n" . '<section' . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' class="section' . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . ( ! empty( $addClass ) ? ' ' . esc_attr( $addClass ) : '' ) . '"' . $atts_str . '>' . "\n" . $content . "\n" . '</section>' . "\n";
	}

	# Main Menu
	public static function mainMenu( $post_id, $menu_class = '' ) {
		return wp_nav_menu( array(
			'theme_location' => 'header-menu',
			'container'      => false,
			'menu_class'     => $menu_class,
			'echo'           => false,
			'depth'          => 2,
			'walker'         => new ProdoMenu,
			'fallback_cb'    => array( 'ProdoMenu', 'fallback_cb' )
		) );
	}

	# Portfolio Item Categories
	public static function portfolioCategories( $post_id, $delimiter = ', ' ) {
		$info = wp_get_object_terms( $post_id, 'portfolio-category' );
		$category = array( );

		foreach( $info as $item ) {
			$category[] = $item->name;
		}

		return implode( $delimiter, $category );
	}

	# Is Ajax?
	public static function isAJAX( ) {
		return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' );
	}

	# Post Content
	public static function postContent( ) {
		$formats = array( 'gallery', 'aside', 'status', 'quote', 'link' );

		foreach ( $formats as $format ) {
			if ( has_post_format( $format ) ) {
				get_template_part( 'templates/post', $format );
				
				return true;
			}
		}
		get_template_part( 'templates/post', 'standard' );

		return true;
	}

	# Post Categories
	public static function postCategories( $post_id, $before = '<span>', $after = '</span>', $echo = true ) {
		$categories = get_the_category( $post_id );

		if ( is_array( $categories ) and count( $categories ) > 0 ) {
			$output = array( );

			foreach ( $categories as $row ) {
				$output[] = '<a href="' . get_category_link( $row->term_id ) . '">' . $row->cat_name . '</a>';
			}

			if ( $echo ) {
				echo $before . implode( ', ', $output ) . $after;
			} else {
				return $before . implode( ', ', $output ) . $after;
			}
		}
	}

	# Comment
	public static function comment( $comment, $args, $depth ) {
		global $post;

		if ( $comment->comment_type == 'pingback' or $comment->comment_type == 'trackback' ) {
			echo '
			<div ' . comment_class( 'user-comment', null, null, false ) . ' id="comment-' . get_comment_ID( ) . '">
				<div class="details">
					<div class="info">
						<span class="author"><span>' . __( 'Pingback', 'prodo' ) . ' &ndash;</span> ' . get_comment_author_link( ) . '<div class="sticker post-author">Post author</div></span>
					</div>
					<div class="reply">';
						edit_comment_link( __( 'Edit ', 'prodo' ), '', ( ( comments_open( ) and $depth < $args['max_depth'] ) ? ' &ndash; ' : '' ) );
			echo '  </div>
				</div>';
		} else {
			$avatar = str_replace( 'class=\'', 'class=\'img-responsive img-rounded ', get_avatar( get_comment_ID( ), 80, '', 'Photo' ) );

			echo '
			<div ' . comment_class( 'user-comment', null, null, false ) . ' id="comment-' . get_comment_ID( ) . '">
				<div class="image">' . $avatar . '</div>
				<div class="details">
					<div class="info">
						<span class="author">' . get_comment_author_link( ) . '<div class="sticker post-author">Post author</div></span>
						<span class="date">' . get_comment_date( ) . ' ' . __( 'at', 'prodo' ) . ' ' . get_comment_time( ) . '</span>
					</div>
					<div class="text">
						<p>' . get_comment_text( ) . '</p>
					</div>
					<div class="reply">';
						edit_comment_link( __( 'Edit ', 'prodo' ), '', ( ( comments_open( ) and $depth < $args['max_depth'] ) ? ' &ndash; ' : '' ) );
						comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'prodo' ), 'depth' => $depth ) ) );
			echo '  </div>
				</div>';
		}
	}

	# Page Title
	public static function pageTitle( ) {
		if ( is_home( ) ) {
			if ( get_option( 'page_for_posts', true ) ) {
				echo get_the_title( get_option( 'page_for_posts', true ) );
			} else {
				_e( 'Latest Posts', 'prodo' );
			}
		} elseif ( is_archive( ) ) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			
			if ( $term ) {
				echo $term->name;
			} elseif ( is_post_type_archive( ) ) {
				echo get_queried_object( )->labels->name;
			} elseif ( is_day( ) ) {
				printf( __( 'Daily Archives: %s', 'prodo' ), get_the_date( ) );
			} elseif ( is_month( ) ) {
				printf( __( 'Monthly Archives: %s', 'prodo' ), get_the_date( 'F Y' ) );
			} elseif ( is_year( ) ) {
				printf( __( 'Yearly Archives: %s', 'prodo' ), get_the_date( 'Y' ) );
			} elseif ( is_author( ) ) {
				global $post;

				printf( __( 'Author Archives: %s', 'prodo' ), get_the_author_meta( 'display_name', $post->post_author ) );
			} else {
				single_cat_title( );
			}
		} elseif ( is_search( ) ) {
			printf( __( 'Search Results for %s', 'prodo' ), get_search_query( ) );
		} elseif ( is_404( ) ) {
			_e( 'File Not Found', 'prodo' );
		} else {
			the_title( );
		}
	}

	# Content Navigation
	public static function navContent( $class = '' ) {
		global $wp_query;

		if ( $wp_query->max_num_pages > 1 ) {
			echo '<div class="row">
				<div class="col-md-12 pages-navigation' . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">
					' . get_next_posts_link( __( '&lsaquo;&nbsp; Older posts', 'prodo' ) ) . '
					' . get_previous_posts_link( __( 'Newer posts &nbsp;&rsaquo;', 'prodo' ) ) . '
				</div>
			</div>';
		}
	}

	# Post Gallery
	public static function postGallery( $more_link, $echo = true ) {
		$content = preg_replace_callback( '/\\[gallery(.*?)ids=(?:"|\')([0-9,]+)([^\\]]+)\\]/is', array( 'self', 'gallerySlider' ), get_the_content( $more_link ) );

		if ( $echo ) {
			echo apply_filters( 'the_content', $content );
		} else {
			return apply_filters( 'the_content', $content );
		}
	}

	# Gallery Slider
	public static function gallerySlider( $matches ) {
		$output = '';
		if ( ! empty( $matches[2] ) ) {
			$output .= '<div class="image-slider">';
			$ids = explode( ',', $matches[2] );

			foreach ( $ids as $id ) {
				$output .= '<div><img src="' . wp_get_attachment_url( $id ) . '" class="img-responsive img-rounded" alt=""></div>';
			}

			$output .= '<div class="arrows"><a class="arrow left"><i class="fa fa-chevron-left"></i></a><a class="arrow right"><i class="fa fa-chevron-right"></i></a></div></div>';
		}

		return $output;
	}

	# Get Next Attachment URL
	public static function nextAttachmentURL( $post ) {
		$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );

		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID ) {
				break;
			}
		}
		
		if ( count( $attachments ) > 1 ) {
			$k ++;
			if ( isset( $attachments[$k] ) ) {
				$url = get_attachment_link( $attachments[$k]->ID );
			} else {
				$url = get_attachment_link( $attachments[0]->ID );
			}
		} else {
			$url = wp_get_attachment_url( );
		}
		
		return $url;
	}

	# Get Option
	public static function option( $key, $default = false ) {
		global $prodoConfig;

		if ( isset( $prodoConfig[$key] ) ) {
			return $prodoConfig[$key];
		}

		return $default;
	}
}
