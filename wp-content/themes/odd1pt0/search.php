<?php global $prodoConfig; ?>
<?php get_header( ); ?>

<section class="section alt-background offsetTop offsetBottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<header>
					<h2><?php _e( 'Search Results', 'prodo' ); ?></h2>
					<p class="info breadcrumbs"><?php dimox_breadcrumbs( ); ?></p>
				</header>
			</div>
		</div>
	</div>
</section>

<section class="section offsetTopS offsetBottom">
	<div class="container">
		<div class="row">
			<?php if ( $prodoConfig['layout-search'] == 2 ) : ?>
				<div class="col-md-4 col-sm-4 sidebar-left">
					<?php get_sidebar( ); ?>
				</div>
				<div class="col-md-8 col-sm-8">
					<?php get_template_part( 'templates/post' ); ?>
				</div>
			<?php elseif ( $prodoConfig['layout-search'] == 3 ) : ?>
				<div class="col-md-8 col-sm-8">
					<?php get_template_part( 'templates/post' ); ?>
				</div>
				<div class="col-md-4 col-sm-4">
					<?php get_sidebar( ); ?>
				</div>
			<?php else : ?>
				<div class="col-md-12 col-sm-12">
					<?php get_template_part( 'templates/post' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer( ); ?>