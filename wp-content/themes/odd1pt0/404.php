<?php get_header( '404' ); ?>

<section class="section" id="error-page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<header class="text-center offsetBottomS">
					<div class="icon largest colored offsetBottomS"><i class="fa fa-chain-broken"></i></div>
					<h1 class="offsetTopS"><?php _e( 'Something has gone wrong!', 'prodo' ); ?></h1>
					<p class="info">
						<?php _e( 'The page you are trying to reach doesn\'t seem to exist.', 'prodo' ); ?>
					</p>
				</header>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<a href="<?php echo esc_url( site_url( ) ); ?>" class="btn btn-default"><?php _e( 'Take me back', 'prodo' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php get_footer( '404' ); ?>