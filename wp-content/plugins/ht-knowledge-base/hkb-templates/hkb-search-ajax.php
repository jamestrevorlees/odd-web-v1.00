<?php
/*
*
* The template used for single page, for use by the theme
*
*/
?>

<ul id="hkb" class="hkb-searchresults">

  <?php $total_results = 0; ?>
    <!-- ht_kb -->
    <?php if (have_posts()) : ?>
          <?php $counter = 0; ?>
        <?php $total_results += (int) $wp_query->posts; ?>
          <?php while (have_posts() && $counter < 10) : the_post(); ?>
              <li class="hkb-searchresults__article <?php hkb_post_type_class(); ?> <?php hkb_post_format_class(); ?>">
                <a href="<?php the_permalink(); ?>">
                        <span class="hkb-searchresults__title"><?php the_title(); ?></span>
                        <?php if( hkb_show_search_excerpt() && function_exists('hkb_the_excerpt') ): ?>
                            <span class="hkb-searchresults__excerpt"><?php hkb_the_excerpt(); ?></span>
                        <?php endif; ?> 
                  <?php hkb_get_template_part( 'hkb-article-meta', 'search' ); ?>
                </a>
              </li>
              <?php $counter++; ?>
          <?php endwhile; ?>
    <?php endif; ?>

    <?php if($total_results>0): ?>
      <li class="hkb-searchresults__showall">
        <a href="<?php echo site_url( '?s=' . $s . '&ht-kb-search=1' ); ?>" title="<?php _e('Show all results', 'ht-knowledge-base'); ?>"><?php _e('Show all results', 'ht-knowledge-base'); ?></a> 
      </li>
    <?php else: ?>
      <li class="hkb-searchresults__noresults">
        <span><?php _e('No Results', 'ht-knowledge-base'); ?></span>
      </li>
    <?php endif; ?>

</ul>