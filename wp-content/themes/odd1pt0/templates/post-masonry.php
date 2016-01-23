<?php if ( have_posts( ) ) : ?>
	<?php global $more, $isMasonry; $more = 0; $isMasonry = true; ?>
	<?php while ( have_posts( ) ) : the_post( ); ?>
		<article id="post-<?php the_ID( ); ?>" <?php post_class( 'blog-post masonry offsetTopS offsetBottom' ); ?>>
			<?php ProdoTheme::postContent( ); ?>
		</article>
	<?php endwhile; ?>
<?php else : ?>
	<?php get_template_part( 'templates/no-content' ); ?>
<?php endif; ?>