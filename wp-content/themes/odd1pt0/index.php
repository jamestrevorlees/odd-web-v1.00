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
					<p class="info"><?php echo esc_html( $subtitle ); ?></p>
					<?php else : ?>
					<p class="info breadcrumbs"><?php dimox_breadcrumbs( ); ?></p>
					<?php endif; ?>
				</header>
			</div>
		</div>
	</div>
</section>

<section class="section offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<?php if ( $prodoConfig['layout-standard'] == 2 ) : ?>
				<div class="col-md-4 col-sm-4 sidebar-left">
					<?php get_sidebar( ); ?>
				</div>
				<div class="col-md-8 col-sm-8">
					<?php if ( have_posts( ) ) : while ( have_posts( ) ) : the_post( ); ?>
						<?php the_content( ); ?>
					<?php endwhile; endif; ?>
				</div>
			<?php elseif ( $prodoConfig['layout-standard'] == 3 ) : ?>
				<div class="col-md-8 col-sm-8">
					<?php if ( have_posts( ) ) : while ( have_posts( ) ) : the_post( ); ?>
						<?php the_content( ); ?>
					<?php endwhile; endif; ?>
				</div>
				<div class="col-md-4 col-sm-4">
					<?php get_sidebar( ); ?>
				</div>
			<?php else : ?>
				<div class="col-md-12 col-sm-12">
					<?php if ( have_posts( ) ) : while ( have_posts( ) ) : the_post( ); ?>
						<?php the_content( ); ?>
					<?php endwhile; endif; ?>
				</div>
				<?php if ( comments_open( ) and is_singular( ) ) : ?>
					<div class="col-md-12 col-sm-12 offsetTopS">
						<div class="offsetTopS"></div>
						<?php comments_template( ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer( ); ?>