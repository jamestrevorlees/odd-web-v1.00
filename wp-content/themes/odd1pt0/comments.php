<?php global $prodoConfig; ?>
<?php if ( post_password_required( ) ) return; ?>

<?php if ( have_comments( ) ) : ?>
	<hr>
	<div class="comments" id="comments">
		<div class="row offsetTopS offsetBottomS">
			<div class="col-md-12">
				<header class="offsetTopS offsetBottomS">
					<h3><?php comments_number( ); ?></h3>
				</header>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 clearfix">
				<?php wp_list_comments( array( 'callback' => array( 'ProdoTheme', 'comment' ), 'style' => 'div' ) ); ?>
			</div>
		</div>
		<?php if ( get_comment_pages_count( ) > 1 && get_option( 'page_comments' ) ) : ?>
		<div class="row offsetTopS">
			<div class="col-md-12 pages-navigation offsetTopS">
				<?php previous_comments_link( __( '&lsaquo;&nbsp; Older comments', 'prodo' ) ); ?>
				<?php next_comments_link( __( 'Newer comments &rsaquo;&nbsp;', 'prodo' ) ); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="offsetTop"></div>
<?php endif; ?>

<?php if ( comments_open( ) or ( ! comments_open( ) && get_comments_number( ) ) ) : ?>
	<hr>
<?php endif; ?>

<div class="offsetTopS"></div>

<?php if ( ! comments_open( ) && get_comments_number( ) ) : ?>
	<div class="offsetTopS">
		<p><?php _e( 'Comments are closed.', 'prodo' ); ?></p>
	</div>
<?php endif; ?>

<?php
$commenter = wp_get_current_commenter( );
$required = ( get_option( 'require_name_email' ) ? " aria-required='true'" : '' );

comment_form( array(
	'fields' => array(
		'author' => '
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="field">
						<input type="text" id="author" name="author" class="field-name" placeholder="' . __( 'Name', 'prodo' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $required . '>
					</div>
				</div>',
		'email' => '
				<div class="col-md-6 col-sm-6">
					<div class="field">
						<input type="email" id="email" name="email" class="field-email" placeholder="' . __( 'Email', 'prodo' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) .
		'"' . $required . '>
					</div>
				</div>
			</div>',
		'url' => '
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="field">
						<input type="text" id="url" name="url" class="field-url" placeholder="' . __( 'Website', 'prodo' ) . '" value="' . esc_attr(  $commenter['comment_author_url'] ) . '">
					</div>
				</div>
			</div>'
	),
	'comment_field' => '
		<div class="row">
			<div class="col-md-12">
				<div class="field">
					<textarea id="comment" name="comment" class="field-comment" placeholder="' . __( 'Comment', 'prodo' ) . '" aria-required="true"></textarea>
				</div>
			</div>
		</div>',
	'comment_notes_before' => '
		<div class="offsetTopS offsetBottomS">
			<p class="comment-notes">' . __( 'Your email address will not be published.', 'prodo' ) . '</p>
		</div>',
	'comment_notes_after' => '',
	'logged_in_as' => '
		<div class="offsetTopS offsetBottomS">
			<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'prodo' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>
		</div>',
	'must_log_in' => '
		<div class="offsetTopS offsetBottomS">
			<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'prodo' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>
		</div>'
) );
?>