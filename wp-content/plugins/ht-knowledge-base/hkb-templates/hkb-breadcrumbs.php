<?php 
/*
*
* Breadcrumbs template
*
*/ 
?>


<?php if(hkb_show_knowledgebase_breadcrumbs()): ?>
<!-- .hkb-breadcrumbs -->
<?php $breadcrumbs_paths = ht_kb_get_ancestors(); ?>
    <?php foreach ($breadcrumbs_paths as $index => $paths): ?>
        <ol class="hkb-breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php $last_item_index = count($paths)-1; ?>
            <?php foreach ($paths as $key => $component): ?>
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <?php if($key==$last_item_index): ?>
                        <span itemprop="item" href="<?php echo $component['link']; ?>" title="<?php echo $component['title']; ?>">
                            <span itemprop="name"><?php echo $component['label']; ?></span>
                        </span> 
                    <?php else: ?>
                        <a itemprop="item" href="<?php echo $component['link']; ?>" title="<?php echo $component['title']; ?>">
                            <span itemprop="name"><?php echo $component['label']; ?></span>
                        </a>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?php echo $key+1; ?>" />
                </li>               
            <?php endforeach; ?>
        </ol>
    <?php endforeach; ?>
<!-- /.hkb-breadcrumbs -->
<?php endif; ?>