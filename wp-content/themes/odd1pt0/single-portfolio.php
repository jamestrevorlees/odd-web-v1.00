<?php $isAJAX = ProdoTheme::isAJAX( ); ?>
<?php $subtitle = get_post_meta( get_the_ID( ), 'subtitle', true ); ?>

<?php if ( ! $isAJAX ) get_header( ); ?>

<?php if ( have_posts( ) ) : while ( have_posts( ) ) : the_post( ); ?>
<?php if ( ! $isAJAX ) : ?>
<section class="section alt-background offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<header>
					<h2 id="share-title"><?php the_title( ); ?></h2>
					<p class="info">
						<?php if ( ! empty( $subtitle ) ) : ?>
						<?php echo esc_html( $subtitle ); ?>
						<?php else : ?>
						<?php echo esc_html( ProdoTheme::portfolioCategories( get_the_ID( ), ' / ' ) ); ?>
						<?php endif; ?>
					</p>
				</header>
			</div>
		</div>
	</div>
</section>

<section class="section offsetTop offsetBottom">
<?php endif; ?>
	<div class="container">
		<?php if ( $isAJAX ) : ?>
		<div class="row">
			<div class="col-md-12">
				<header class="text-center offsetBottom">
					<div class="icon close"><i class="fa fa-times"></i></div>
					<h2 id="share-title"><?php the_title( ); ?></h2>
					<p class="info portfolio">
						<?php if ( ! empty( $subtitle ) ) : ?>
						<?php echo esc_html( $subtitle ); ?>
						<?php else : ?>
						<?php echo esc_html( ProdoTheme::portfolioCategories( get_the_ID( ), ' / ' ) ); ?>
						<?php endif; ?>
					</p>
				</header>
			</div>
		</div>
		<?php endif; ?>
		<div id="share-image" class="responsive-images">
			<?php the_content( ); ?>
		</div>
	</div>
<?php if ( ! $isAJAX ) : ?>
</section>
<?php endif; ?>
<?php endwhile; endif; ?>

<?php if ( ! $isAJAX ) get_footer( ); ?>