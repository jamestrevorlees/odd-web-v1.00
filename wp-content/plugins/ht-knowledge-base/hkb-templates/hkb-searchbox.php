<?php
/*
*
* The template used for displaying the search box
*
*/
?>

<?php if(hkb_show_knowledgebase_search()): ?>
    <?php /* important - load live search scripts */ ht_knowledge_base_activate_live_search(); ?>
    <form class="hkb-site-search" method="get" action="<?php echo home_url( '/' ); ?>">
        <label class="hkb-screen-reader-text" for="s"><?php _e( 'Search For', 'ht-knowledge-base' ); ?></label>
        <input class="hkb-site-search__field" type="text" value="<?php echo get_search_query(); ?>" placeholder="<?php echo hkb_get_knowledgebase_searchbox_placeholder_text(); ?>" name="s" autocomplete="off">
        <input type="hidden" name="ht-kb-search" value="1" />
        <button class="hkb-site-search__button" type="submit"><span><?php _e( 'Search', 'ht-knowledge-base' ); ?></span></button>
    </form>
<?php endif; ?>