<?php 
/*
*
* Author single template
*
*/ 
?>

<?php if ( function_exists('get_the_author_meta') && get_the_author_meta('description') != '' ): ?>
    <section class="hkb-article-author">
        <?php if ( !is_author() ): ?>
            <h3 id="hkb-article-author__title">
            <?php _e('About The Author', 'framework') ?>
            </h3>
        <?php endif; ?>
        <div class="hkb-article-author__avatar">
            <?php if(function_exists('get_avatar')) : ?> 
            <?php echo get_avatar( get_the_author_meta('email'), '70' ); ?>
            <?php endif; ?>
        </div>
        <h4 class="hkb-article-author__name">
            <a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                <?php echo get_the_author(); ?>
            </a>
        </h4>
        <?php //display_is_most_helpful_user(get_the_author_meta('ID')); ?>
        <div class="hkb-article-author__bio">
            <?php the_author_meta('description') ?>
        </div>
    </section>
<?php endif; ?>