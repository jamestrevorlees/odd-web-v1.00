<?php global $prodoConfig; ?>
<?php $height = get_post_meta( get_the_ID( ), 'section-height', true ); ?>
<?php $height = ! empty( $height ) ? $height : '100%'; ?>

<section class="intro" id="intro" data-type="slideshow" data-images=".images-list" data-content=".content" data-to-left=".arrow.left" data-to-right=".arrow.right" data-delay="<?php echo ( $prodoConfig['home-slideshow-timeout'] >= 5 ? intval( $prodoConfig['home-slideshow-timeout'] ) : 5 ); ?>" style="height: <?php echo esc_attr( $height ); ?>;">
	<div class="images-list">
		<?php echo ProdoTheme::slideshowImages( '<img src="%s" alt="">', get_post_meta( get_the_ID( ), 'slideshow-images', true ) ); ?>
	</div>
	<div class="container">
		<div class="content">
			<?php $slides = ProdoTheme::slideshowSlides( get_the_ID( ) ); ?>
			<?php if ( $slides !== false ) : foreach( $slides as $slide ) : ?>
			<div>
				<?php echo apply_filters( 'the_content', $slide ); ?>
				<div class="arrows animate">
					<a class="arrow left"><i class="fa fa-chevron-left"></i></a>
					<a class="arrow right"><i class="fa fa-chevron-right"></i></a>
				</div>
			</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
	
	<?php if ( $prodoConfig['home-magic-mouse'] ) : ?>
	<div class="mouse hidden-xs">
		<div class="wheel"></div>
	</div>
	<?php endif; ?>
</section>