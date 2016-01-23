<?php global $prodoConfig; ?>
<?php $height = get_post_meta( get_the_ID( ), 'section-height', true ); ?>
<?php $height = ! empty( $height ) ? $height : '100%'; ?>

<section class="intro" id="intro" data-type="static-image" data-source="<?php echo esc_url( get_post_meta( get_the_ID( ), 'embed-image', true ) ); ?>" style="height: <?php echo esc_attr( $height ); ?>;">
	<div class="container">
		<div class="content">
			<div class="text-center">
				<div class="row">
					<div class="col-md-12">
						<?php echo apply_filters( 'the_content', get_post_meta( get_the_ID( ), 'content-embed', true ) ); ?>
						<div class="video-preview animate">
							<img src="<?php echo esc_url( get_post_meta( get_the_ID( ), 'embed-video-preview', true ) ); ?>" class="img-responsive" alt="">
							<div class="video-control" id="embed-video-control">
								<i class="fa fa-play"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="row hidden" id="embed-video">
					<div class="col-md-12">
						<header class="offsetBottomS">
							<div class="icon close animate">
								<i class="fa fa-times" style="font-size: 17px"></i>
								<strong><?php _e( 'Take me back', 'prodo' ); ?></strong>
							</div>
						</header>
						<div class="video-container">
							<div class="video-responsive">
								<div class="autocreate hidden" width="560" height="315" data-source="<?php echo esc_url( get_post_meta( get_the_ID( ), 'embed-video', true ) ); ?>"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $prodoConfig['home-magic-mouse'] ) : ?>
	<div class="mouse hidden-xs">
		<div class="wheel"></div>
	</div>
	<?php endif; ?>
</section>