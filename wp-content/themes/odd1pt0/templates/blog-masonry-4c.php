<?php
/**
 * Template Name: Blog (Masonry) with 4 Columns
 */
?>

<?php
if ( is_front_page( ) ) $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
else $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>

<?php $subtitle = get_post_meta( get_the_ID( ), 'subtitle', true ); ?>
<?php get_header( ); ?>

<section class="section alt-background offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<header class="text-center">
					<h2 id="share-title"><?php the_title( ); ?></h2>
					<?php if ( ! empty( $subtitle ) ) : ?>
					<p class="info">
						<?php echo esc_html( $subtitle ); ?>
					</p>
					<?php else : ?>
					<p class="info breadcrumbs"><?php dimox_breadcrumbs( ); ?></p>
					<?php endif; ?>
				</header>
			</div>
		</div>
	</div>
</section>

<section class="section offsetTopS offsetBottom">
	<div class="container">
		<div class="row">
			<?php query_posts( 'post_type=post&posts_per_page=' . get_option( 'posts_per_page' ) . '&paged=' . $paged ); ?>
			<?php if ( have_posts( ) ) : ?>
			<div class="col-md-12 col-sm-12 blog-masonry blog-masonry-four">
			<?php else : ?>
			<div class="col-md-12 col-sm-12">
			<?php endif; ?>
				<?php get_template_part( 'templates/post', 'masonry' ); ?>
			</div>
		</div>
		<?php ProdoTheme::navContent( 'masonry' ); ?>
	</div>
</section>

<?php get_footer( ); ?>