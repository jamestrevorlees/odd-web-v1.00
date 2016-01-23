<?php
/**
 * Template Name: Page without Sidebars
 */
?>

<?php global $prodoConfig; ?>

<?php $subtitle = get_post_meta( get_the_ID( ), 'subtitle', true ); ?>
<?php get_header( ); ?>

<section class="section alt-background offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<header>
					<h2><?php ProdoTheme::pageTitle( ); ?></h2>
					<?php if ( ! empty( $subtitle ) ) : ?>
					<p class="info">
						<?php echo esc_html( $subtitle ); ?>
					</p>
					<?php else : ?>
						<?php if ( ! isset( $prodoConfig['breadcrumbs'] ) or $prodoConfig['breadcrumbs'] ) : ?>
						<p class="info breadcrumbs"><?php dimox_breadcrumbs( ); ?></p>
						<?php endif; ?>
					<?php endif; ?>
				</header>
			</div>
		</div>
	</div>
</section>

<section class="section offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<?php if ( have_posts( ) ) : while ( have_posts( ) ) : the_post( ); ?>
					<?php the_content( ); ?>
				<?php endwhile; endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer( ); ?>