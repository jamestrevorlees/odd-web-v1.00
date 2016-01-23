/**
 * Prodo
 * WordPress Theme
 *
 * Web: https://www.facebook.com/a.axminenko
 * Email: a.axminenko@gmail.com
 *
 * Copyright 2015 Alexander Axminenko
 */

"use strict";

// Fix Navigation Bar
jQuery( window ).resize( function( ) {
	jQuery( 'html.nav-sticky' ).attr( 'style', 'margin-top: ' + jQuery( '.navbar.navbar-fixed-top.floating' ).outerHeight( true ) + 'px !important;' );
} ).resize( );

/* Default Variables */
var ProdoOptions = {
	parallax: true,
	loader: true,
	animations: true,
	scrollSpeed: 700,
	navigation: 'sticky',
	intro: {
		animate: true,
		animateDelayFirst: 500,
		animateDelay: 300
	}
}

if ( typeof Prodo !== 'undefined' ) {
	jQuery.extend( ProdoOptions, Prodo );
}

var ProdoTheme = {
	// Initialize
	init: function( ) {
		ProdoTheme.intro( );
		ProdoTheme.navigation( );
		ProdoTheme.portfolio( );
		ProdoTheme.shortcodes( );
		ProdoTheme.animations( );
		ProdoTheme.loader( );

		ProdoTheme.contact( );
		ProdoTheme.map( );

		ProdoTheme.tweaks( );
		ProdoTheme.parallax( );
		ProdoTheme.videos( );
		ProdoTheme.imageSlider( );
		ProdoTheme.contentSlider( );
		ProdoTheme.blog( );

		ProdoTheme.twitter( );
	},

	// Page Loader
	loader: function( ) {
		if ( ProdoOptions.loader ) {
			var loaderHide = function( ) {
				jQuery( window ).trigger( 'prodo.loaded' );
				jQuery( '.page-loader .content' ).delay( 500 ).fadeOut( );
				jQuery( '.page-loader' ).delay( 1000 ).fadeOut( 'slow', function( ) {
					jQuery( window ).trigger( 'prodo.complete' );
				} );
			};

			// Loadsafe
			jQuery( window ).load( function( ) {
				window._loaded = true;
			} );
			window.loadsafe = function( callback ) {
				if ( window._loaded ) {
					callback.call( );
				} else {
					jQuery( window ).load( function( ) {
						callback.call( );
					} );
				}
			}

			// Hidding
			if ( jQuery( '#intro' ).attr( 'data-type' ) == 'video' && ! Modernizr.touch ) {
				jQuery( window ).on( 'prodo.intro-video', function( ) {
					window.loadsafe( function( ) {
						loaderHide( );
					} );
				} );
			} else {
				jQuery( window ).load( function( ) {
					loaderHide( );
				} );
			}
		} else {
			jQuery( document ).ready( function( ) {
				jQuery( window ).trigger( 'prodo.loaded' );
				jQuery( '.page-loader' ).remove( );
				jQuery( window ).trigger( 'prodo.complete' );
			} );
			jQuery( window ).load( function( ) {
				jQuery( window ).trigger( 'prodo.complete' );
			} );
		}
	},

	// Navigation
	navigation: function( ) {
		// Floating Menu
		var floatingMenuShow = function( ) {
			var $that = jQuery( '.navbar.floating' ), $old = jQuery( '.navbar:not(.floating)' );
			if ( ! $that.hasClass( 'process' ) ) {
				dropdownHide( );
				$old.animate( { opacity: 0 }, { duration: 75, queue: false, complete: function( ) {
					$that.find( 'img[data-alt]' ).each( function( ) {
						jQuery( this ).attr( 'src', jQuery( this ).attr( 'data-alt' ) ).removeAttr( 'data-alt' );
						new RetinaImage( this );
					} );
				} } );
				$that.addClass( 'process' ).addClass( 'positive' );
				setTimeout( function( ) {
					$that.removeClass( 'process' );
				}, 200 );
			}
		};
		var floatingMenuHide = function( ) {
			var $that = jQuery( '.navbar.floating' ), $old = jQuery( '.navbar:not(.floating)' );
			if ( ! $that.hasClass( 'process' ) ) {
				dropdownHide( );
				$that.addClass( 'process' ).removeClass( 'positive' ).addClass( 'negative' );
				$old.animate( { opacity: 1 }, { duration: 250, queue: false } );
				$old.find( '.navbar-collapse.collapse.in' ).removeClass( 'in' );
				setTimeout( function( ) {
					$that.removeClass( 'negative' ).removeClass( 'process' );
				}, 200 );
			}
		};
		var floatingMenu = function( ) {
			var isFloating = jQuery( '.navbar' ).hasClass( 'positive' );
			if ( jQuery( document ).scrollTop( ) > 0 && ! isFloating ) {
				floatingMenuShow( );
			} else if ( jQuery( document ).scrollTop( ) == 0 && isFloating ) {
				floatingMenuHide( );
			}
		};

		// Dropdown Menu
		var dropdownHide = function( ) {
			if ( jQuery( '.navbar .dropdown.open' ).length == 0 ) return;
			jQuery( '.navbar .dropdown.open' ).each( function( ) {
				jQuery( this ).find( '.dropdown-menu' ).animate( { opacity: 0 }, { duration: 150, queue: false, complete: function( ) {
					jQuery( this ).parent( ).removeClass( 'open' );
				} } );
			} );
		};
		var dropdownShow = function( $that ) {
			$that.find( '.dropdown-menu' ).css( { opacity: 0 } );
			$that.addClass( 'open' ).find( '.dropdown-menu' ).animate( { opacity: 1 }, { duration: 150, queue: false } );
		};

		// Collapse Menu
		var collapseMenu = function( ) {
			if ( jQuery( '.navbar-collapse.collapse.in' ).length > 0 ) {
				jQuery( '.navbar-collapse.collapse.in' ).each( function( ) {
					jQuery( this ).parent( ).find( '.navbar-toggle' ).click( );
				} );
			}
		};

		jQuery( window ).resize( function( ) {
			collapseMenu( );
			dropdownHide( );
		} ).scroll( function( ) {
			collapseMenu( );
		} );

		// Navbar Toggle
		jQuery( '.navbar .navbar-toggle' ).click( function( evt ) {
			evt.preventDefault( );
			dropdownHide( );
		} );

		// Create floating navigation bar
		if ( jQuery( '#intro' ).length > 0 && ProdoOptions.navigation == 'sticky' ) {
			var navbarClone = jQuery( '.navbar' ).clone( ).prependTo( 'body' );

			navbarClone.addClass( 'floating navbar-fixed-top' ).find( '.navbar-toggle' ).attr( 'data-target', '#navbar-collapse-alt' ).parent( ).parent( ).find( '.navbar-collapse' ).attr( 'id', 'navbar-collapse-alt' );

			jQuery( window ).load( function( ) {
				floatingMenu( );
			} ).scroll( function( ) {
				floatingMenu( );
			} );
		}
		if ( jQuery( '#intro' ).length == 0 && ProdoOptions.navigation != 'sticky' ) {
			jQuery( '.navbar' ).removeClass( 'navbar-fixed-top' );
		}

		// Dropdown Menu
		var dropdownTimer, dropdownExists = false;
		jQuery( '.dropdown' ).hover( function( ) {
			if ( ! jQuery( this ).parent( ).parent( ).hasClass( 'in' ) && ! jQuery( this ).parent( ).parent( ).hasClass( 'collapsing' ) ) {
				clearTimeout( dropdownTimer );
				if ( jQuery( this ).hasClass( 'open' ) ) return;
				if ( dropdownExists ) dropdownHide( );
				dropdownExists = true;
				dropdownShow( jQuery( this ) );
			}
		}, function( ) {
			if ( ! jQuery( this ).parent( ).parent( ).hasClass( 'in' ) ) {
				dropdownTimer = setTimeout( function( ) {
					dropdownHide( );
					dropdownExists = false;
				}, 500 );
			}
		} );
		jQuery( document ).on( 'click', '.navbar-collapse.in .dropdown > a', function( evt ) {
			evt.preventDefault( );
			var $parent = jQuery( this ).parent( );
			if ( ! $parent.hasClass( 'open' ) ) {
				dropdownShow( $parent );
			} else {
				dropdownHide( );
			}
		} );

		// Scroll to Anchor Links
		jQuery( 'a[href^=#]' ).click( function( evt ) {
			if ( jQuery( this ).attr( 'href' ) != '#' && ! jQuery( evt.target ).parent( ).parent( ).is( '.navbar-nav' ) && ! jQuery( this ).attr( 'data-toggle' ) ) {
				jQuery( document ).scrollTo( jQuery( this ).attr( 'href' ), ProdoOptions.scrollSpeed, { offset: { top: -85, left: 0 } } );
				evt.preventDefault( );
			}
		} );

		// Navigation
		jQuery( document ).ready( function( ) {
			jQuery( '.navbar-nav' ).onePageNav( {
				currentClass: 'current-menu-item',
				changeHash: false,
				scrollSpeed: ProdoOptions.scrollSpeed,
				scrollOffset: 85,
				scrollThreshold: 0.5,
				filter: 'li a[href^=#]',
				begin: function( ) {
					collapseMenu( );
				}
			} );
		} );

		if ( document.location.hash && ProdoOptions.loader ) {
			if ( ! /\?/.test( document.location.hash ) ) {
				jQuery( window ).load( function( ) {
					jQuery( window ).scrollTo( document.location.hash, 0, { offset: { top: -85, left: 0 } } );
				} );
			}
		}

		// To Top
		jQuery( '.footer .to-top' ).click( function( ) {
			jQuery( window ).scrollTo( jQuery( 'body' ), 1500, { offset: { top: 0, left: 0 } } );
		} );
	},

	// Intro
	intro: function( ) {
		if ( jQuery( '#intro' ).length == 0 ) {
			return;
		}

		var $that = jQuery( '#intro' );
		var useImages = false, useVideo = false;

		// Vertical Align Content
		var verticalAlignContent = function( ) {
			var contentH = $that.find( '.content' ).outerHeight( ),
				windowH = jQuery( '#intro' ).height( ),
				menuH = $that.find( '.navbar' ).not( '.floating' ).outerHeight( true ),
				adminH = jQuery( '#wpadminbar' ).outerHeight( );

			var value = Math.floor( windowH / 2 - contentH / 2 - menuH / 2 );

			$that.find( '.content' ).css( { marginTop: value } );
		};

		// Magic Mouse
		var magicMouse = function( ) {
			var mouseOpacity = 1 - jQuery( document ).scrollTop( ) / 400;
			if ( mouseOpacity < 0 ) mouseOpacity = 0;
			$that.find( '.mouse' ).css( { opacity: mouseOpacity } );
		};

		if ( ! ProdoOptions.intro.animate ) {
			$that.find( '.animate' ).removeClass( 'animate' );
		}

		jQuery( window ).resize( function( ) {
			verticalAlignContent( );
		} );
		jQuery( window ).load( function( ) {
			verticalAlignContent( );
			magicMouse( );
		} );
		jQuery( window ).scroll( function( ) {
			magicMouse( );
		} );

		// Static Image or Pattern
		if ( $that.attr( 'data-type' ) == 'static-image' || $that.attr( 'data-type' ) == 'image-pattern' ) {
			useImages = true;

			var $elements = $that.find( '.animate' );
			if ( $elements.length > 0 ) {
				verticalAlignContent( );

				if ( ProdoOptions.loader ) {
					jQuery( window ).on( 'prodo.complete', function( ) {
						jQuery( $elements ).each( function( i ) {
							var $this = jQuery( this );
							setTimeout( function( ) {
								$this.addClass( 'complete' );
							}, 0 + ( i * ProdoOptions.intro.animateDelay ) );
						} );
					} );
				} else {
					jQuery( $elements ).each( function( i ) {
						var $this = jQuery( this );
						setTimeout( function( ) {
							$this.addClass( 'complete' );
						}, 0 + ( i * ProdoOptions.intro.animateDelay ) );
					} );
				}
			} else {
				verticalAlignContent( );
			}

			jQuery( '<div />' ).addClass( 'slider fullscreen' ).css( { height: $that.height( ) } ).prependTo( 'body' );
			jQuery( '<div />' ).addClass( 'image' ).css( {
				opacity: 0,
				backgroundImage: "url('" + $that.attr( 'data-source' ) + "')",
				backgroundRepeat: ( ( $that.attr( 'data-type' ) == 'image-pattern' ) ? 'repeat' : 'no-repeat' ),
				backgroundSize: ( ( $that.attr( 'data-type' ) == 'image-pattern' ) ? 'auto' : 'cover' )
			} ).appendTo( '.slider' );

			jQuery( '.slider' ).imagesLoaded( function( ) {
				jQuery( this ).find( '.image' ).css( { opacity: 1 } );
			} );

			if ( $that.attr( 'data-parallax' ) == 'true' && ! Modernizr.touch ) {
				jQuery( document ).ready( function( ) {
					jQuery( '.slider' ).find( '.image' ).css( { backgroundRepeat: 'repeat' } ).parallax( '50%', 0.25 );
				} );
			}

			if ( jQuery( '#embed-video-control' ).length > 0 ) {
				jQuery( '#embed-video-control' ).click( function( ) {
					var $content = $that.find( '.content' ),
						$current = $content.find( '> div:visible' ),
						$staticRow = $current.find( '> div' ).not( '.hidden' ),
						$videoRow = $current.find( '#embed-video' ),
						$autocreate = $videoRow.find( '.autocreate' );

					$current.animate( { opacity: 0 }, { duration: 300, queue: false, complete: function( ) {
						$staticRow.hide( );
						$videoRow.removeClass( 'hidden' );

						var source = $autocreate.attr( 'data-source' );
						if ( /\?/.test( source ) ) {
							if ( ! /autoplay/i.test( source ) ) source += '&autoplay=1&wmode=transparent';
						} else source += '?autoplay=1&rel=0&wmode=transparent';

						jQuery( '<iframe />' ).attr( {
							width: $autocreate.attr( 'width' ) || 560,
							height: $autocreate.attr( 'height' ) || 315,
							src: source,
							frameborder: 0,
							allowfullscreen: 'allowfullscreen'
						} ).appendTo( $videoRow.find( '.video-responsive' ) );
						$autocreate.hide( );

						verticalAlignContent( );
						$current.animate( { opacity: 1 }, { duration: 300, queue: false } );
					} } );
				} );

				$that.find( '.icon.close i' ).click( function( ) {
					var $content = $that.find( '.content' ),
						$current = $content.find( '> div:visible' ),
						$staticRow = $current.find( '> div' ).not( '#embed-video' ),
						$videoRow = $current.find( '#embed-video' );

					$current.animate( { opacity: 0 }, { duration: 300, queue: false, complete: function( ) {
						$current.find( 'iframe' ).remove( );
						$staticRow.show( );
						$videoRow.addClass( 'hidden' );

						verticalAlignContent( );
						$current.animate( { opacity: 1 }, { duration: 300, queue: false } );
					} } );
				} );
			}
		}
		// Slideshow
		else if ( $that.attr( 'data-type' ) == 'slideshow' ) {
			useImages = true;

			var contentListShow = function( $that, $contentList, index ) {
				if ( ! $contentList ) {
					$contentList = jQuery( '#intro' );
					var $current = $contentList;
				} else {
					var $current = $contentList.find( '> div[data-index=' + index + ']' );
				}
				var $elements = $current.find( '.animate' );

				if ( $elements.length > 0 ) {
					$elements.removeClass( 'complete' );
					$current.show( );
					verticalAlignContent( );

					jQuery( $elements ).each( function( i ) {
						var $this = jQuery( this );
						setTimeout( function( ) {
							$this.addClass( 'complete' );
						}, ProdoOptions.intro.animateDelayFirst + ( i * ProdoOptions.intro.animateDelay ) );
					} );
				} else {
					$current.show( );
					verticalAlignContent( );
				}
			};
			var contentListHide = function( $that, $contentList, onComplete ) {
				if ( $contentList ) {
					var $current = $contentList.find( '> div:visible' );
					if ( typeof $current !== 'undefined' ) {
						$contentList.find( '> div' ).hide( );
					}
				}
				if ( onComplete && typeof onComplete == 'function' ) onComplete( );
			};

			var $imagesList = $that.find( $that.attr( 'data-images' ) ),
				$contentList = $that.attr( 'data-content' ) ? $that.find( $that.attr( 'data-content' ) ) : false,
				changeContent = $contentList !== false ? true : false,
				$toLeft = $that.attr( 'data-to-left' ) ? $that.find( $that.attr( 'data-to-left' ) ) : false,
				$toRight = $that.attr( 'data-to-right' ) ? $that.find( $that.attr( 'data-to-right' ) ) : false,
				delay = parseInt( $that.attr( 'data-delay' ) ) > 0 ? parseInt( $that.attr( 'data-delay' ) ) * 1000 : 7000;

			$imagesList.hide( );
			if ( changeContent ) $contentList.find( '> div' ).hide( );

			var images = [];
			$imagesList.find( '> img' ).each( function( index ) {
				images.push( { src: jQuery( this ).attr( 'src' ) } );
				jQuery( this ).attr( 'data-index', index );
			} );

			if ( changeContent ) {
				$contentList.find( '> div' ).each( function( index ) {
					jQuery( this ).attr( 'data-index', index );
				} );
			}

			var slideshowTimeout = false, slideshowCurrent = 0, slideshowIsFirst = true;
			var tempInt = ProdoOptions.intro.animateDelayFirst;
			var slideshowChange = function( $that, index ) {
				if ( index >= images.length ) index = 0;
				else if ( index < 0 ) index = images.length - 1;
				slideshowCurrent = index;

				var isFirst = $that.find( '.image' ).length == 0 ? true : false;
				if ( isFirst ) {
					jQuery( '<div />' ).css( {
						backgroundImage: "url('" + images[index].src + "')",
						backgroundRepeat: 'no-repeat'
					} ).addClass( 'image' ).appendTo( '.slider' );
				} else {
					jQuery( '<div />' ).css( {
						backgroundImage: "url('" + images[index].src + "')",
						backgroundRepeat: 'no-repeat',
						opacity: 0
					} ).addClass( 'image' ).appendTo( '.slider' );

					setTimeout( function( ) {
						$that.find( '.image:last-child' ).css( { opacity: 1 } );
						setTimeout( function( ) {
							$that.find( '.image:first-child' ).remove( );
						}, 1500 );
					}, 100 );
				}

				if ( $contentList || slideshowIsFirst ) {
					contentListHide( $that, $contentList, function( ) {
						if ( ! slideshowIsFirst ) {
							if ( ProdoOptions.intro.animateDelayFirst == 0 ) ProdoOptions.intro.animateDelayFirst = tempInt;
							contentListShow( $that, $contentList, index );
						} else {
							if ( ProdoOptions.loader ) {
								jQuery( window ).on( 'prodo.complete', function( ) {
									ProdoOptions.intro.animateDelayFirst = 0;
									contentListShow( $that, $contentList, index );
								} );
							} else {
								ProdoOptions.intro.animateDelayFirst = 0;
								contentListShow( $that, $contentList, index );
							}
						}
					} );
				}
				slideshowIsFirst = false;

				clearTimeout( slideshowTimeout );
				slideshowTimeout = setTimeout( function( ) {
					slideshowNext( $that );
				}, delay );
			};
			var slideshowCreate = function( ) {
				jQuery( '<div />' ).addClass( 'slider fullscreen' ).css( { height: jQuery( '#intro' ).height( ) } ).prependTo( 'body' );
				jQuery( window ).on( 'prodo.loaded', function( ) {
					$imagesList.imagesLoaded( function( ) {
						slideshowChange( jQuery( '.slider' ), 0 );
					} );
				} );
			};
			var slideshowNext = function( $slider ) {
				slideshowChange( $slider, slideshowCurrent + 1 );
			};
			var slideshowPrev = function( $slider ) {
				slideshowChange( $slider, slideshowCurrent - 1 );
			};

			slideshowCreate( );

			if ( $toLeft !== false && $toRight !== false ) {
				$toLeft.click( function( evt ) {
					slideshowPrev( jQuery( '.slider' ) );
					evt.preventDefault( );
				} );
				$toRight.click( function( evt ) {
					slideshowNext( jQuery( '.slider' ) );
					evt.preventDefault( );
				} );
			}
		}
		// Fullscreen Video
		else if ( $that.attr( 'data-type' ) == 'video' ) {
			useVideo = true;

			if ( Modernizr.touch ) {
				jQuery( '#video-mode' ).removeClass( 'animate' ).hide( );
				useImages = true;
				useVideo = false;
			}

			var $elements = $that.find( '.animate' );
			if ( $elements.length > 0 ) {
				verticalAlignContent( );

				if ( ProdoOptions.loader ) {
					jQuery( window ).on( 'prodo.complete', function( ) {
						jQuery( $elements ).each( function( i ) {
							var $this = jQuery( this );
							setTimeout( function( ) {
								$this.addClass( 'complete' );
							}, 0 + ( i * ProdoOptions.intro.animateDelay ) );
						} );
					} );
				} else {
					jQuery( $elements ).each( function( i ) {
						var $this = jQuery( this );
						setTimeout( function( ) {
							$this.addClass( 'complete' );
						}, 0 + ( i * ProdoOptions.intro.animateDelay ) );
					} );
				}
			} else {
				verticalAlignContent( );
			}

			if ( Modernizr.touch ) {
				jQuery( '<div />' ).addClass( 'slider fullscreen' ).css( { height: $that.height( ) } ).prependTo( 'body' );
				jQuery( '<div />' ).addClass( 'image' ).css( {
					opacity: 0,
					backgroundImage: "url('" + $that.attr( 'data-on-error' ) + "')",
					backgroundRepeat: 'no-repeat'
				} ).appendTo( '.slider' );

				jQuery( '.slider' ).imagesLoaded( function( ) {
					jQuery( this ).find( '.image' ).css( { opacity: 1 } );
				} );
			} else {
				jQuery( document ).ready( function( ) {
					var reserveTimer, onlyForFirst = true;
					jQuery( '[data-hide-on-another="true"]' ).remove( );

					jQuery( window ).on( 'YTAPIReady', function( ) {
						reserveTimer = setTimeout( function( ) {
							jQuery( window ).trigger( 'prodo.intro-video' );
							onlyForFirst = false;
						}, 5000 );
					} );
					jQuery( '<div />' ).addClass( 'slider fullscreen' ).prependTo( 'body' ).on( 'YTPStart', function( ) {
							if ( onlyForFirst ) {
								clearTimeout( reserveTimer );
								jQuery( window ).trigger( 'prodo.intro-video' );
								onlyForFirst = false;
							}
						} ).mb_YTPlayer( {
							videoURL: $that.attr( 'data-source' ),
							containment: '.slider',
							mute: $that.attr( 'data-mute' ) == 'true' ? true : false,
							loop: $that.attr( 'data-loop' ) == 'true' ? true : false,
							startAt: parseInt( $that.attr( 'data-start' ) ),
							stopAt: parseInt( $that.attr( 'data-stop' ) ),
							autoPlay: true,
							showControls: false,
							ratio: '16/9',
							showYTLogo: false,
							vol: 100,
							onError: function( ) {
								clearTimeout( reserveTimer );
								jQuery( window ).trigger( 'prodo.intro-video' );
							}
						}
					);

					if ( $that.attr( 'data-overlay' ) ) {
						jQuery( '<div />' ).addClass( 'overlay' ).css( { width: '100%', height: '100%', position: 'absolute', left: 0, top: 0, backgroundColor: 'rgba( 0, 0, 0, ' + $that.attr( 'data-overlay' ) + ' )' } ).appendTo( '.slider.fullscreen' );
					}
				} );

				var videoMode = false, videoModeSelector = '#intro .mouse, #intro .content, .slider.fullscreen .overlay';
				jQuery( '#video-mode' ).click( function( ) {
					jQuery( videoModeSelector ).animate( { opacity: 0 }, { duration: 500, queue: false, complete: function( ) {
						if ( ! videoMode ) {
							jQuery( '.slider' ).unmuteYTPVolume( );
							jQuery( '<div />' ).appendTo( '#intro' ).css( {
								position: 'absolute',
								textAlign: 'center',
								bottom: '30px',
								color: '#FFF',
								left: 0,
								right: 0,
								opacity: 0
							} ).addClass( 'click-to-exit' );
							jQuery( '<h5 />' ).appendTo( '.click-to-exit' ).text( 'Click to exit full screen' );

							setTimeout( function( ) {
								jQuery( '.click-to-exit' ).animate( { opacity: 1 }, { duration: 500, queue: false, complete: function( ) {
									setTimeout( function( ) {
										jQuery( '.click-to-exit' ).animate( { opacity: 0 }, { duration: 500, queue: false, complete: function( ) {
											jQuery( this ).remove( );
										} } )
									}, 1500 );
								} } );
							}, 500 );
						}

						videoMode = true;
						jQuery( this ).hide( );
					} } );
				} );

				$that.click( function( evt ) {
					if ( videoMode && jQuery( evt.target ).is( '#intro' ) ) {
						jQuery( '.slider' ).muteYTPVolume( );
						jQuery( videoModeSelector ).show( ).animate( { opacity: 1 }, { duration: 500, queue: false } );
						$that.find( '.click-to-exit' ).remove( );
						videoMode = false;
					}
				} );
			}
		}
	},

	// Portfolio
	portfolio: function( ) {
		if ( jQuery( '.portfolio-item' ).length == 0 ) {
			return;
		}

		var calculatePortfolioItems = function( ) {
			var sizes = { lg: 6, md: 6, sm: 4, xs: 2 }, $that = jQuery( '.portfolio-items' ),
				w = jQuery( window ).width( ), onLine = 0, value = 0;

			if ( $that.attr( 'data-on-line-lg' ) > 0 ) sizes.lg = parseInt( $that.attr( 'data-on-line-lg' ) );
			if ( $that.attr( 'data-on-line-md' ) > 0 ) sizes.md = parseInt( $that.attr( 'data-on-line-md' ) );
			if ( $that.attr( 'data-on-line-sm' ) > 0 ) sizes.sm = parseInt( $that.attr( 'data-on-line-sm' ) );
			if ( $that.attr( 'data-on-line-xs' ) > 0 ) sizes.xs = parseInt( $that.attr( 'data-on-line-xs' ) );

			if ( w <= 767 ) onLine = sizes.xs;
			else if ( w >= 768 && w <= 991 ) onLine = sizes.sm;
			else if ( w >= 992 && w <= 1199 ) onLine = sizes.md;
			else onLine = sizes.lg;

			value = Math.floor( w / onLine );
			jQuery( '.portfolio-item' ).css( { width: value + 'px', height: value + 'px' } );
		};

		jQuery( window ).resize( function( ) {
			calculatePortfolioItems( );
		} );
		jQuery( document ).ready( function( ) {
			calculatePortfolioItems( );
			jQuery( '.portfolio-items' ).isotope( {
				itemSelector: '.portfolio-item',
				layoutMode: 'fitRows'
			} );
		} );

		jQuery( '.portfolio-filters a' ).click( function( evt ) {
			var $that = jQuery( this );
			jQuery( '.portfolio-filters a' ).removeClass( 'active' );
			$that.addClass( 'active' );

			jQuery( '.portfolio-items' ).isotope( { filter: $that.attr( 'data-filter' ) } );
			evt.preventDefault( );
		} );

		var closeProject = function( ) {
			jQuery( '#portfolio-details' ).parent( ).animate( { opacity: 0 }, { duration: 600, queue: false } );
			jQuery( '#portfolio-details' ).parent( ).animate( { height: 0 }, { duration: 700, queue: false, complete: function( ) {
				jQuery( this ).find( '#portfolio-details' ).hide( ).html( '' ).removeAttr( 'data-current' );
				jQuery( this ).css( { height: 'auto', opacity: 1 } );
			} } );
		};

		jQuery( '.portfolio-item a' ).filter( '[data-url]' ).click( function( evt ) {
			evt.preventDefault( );
			var $that = jQuery( this );

			if ( $that.parent( ).parent( ).find( '.loading' ).length == 0 ) {
				jQuery( '<div />' ).addClass( 'loading' ).appendTo( $that.parent( ).parent( ) );
				$that.parent( ).parent( ).addClass( 'active' );

				var $loading = jQuery( this ).parent( ).parent( ).find( '.loading' ),
					$container = jQuery( '#portfolio-details' ), $parent = $container.parent( ), timer = 1, projectRel;

				if ( $container.is( ':visible' ) ) {
					closeProject( );
					timer = 800;
					$loading.animate( { width: '70%' }, { duration: 2000, queue: false } );
				}

				setTimeout( function( ) {
					$loading.stop( true, false ).animate( { width: '70%' }, { duration: 6000, queue: false } );
					jQuery.get( $that.attr( 'data-url' ) ).done( function( response ) {
						$container.html( response );
						$container.imagesLoaded( function( ) {
							$loading.stop( true, false ).animate( { width: '100%' }, { duration: 500, queue: true } );
							$loading.animate( { opacity: 0 }, { duration: 200, queue: true, complete: function( ) {
								$that.parent( ).parent( ).removeClass( 'active' );
								jQuery( this ).remove( );

								$parent.css( { opacity: 0, height: 0 } );
								$container.show( );

								ProdoTheme.imageSlider( $container, function( ) {
									jQuery( document ).scrollTo( $container, 600, { offset: { top: -85, left: 0 } } );
									$parent.animate( { opacity: 1 }, { duration: 700, queue: false } );
									$parent.animate( { height: $container.outerHeight( true ) }, { duration: 600, queue: false, complete: function( ) {
										projectRel = $that.parent( ).parent( ).is( '.portfolio-item' ) ? $that.parent( ).parent( ) : $that.parent( ).parent( ).parent( );
										jQuery( this ).css( { height: 'auto' } );
										$container.attr( 'data-current', projectRel.attr( 'rel' ) );
									} } );
								} );
							} } );
						} );
					} ).fail( function( obj ) {
						$that.parent( ).parent( ).removeClass( 'active' );
						$loading.remove( );
					} );
				}, timer );
			}
		} );

		jQuery( document.body ).on( 'click', '#portfolio-details .icon.close i', function( ) {
			closeProject( );
		} );

		// Anchor Links for Projects
		var dh = document.location.hash;
		if ( /#view-/i.test( dh ) ) {
			var $item = jQuery( '[rel="' + dh.substr( 6 ) + '"]' );
			if ( $item.length > 0 ) {
				jQuery( document ).scrollTo( '#portfolio', 0, { offset: { top: 0, left: 0 } } );
				
				if ( ProdoOptions.loader ) {
					jQuery( window ).on( 'prodo.complete', function( ) {
						$item.find( '.href a' ).trigger( 'click' );
					} );
				} else {
					$item.find( '.href a' ).trigger( 'click' );
				}
			}
		}

		jQuery( 'a[href*="#view-"]' ).not( '[data-url]' ).click( function( ) {
			var $item = jQuery( '[rel="' + jQuery( this ).attr( 'href' ).split( '#' ).pop( ).substr( 5 ) + '"]' );
			if ( $item.length > 0 ) {
				jQuery( document ).scrollTo( '#portfolio', ProdoOptions.scrollSpeed, { offset: { top: -85, left: 0 }, onAfter: function( ) {
					$item.find( '.href a' ).trigger( 'click' );
				} } );
			}
		} );
	},

	// Parallax Sections
	parallax: function( ) {
		if ( jQuery( '.parallax' ).length == 0 ) {
			return;
		}

		jQuery( window ).load( function( ) {
			jQuery( '.parallax' ).each( function( ) {
				if ( jQuery( this ).attr( 'data-image' ) ) {
					jQuery( this ).css( { backgroundImage: 'url( ' + jQuery( this ).attr( 'data-image' ) + ' )' } );
					if ( ProdoOptions.parallax && ! Modernizr.touch && ! /MSIE/.test( navigator.userAgent ) ) {
						jQuery( this ).parallax( '50%', 0.5 );
					}
				}
			} );
		} );
	},

	// Video Background for Sections
	videos: function( ) {
		if ( Modernizr.touch ) {
			jQuery( '.section.video' ).remove( );
			return;
		}

		if ( jQuery( '.section.video' ).length > 0 ) {
			var tag = document.createElement( 'script' );
			tag.src = "http://www.youtube.com/player_api";
			var firstScriptTag = document.getElementsByTagName( 'script' )[0];
			firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );

			jQuery( window ).resize( function( ) {
				jQuery( '.section.video' ).each( function( ) {
					jQuery( this ).css( { height: jQuery( this ).find( '.video-container .container' ).outerHeight( true ) } );
				} );
			} ).resize( );
		}
	},

	// Google Maps
	map: function( ) {
		// Shortcode
		jQuery( '.googlemap' ).each( function( ) {
			var address = jQuery( this ).attr( 'data-address' ) || false,
				latLng  = jQuery( this ).attr( 'data-latlng' ) || false,
				zoom    = jQuery( this ).attr( 'data-zoom' ) || 15,
				marker  = [];

			if ( latLng ) {
				marker.push( { latitude: latLng.split( ',' ).shift( ), longitude: latLng.split( ',' ).pop( ) } );
			} else {
				marker.push( { address: ( address || 'New York, United States' ) } );
			}

			jQuery( this ).gMap( {
				zoom: parseInt( zoom ),
				scrollWheel: false,
				maptype: 'ROADMAP',
				markers: marker
			} );
		} );

		// Site Section
		if ( jQuery( '#google-map' ).length == 0 ) {
			return;
		}

		var $map = jQuery( '#google-map' );
		jQuery( window ).load( function( ) {
			var coordY = $map.attr( 'data-latitude' ), coordX = $map.attr( 'data-longitude' );
			var latlng = new google.maps.LatLng( coordY, coordX );
			var settings = {
				zoom: parseInt( $map.attr( 'data-map-zoom' ) || 14 ),
				center: new google.maps.LatLng( coordY, coordX ),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				mapTypeControl: false,
				scrollwheel: false,
				draggable: true,
				mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
				navigationControl: false,
				navigationControlOptions: { style: google.maps.NavigationControlStyle.SMALL },
				styles: [ { "stylers": [ { "saturation": -100 }, { "lightness": 20 }, { "gamma": 1.2 } ] } ]
			};
			var map = new google.maps.Map( $map.get( 0 ), settings );
			google.maps.event.addDomListener( window, "resize", function( ) {
				var center = map.getCenter( );
				google.maps.event.trigger( map, "resize" );
				map.setCenter( center );
			} );
			
			if ( $map.attr( 'data-marker' ) ) {
				var contentString = $map.parent( ).find( '#map-info' ).html( ) || '';
				var infowindow = new google.maps.InfoWindow( { content: contentString } );
				var companyImage = new google.maps.MarkerImage( $map.attr( 'data-marker' ), null, null, new google.maps.Point( 27, 62 ), new google.maps.Size( 54, 64 ) );
				var companyPos = new google.maps.LatLng( coordY, coordX );
				var companyMarker = new google.maps.Marker( {
					position: companyPos,
					map: map,
					icon: companyImage,
					zIndex: 3
				} );
				google.maps.event.addListener( companyMarker, 'click', function( ) {
					infowindow.open( map, companyMarker );
				} );
			}

			// Shadow for Map
			jQuery( '<i />' ).addClass( 'shadow-top' ).appendTo( $map.parent( ) );
			jQuery( '<i />' ).addClass( 'shadow-bottom' ).appendTo( $map.parent( ) );
		} );
	},

	// Content Slider
	contentSlider: function( $root, element ) {
		if ( typeof $root === 'undefined' ) $root = jQuery( 'body' );
		if ( typeof element === 'undefined' ) element = 'div';

		$root.find( '.content-slider' ).each( function( ) {
			var $that = jQuery( this ), timeout, delay = false, process = false;
			$that.css( { position: 'relative' } ).find( '> ' + element ).each( function( index ) {
				$that.height( jQuery( this ).outerHeight( true ) );
				jQuery( this ).attr( 'data-index', index );
				jQuery( this ).css( { position: 'relative', left: 0, top: 0 } );
				if ( index > 0 ) jQuery( this ).hide( );
				else $that.attr( 'data-index', 0 );
			} );

			if ( $that.attr( 'data-arrows' ) ) {
				var $arrows = jQuery( $that.attr( 'data-arrows' ) );
			} else {
				var $arrows = $that.parent( );
			}

			if ( $that.attr( 'data-delay' ) ) {
				delay = parseInt( $that.attr( 'data-delay' ) );
				timeout = setInterval( function( ) {
					$arrows.find( '.arrow.right' ).click( );
				}, delay );
			}
			if ( $that.find( '> ' + element + '[data-index]' ).length < 2 ) {
				$arrows.hide( );
				clearInterval( timeout );
				delay = false;
			}

			$arrows.find( '.arrow' ).click( function( ) {
				if ( ! process ) {
					process = true;
					clearInterval( timeout );

					var index = parseInt( $that.attr( 'data-index' ) ), last = parseInt( $that.find( '> ' + element + ':last-child' ).attr( 'data-index' ) ), set;
					if ( jQuery( this ).hasClass( 'left' ) ) {
						set = index == 0 ? last : index - 1;
						var property = [ { left: 100 }, { left: -100 } ];
					} else {
						set = index == last ? 0 : index + 1;
						var property = [ { left: -100 }, { left: 100 } ];
					}
					var $current = $that.find( '> ' + element + '[data-index=' + index + ']' ),
						$next = $that.find( '> ' + element + '[data-index=' + set + ']' );

					$that.attr( 'data-index', set );
					$current.css( { left: 'auto', right: 'auto' } );
					$current.animate( { opacity: 0 }, { duration: 300, queue: false } );

					$current.animate( property[0], { duration: 300, queue: false, complete: function( ) {
						jQuery( this ).hide( ).css( { opacity: 1 } ).css( { left: 0 } );

						$that.animate( { height: $next.outerHeight( true ) }, { duration: ( ( $that.outerHeight( true ) == $next.outerHeight( true ) ) ? 0 : 200 ), queue: false, complete: function( ) {
							$next.css( { opacity: 0, left: 'auto', right: 'auto' } ).css( property[1] ).show( );
							$next.animate( { opacity: 1 }, { duration: 300, queue: false } );

							$next.animate( { left: 0 }, { duration: 300, queue: false, complete: function( ) {
								if ( delay !== false ) {
									timeout = setInterval( function( ) {
										$arrows.find( '.arrow.right' ).click( );
									}, delay );
								}
								process = false;
							} } );
						} } );
					} } );
				}
			} );

			jQuery( window ).resize( function( ) {
				$that.each( function( ) {
					jQuery( this ).height( jQuery( this ).find( '> ' + element + ':visible' ).outerHeight( true ) );
				} );
			} ).resize( );
		} );
	},

	// Contact Form
	contact: function( ) {
		if ( jQuery( '#prodo-contact-form' ).length == 0 ) {
			return;
		}

		var $name = jQuery( '.field-name' ), $email = jQuery( '.field-email' ), $phone = jQuery( '.field-phone' ),
			$text = jQuery( '.field-message' ), $button = jQuery( '#contact-submit' ),
			$action = jQuery( '.field-action' );

		jQuery( '.field-name, .field-email, .field-message' ).focus( function( ) {
			if ( jQuery( this ).parent( ).find( '.error' ).length > 0 ) jQuery( this ).parent( ).find( '.error' ).fadeOut( 150, function( ) {
				jQuery( this ).remove( );
			} );
		} );

		$button.removeAttr( 'disabled' );
		$button.click( function( ) {
			var $that = jQuery( this );
			var fieldNotice = function( $that ) {
				if ( $that.parent( ).find( '.error' ).length == 0 ) {
					jQuery( '<span class="error"><i class="fa fa-times"></i></span>' ).appendTo( $that.parent( ) ).fadeIn( 150 );
				}
			};

			if ( $name.val( ).length < 1 ) fieldNotice( $name );
			if ( $email.val( ).length < 1 ) fieldNotice( $email );
			if ( $text.val( ).length < 1 ) fieldNotice( $text );

			if ( jQuery( '#prodo-contact-form' ).find( '.field .error' ).length == 0 ) {
				jQuery( document ).ajaxStart( function( ) {
					$button.attr( 'disabled', true );
				} );
				jQuery.post( $action.attr( 'data-url' ), {
					action: 'contact',
					name: $name.val( ),
					email: $email.val( ),
					phone: $phone.val( ),
					message: $text.val( )
				}, function( response ) {
					var data = jQuery.parseJSON( response );
					if ( data.status == 'error' && data.error == 'name' ) {
						fieldNotice( $name );
						$button.removeAttr( 'disabled' );
					}
					else if ( data.status == 'error' && data.error == 'email' ) {
						fieldNotice( $email );
						$button.removeAttr( 'disabled' );
					}
					else if ( data.status == 'error' && data.error == 'message' ) {
						fieldNotice( $text );
						$button.removeAttr( 'disabled' );
					}
					else if ( data.status == 'error' ) {
						$button.text( 'Unknown Error :(' );
					}
					else {
						jQuery( '.contact-form-area' ).animate( { opacity: 0 }, { duration: 200, queue: false } );
						jQuery( '.contact-form-area' ).animate( { height: jQuery( '.contact-form-result' ).outerHeight( true ) }, { duration: 300, queue: false, complete: function( ) {
							jQuery( this ).css( { height: 'auto' } ).hide( );
							jQuery( '.contact-form-result' ).fadeIn( 300 );
						} } );
					}
				} );
			}
		} );
	},

	// Tweaks for Oldest Browsers
	tweaks: function( ) {
		// Input Placeholders
		if ( ! Modernizr.input.placeholder ) {
			jQuery( 'input[placeholder], textarea[placeholder]' ).each( function( ) {
				jQuery( this ).val( jQuery( this ).attr( 'placeholder' ) ).focusin( function( ) {
					if ( jQuery( this ).val( ) == jQuery( this ).attr( 'placeholder' ) ) jQuery( this ).val( '' );
				} ).focusout( function( ) {
					if ( jQuery( this ).val( ) == 0 ) jQuery( this ).val( jQuery( this ).attr( 'placeholder' ) );
				} );
			} );
		}

		// Error Pages
		if ( jQuery( '#error-page' ).length > 0 ) {
			jQuery( window ).resize( function( ) {
				jQuery( '#error-page' ).css( { marginTop: - Math.ceil( jQuery( '#error-page' ).outerHeight( ) / 2 ) } );
			} ).resize( );
		}

		// Comment form submit button
		jQuery( '.comment-form #submit' ).addClass( 'btn btn-default' );
	},

	// Shortcodes
	shortcodes: function( ) {
		// Progress Bars
		if ( jQuery( '.progress .progress-bar' ).length > 0 ) {
			setTimeout( function( ) {
				if ( ProdoOptions.loader ) {
					jQuery( window ).on( 'prodo.complete', function( ) {
						jQuery( window ).scroll( function( ) {
							var scrollTop = jQuery( window ).scrollTop( );
							jQuery( '.progress .progress-bar' ).each( function( ) {
								var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
								if ( scrollTop > itemTop && $that.outerWidth( ) == 0 ) {
									var percent = jQuery( this ).attr( 'aria-valuenow' ) + '%';
									var $value = jQuery( this ).parent( ).parent( ).find( '.progress-value' );
									if ( $value.length > 0 ) $value.css( { width: percent, opacity: 0 } ).text( percent );

									$that.animate( { width: percent }, { duration: 1500, queue: false, complete: function( ) {
										if ( $value.length > 0 ) {
											$value.animate( { opacity: 1 }, { duration: 300, queue: false } );
										}
									} } );
								}
							} );
						} ).scroll( );
					} );
				} else {
					jQuery( window ).scroll( function( ) {
						var scrollTop = jQuery( window ).scrollTop( );
						jQuery( '.progress .progress-bar' ).each( function( ) {
							var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
							if ( scrollTop > itemTop && $that.outerWidth( ) == 0 ) {
								var percent = jQuery( this ).attr( 'aria-valuenow' ) + '%';
								var $value = jQuery( this ).parent( ).parent( ).find( '.progress-value' );
								if ( $value.length > 0 ) $value.css( { width: percent, opacity: 0 } ).text( percent );

								$that.animate( { width: percent }, { duration: 1500, queue: false, complete: function( ) {
									if ( $value.length > 0 ) {
										$value.animate( { opacity: 1 }, { duration: 300, queue: false } );
									}
								} } );
							}
						} );
					} ).scroll( );
				}
			}, 1 );
		}

		// Circular Bars
		if ( jQuery( '.circular-bars' ).length > 0 ) {
			if ( Modernizr.canvas ) {
				jQuery( '.circular-bars input' ).each( function( ) {
					jQuery( this ).val( 0 ).knob( {
						fgColor: jQuery( this ).attr( 'data-color' ) || jQuery( 'a' ).css( 'color' ),
						width: '90px',
						readOnly: true,
						thickness: .10
					} );
				} );
				setTimeout( function( ) {
					if ( ProdoOptions.loader ) {
						jQuery( window ).on( 'prodo.complete', function( ) {
							jQuery( window ).scroll( function( ) {
								var scrollTop = jQuery( window ).scrollTop( );
								jQuery( '.circular-bars input' ).each( function( ) {
									var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
									if ( scrollTop > itemTop && $that.val( ) == 0 ) {
										jQuery( { value: 0 } ).animate( { value: $that.attr( 'data-value' ) }, {
											duration: 1500,
											queue: false,
											step: function( ) {
												$that.val( Math.ceil( this.value ) ).trigger( 'change' );
											}
										} );
									}
								} );
							} ).scroll( );
						} );
					} else {
						jQuery( window ).scroll( function( ) {
							var scrollTop = jQuery( window ).scrollTop( );
							jQuery( '.circular-bars input' ).each( function( ) {
								var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
								if ( scrollTop > itemTop && $that.val( ) == 0 ) {
									jQuery( { value: 0 } ).animate( { value: $that.attr( 'data-value' ) }, {
										duration: 1500,
										queue: false,
										step: function( ) {
											$that.val( Math.ceil( this.value ) ).trigger( 'change' );
										}
									} );
								}
							} );
						} ).scroll( );
					}
				}, 1 );
			} else {
				var value;
				jQuery( '.circular-bars' ).each( function( ) {
					if ( jQuery( this ).attr( 'data-on-error-hide' ) ) {
						value = jQuery( this ).attr( 'data-on-error-hide' );
						if ( value == 'this' ) jQuery( this ).hide( );
						else jQuery( value ).hide( );
					}
				} );
			}
		}

		// Milestone Counters
		if ( jQuery( '.milestone' ).length > 0 ) {
			jQuery( '.milestone' ).each( function( ) {
				jQuery( this ).find( '.counter' ).text( '0' );
			} );
			setTimeout( function( ) {
				if ( ProdoOptions.loader ) {
					jQuery( window ).on( 'prodo.complete', function( ) {
						jQuery( window ).scroll( function( ) {
							var scrollTop = jQuery( window ).scrollTop( );
							jQuery( '.milestone' ).each( function( ) {
								var $that = jQuery( this ), $counter = $that.find( '.counter' ),
									itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
								if ( scrollTop > itemTop && parseInt( $counter.text( ) ) == 0 ) {
									jQuery( { value: parseInt( $counter.attr( 'data-from' ) ) } ).animate( {
										value: $counter.attr( 'data-to' )
									}, {
										duration: parseInt( $counter.attr( 'data-speed' ) ) || 2000,
										queue: false,
										step: function( ) {
											$counter.text( Math.ceil( this.value ) );
										}
									} );
								}
							} );
						} ).scroll( );
					} );
				} else {
					jQuery( window ).scroll( function( ) {
						var scrollTop = jQuery( window ).scrollTop( );
						jQuery( '.milestone' ).each( function( ) {
							var $that = jQuery( this ), $counter = $that.find( '.counter' ),
								itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
							if ( scrollTop > itemTop && parseInt( $counter.text( ) ) == 0 ) {
								jQuery( { value: parseInt( $counter.attr( 'data-from' ) ) } ).animate( {
									value: $counter.attr( 'data-to' )
								}, {
									duration: parseInt( $counter.attr( 'data-speed' ) ) || 2000,
									queue: false,
									step: function( ) {
										$counter.text( Math.ceil( this.value ) );
									}
								} );
							}
						} );
					} ).scroll( );
				}  
			}, 1 );
		}
	},

	// Images Slider
	imageSlider: function( $root, onComplete ) {
		if ( typeof $root === 'undefined' ) $root = jQuery( 'body' );
		if ( $root.find( '.image-slider' ).length == 0 ) {
			if ( onComplete && typeof onComplete == 'function' ) onComplete( );
			return;
		}

		$root.find( '.image-slider' ).each( function( ) {
			var $that = jQuery( this ), $arrows = $that.find( '.arrows' );
			var $list = jQuery( this ).find( '> div' ).not( '.arrows' );
			var timeout, delay = false, process = false;

			var setHeight = function( $that, onComplete ) {
				$that.css( {
					height: $that.find( '> div:visible img' ).outerHeight( true )
				} );
				if ( onComplete && typeof onComplete == 'function' ) onComplete( );
			};

			if ( $that.attr( 'data-delay' ) ) {
				delay = parseInt( $that.attr( 'data-delay' ) );
				timeout = setInterval( function( ) {
					$arrows.find( '.arrow.right' ).click( );
				}, delay );
			}

			jQuery( this ).imagesLoaded( function( ) {
				jQuery( this ).css( { position: 'relative' } );
				$list.hide( ).css( {
					position: 'absolute',
					top: 0,
					left: 0,
					zIndex: 1,
					width: '100%',
					paddingLeft: 15,
					paddingRight: 15,
				} );
				$list.eq( 0 ).show( );

				setHeight( $that, onComplete );
				jQuery( window ).resize( function( ) {
					setTimeout( function( ) {
						setHeight( $that );
					}, 1 );
				} );

				if ( $list.length == 1 ) {
					$arrows.hide( );
					clearInterval( timeout );
					delay = false;
				}
			} );

			$arrows.find( '.arrow' ).on( 'click', function( evt ) {
				if ( process ) {
					evt.preventDefault( );
					return;
				}
				clearInterval( timeout );

				var isRight = jQuery( this ).hasClass( 'right' );
				var $current = $that.find( '> div:visible' ).not( '.arrows' ), $next;

				if ( isRight ) {
					$next = $current.next( );
					if ( ! $next || $next.is( '.arrows' ) ) $next = $list.eq( 0 );
				} else {
					if ( $current.is( ':first-child' ) ) $next = $list.last( );
					else $next = $current.prev( );
				}

				process = true;
				$current.css( { zIndex: 1 } );

				$next.parent( ).stop( ).animate( { height: $next.outerHeight( true ) }, { duration: 310, queue: false } );
				$next.css( { opacity: 0, zIndex: 2 } ).show( ).animate( { opacity: 1 }, { duration: 300, queue: false, complete: function( ) {
					$current.hide( ).css( { opacity: 1 } );
					
					if ( delay !== false ) {
						timeout = setInterval( function( ) {
							$arrows.find( '.arrow.right' ).click( );
						}, delay );
					}
					process = false;
				} } );
			} );
		} );
	},

	// Twitter Widget
	twitter: function( ) {
		if ( jQuery( '.twitter-feed' ).length == 0 ) {
			return;
		}
		
		jQuery( window ).load( function( ) {
			var $this = jQuery( '.twitter-feed' ).find( 'ul' ).addClass( 'content-slider' ).parent( );
			ProdoTheme.contentSlider( $this, 'li' );
		} );
	},

	// Share Functions
	share: function( network, title, image, url ) {
		// Window Size
		var w = 650, h = 350, params = 'width=' + w + ', height=' + h + ', resizable=1';

		// Select Data
		if ( typeof title === 'undefined' ) title = jQuery( 'title' ).text( );
		else if ( typeof title === 'string' ) {
			if ( jQuery( title ).length > 0 ) title = jQuery( title ).text( );
		}
		if ( typeof image === 'undefined' ) image = '';
		else if ( typeof image === 'string' ) {
			if ( ! /http/i.test( image ) ) {
				if ( jQuery( image ).length > 0 ) {
					if ( jQuery( image ).is( 'img' ) ) image = jQuery( image ).attr( 'src' );
					else image = jQuery( image ).find( 'img' ).eq( 0 ).attr( 'src' );
				} else image = '';
			}
		}
		if ( typeof url === 'undefined' ) url = document.location.href;
		else url = document.location.protocol + '//' + document.location.host + document.location.pathname + url;

		// Share
		if ( network == 'twitter' ) {
			return window.open( 'http://twitter.com/intent/tweet?text=' + encodeURIComponent( title + ' ' + url ), 'share', params );
		} else if ( network == 'facebook' ) {
			return window.open( 'https://www.facebook.com/sharer/sharer.php?s=100&p[url]=' + encodeURIComponent( url ) + '&p[title]=' + encodeURIComponent( title ) + '&p[images][0]=' + encodeURIComponent( image ), 'share', params );
		} else if ( network == 'pinterest' ) {
			window.open( 'http://pinterest.com/pin/create/bookmarklet/?media=' + image + '&description=' + title + ' ' + encodeURIComponent( url ), 'share', params );
		} else if ( network == 'google' ) {
			return window.open( 'https://plus.google.com/share?url=' + encodeURIComponent( url ), 'share', params );
		} else if ( network == 'linkedin' ) {
			return window.open( 'http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent( url ) + '&title=' + title, 'share', params );
		}
		return;
	},

	// Blog
	blog: function( ) {
		if ( jQuery( '.blog-masonry' ).length == 0 ) {
			return;
		}

		// Get Column Width
		function getColumnWidth( ) {
			var $that = jQuery( '.blog-masonry' ), w = $that.outerWidth( true ) - 30,
				ww = jQuery( window ).width( ), columns;

			if ( $that.hasClass( 'blog-masonry-four' ) ) columns = 4;
			else if ( $that.hasClass( 'blog-masonry-three' ) ) columns = 3;
			else if ( $that.hasClass( 'blog-masonry-two' ) ) columns = 2;
			else columns = 1;

			if ( ww <= 767 ) columns = 1;
			else if ( ww >= 768 && ww <= 991 && columns > 2 ) columns -= 1;
			return Math.floor( w / columns );
		}

		jQuery( '.blog-post.masonry' ).css( { width: getColumnWidth( ) } );
		jQuery( '.blog-masonry' ).imagesLoaded( function( ) {
			jQuery( this ).isotope( {
				itemSelector: '.blog-post.masonry',
				resizable: false,
				transformsEnabled: false,
				masonry: { columnWidth: getColumnWidth( ) }
			} );
		} );

		jQuery( window ).resize( function( ) {
			var size = getColumnWidth( );
			jQuery( '.blog-post.masonry' ).css( { width: size } );
			jQuery( '.blog-masonry' ).isotope( {
				masonry: { columnWidth: size }
			} );
		} );
	},

	// Animations
	animations: function( ) {
		if ( Modernizr.touch ) {
			ProdoOptions.animations = false;
		}

		if ( ! ProdoOptions.animations ) {
			jQuery( '.animation[class*="animation-"]' ).removeClass( 'animation' );
		} else {
			var animationItem = jQuery( '.animation[class*="animation-"]' );

			if ( animationItem.length ) {
				var delay;

				animationItem.not( '.active' ).each( function( i ) {
					if ( i != 0 && jQuery( this ).offset( ).top == jQuery( animationItem.get( i - 1 ) ).offset( ).top ) {
						delay ++
					} else delay = 0;

					jQuery( this ).css( {
						'-webkit-transition-delay': delay * 150 + 'ms',
						'-moz-transition-delay': delay * 150 + 'ms',
						'-o-transition-delay': delay * 150 + 'ms',
						'-ms-transition-delay': delay * 150 + 'ms',
						'transition-delay': delay * 150 + 'ms'
					} );
				} );

				setTimeout( function( ) {
					if ( ProdoOptions.loader ) {
						jQuery( window ).on( 'prodo.complete', function( ) {
							jQuery( window ).scroll( function( ) {
								var scrollTop = jQuery( window ).scrollTop( );
								
								animationItem.not( '.active' ).each( function( ) {
									var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.outerHeight( ) / 2;
									if ( scrollTop > itemTop ) jQuery( this ).addClass( 'active' );
								} );
							} ).scroll( );
						} );
					} else {
						jQuery( window ).scroll( function( ) {
							var scrollTop = jQuery( window ).scrollTop( );
							
							animationItem.not( '.active' ).each( function( ) {
								var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.outerHeight( ) / 2;
								if ( scrollTop > itemTop ) jQuery( this ).addClass( 'active' );
							} );
						} ).scroll( );
					}
				}, 1 );
			}
		}

		/*** How it looks (iMacs Preview) ***/
		if ( jQuery( '.imacs' ).length > 0 ) {
			if ( ! ProdoOptions.animations ) {
				jQuery( '.imacs' ).find( '.item' ).not( '.center' ).addClass( 'complete' );
				return;
			}
			setTimeout( function( ) {
				if ( ProdoOptions.loader ) {
					jQuery( window ).on( 'prodo.complete', function( ) {
						jQuery( window ).scroll( function( ) {
							var scrollTop = jQuery( window ).scrollTop( );
							jQuery( '.imacs' ).find( '.item' ).not( '.complete' ).each( function( ) {
								var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
								if ( scrollTop > itemTop && ! $that.hasClass( 'center' ) ) {
									$that.addClass( 'complete' );
								}
							} );
						} ).scroll( );
					} );
				} else {
					jQuery( window ).scroll( function( ) {
						var scrollTop = jQuery( window ).scrollTop( );
						jQuery( '.imacs' ).find( '.item' ).not( '.complete' ).each( function( ) {
							var $that = jQuery( this ), itemTop = $that.offset( ).top - jQuery( window ).height( ) + $that.height( ) / 2;
							if ( scrollTop > itemTop && ! $that.hasClass( 'center' ) ) {
								$that.addClass( 'complete' );
							}
						} );
					} ).scroll( );
				}
			}, 1 );
		}
	}
};

// Initialize
ProdoTheme.init( );

// Share Functions
function shareTo( network, title, image, url ) {
	return ProdoTheme.share( network, title, image, url );
}

// Video Background for Sections
function onYouTubeIframeAPIReady( ) {
	jQuery( '.section.video' ).each( function( index ) {
		var $that = jQuery( this ), currentId = 'video-background-' + index;
		jQuery( '<div class="video-responsive"><div id="' + currentId + '"></div></div>' ).prependTo( $that );

		var player = new YT.Player( currentId, {
			height: '100%',
			width: '100%',            
			playerVars: {
				'rel': 0,
				'autoplay': 1,
				'loop': 1,
				'controls': 0,
				'start': parseInt( $that.attr( 'data-start' ) ),
				'autohide': 1,
				'wmode': 'opaque',
				'playlist': currentId
			},
			videoId: $that.attr( 'data-source' ),
			events: {
				'onReady': function( evt ) {
					evt.target.mute( );
				},
				'onStateChange': function( evt ) {
					if ( evt.data === 0 ) evt.target.playVideo( );
				}
			}
		} );
	} );
}