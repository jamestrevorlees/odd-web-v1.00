<?php
/*
*
* The template for displaying heroic knowledgebase category archive content
*
*/
?>

<!-- #ht-kb -->
<div id="hkb" class="hkb-template-category">

    <?php hkb_get_template_part('hkb-searchbox', 'single'); ?>

    <?php hkb_get_template_part('hkb-breadcrumbs', 'taxonomy'); ?>

	<?php hkb_get_template_part('hkb-subcategories'); ?>

    <?php if ( have_posts() ) : ?>
    
        <?php while ( have_posts() ) : the_post(); ?>

    		<?php hkb_get_template_part('hkb-content-article'); ?>
        
        <?php endwhile; ?>

        <?php posts_nav_link(); ?>
        
    <?php else : ?>

        <h2><?php _e('Nothing else in this category.', 'ht-knowledge-base'); ?></h2>
        
    <?php endif; ?>

</div>
<!-- /#ht-kb -->