<?php $subcategories = hkb_get_subcategories(); ?>
<?php if ( $subcategories && ( hkb_archive_display_subcategories() || is_tax('ht_kb_category') ) ): ?>

    <!--.hkb-subcats-->
    <ul class="hkb-subcats">
        <?php foreach ($subcategories as $term): ?>
            <li class="hkb-subcats__cat">
                
                <a class="hkb-subcats__cat-title" href="<?php echo esc_attr(get_term_link($term, 'ht_kb_category')); ?>" title="<?php echo sprintf( __( 'View all posts in %s', 'ht-knowledge-base' ), $term->name ); ?>" ><?php echo $term->name; ?></a>
                
                <?php if(hkb_archive_display_subcategory_count()): ?>
                    <span class="hkb-subcats__cat-count"><?php echo sprintf( _n( '1 Article', '%s Articles', $term->count, 'ht-knowledge-base' ), $term->count ); ?></span>
                <?php endif; ?>
                

                <?php
                    hkb_set_current_term_id($term->term_id);
                    //recursive
                    hkb_get_template_part('hkb-subcategories', 'archive');
                ?>

                <?php if(hkb_archive_display_subcategory_articles()): ?>            

                    <?php $sub_cat_posts = hkb_get_archive_articles($term); ?>
                    <?php if ($sub_cat_posts) : ?>

                    <ul class="hkb-article-list">
                        <?php foreach( $sub_cat_posts as $post ) : ?>                                
                            <li class="hkb-article-list__<?php hkb_post_format_class($post->ID); ?>">
                                <a href="<?php echo get_permalink($post->ID); ?>" rel="bookmark">
                                    <?php echo get_the_title($post->ID); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul><!-- and article list -->

                <?php endif; //end if display_sub_cat_articles ?>

            <?php endif; // end if $sub_cat_posts ?>
            </li> <!--  /.ht-kb-sub-cat -->

        <?php endforeach; ?>
    </ul>
    <!--/.hkb-subcats-->
<?php endif; ?>