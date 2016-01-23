<?php global $content_width; ?>
<?php $title = get_the_title( ); ?>
<?php $metadata = wp_get_attachment_metadata( ); ?>
<?php $attachment_link = $metadata ? $metadata['width'] . ' &times; ' . $metadata['height'] : __( 'Download', 'prodo' ); ?>

<article id="post-<?php the_ID( ); ?>" <?php post_class( 'row blog-post offsetTopS offsetBottomS' . ( is_single( ) ? ' is-single' : '' ) ); ?>>
	<div class="col-md-12 col-sm-12">
		<header>
			<?php if ( ! empty( $title ) ) : ?>
			<h2><?php the_title( ); ?></h2>
			<?php endif; ?>
			<div class="info">
				<span><?php the_time( get_option( 'date_format' ) ); ?></span>
				<span><a href="<?php echo wp_get_attachment_url( ); ?>"><?php echo esc_html( $attachment_link ); ?></a></span>
				<?php edit_post_link( __( 'Edit', 'prodo' ), '<span>', '</span>' ); ?>
			</div>
		</header>
		<a href="<?php echo esc_url( ProdoTheme::nextAttachmentURL( $post ) ); ?>" title="<?php the_title_attribute( ); ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, array( $content_width, $content_width ) ); ?></a>

		<?php if ( ! empty( $post->post_excerpt ) ) : ?>
		<?php the_excerpt( ); ?>
		<?php endif; ?>
		
		<?php wp_link_pages( array( 'before' => '<div class="pages-navigation text-center"><span class="pages">' . __( 'Pages:', 'prodo' ) . '</span>', 'after' => '</div>', 'separator' => '&nbsp; ' ) ); ?>
	</div>
</article>

<?php if ( is_single( ) ) : ?>
<?php comments_template( ); ?>
<?php endif; ?>