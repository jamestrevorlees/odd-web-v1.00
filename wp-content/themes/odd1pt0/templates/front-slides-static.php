<?php global $prodoConfig; ?>
<?php $height = get_post_meta( get_the_ID( ), 'section-height', true ); ?>
<?php $height = ! empty( $height ) ? $height : '100%'; ?>

<section class="intro" id="intro" data-type="slideshow" data-images=".images-list" data-delay="<?php echo ( $prodoConfig['home-slideshow-timeout'] >= 5 ? intval( $prodoConfig['home-slideshow-timeout'] ) : 5 ); ?>" style="height: <?php echo esc_attr( $height ); ?>;">
	<div class="images-list">
		<?php echo ProdoTheme::slideshowImages( '<img src="%s" alt="">', get_post_meta( get_the_ID( ), 'slideshow-alt-images', true ) ); ?>
	</div>
	<div class="container">
		<div class="content">
			<?php echo apply_filters( 'the_content', get_post_meta( get_the_ID( ), 'content-slideshow-alt', true ) ); ?>
		</div>
	</div>
	
	<?php if ( $prodoConfig['home-magic-mouse'] ) : ?>
	<div class="mouse hidden-xs">
		<div class="wheel"></div>
	</div>
	<?php endif; ?>
</section>