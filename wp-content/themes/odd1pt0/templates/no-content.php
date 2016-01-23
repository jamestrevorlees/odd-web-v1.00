<header class="offsetBottomS">
	<h2><?php _e( 'Nothing Found', 'prodo' ); ?></h2>
</header>

<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'prodo' ); ?></p>

<div class="offsetTopS nothing-found">
	<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="search-field" placeholder="<?php _e( 'Search &hellip;', 'prodo' ); ?>" value="" name="s" title="<?php _e( 'Search for:', 'prodo' ); ?>" />
		<input type="submit" class="search-submit" value="<?php _e( 'Search', 'prodo' ); ?>" />
	</form>
</div>