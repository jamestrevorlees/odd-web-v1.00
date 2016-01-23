<?php get_header( ); ?>

<section class="section alt-background offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<header>
					<h2><?php ProdoTheme::pageTitle( ); ?></h2>
					<p class="info breadcrumbs"><?php dimox_breadcrumbs( ); ?></p>
				</header>
			</div>
		</div>
	</div>
</section>

<?php if ( class_exists( 'ProdoShortcodes' ) ) : ?>
	<section class="section offsetTop">
		<div class="container">
			<h2><?php _e( 'Favorite Projects', 'prodo' ); ?></h2>
			<?php echo ProdoShortcodes::portfolio( array( ) ); ?>
		</div>
	</section>
	<div>
		<div class="section offsetTop offsetBottomL" id="portfolio-details"></div>
	</div>
<?php else : ?>
	<section class="section offsetTopS offsetBottom">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php get_template_part( 'templates/no-content' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer( ); ?>