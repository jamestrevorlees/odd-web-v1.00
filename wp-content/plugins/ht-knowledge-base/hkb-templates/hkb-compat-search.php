<?php
/*
*
* The compat template for displaying search results
*
*/
?>

<!-- #ht-kb -->
<div id="hkb" class="hkb-template-search">

<?php hkb_get_template_part('hkb-searchbox', 'search'); ?>

<?php hkb_get_template_part('hkb-breadcrumbs', 'single'); ?>

        <?php if ( have_posts() ) : ?>
          <?php while ( have_posts() ) : the_post(); ?>

            <?php hkb_get_template_part('hkb-content-article'); ?>

          <?php endwhile; ?>

          <?php posts_nav_link(); ?>

        <?php else : ?>

        <h2 class="hkb-search-title">
          <?php _e('No Results', 'ht-knowledge-base'); ?>
        </h2>

        <?php endif; ?>

</div>
<!-- /#ht-kb -->