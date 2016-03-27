<?php if( function_exists('ht_usefulness') ): ?>
    <?php
        $article_usefulness = ht_usefulness( get_the_ID() );
        $helpful_article = ( $article_usefulness >= 0 ) ? true : false;
        $helpful_article_class = ( $helpful_article ) ? 'hkb-meta__usefulness--good' : 'hkb-meta__usefulness--bad';
    ?>
    <li class="hkb-meta__usefulness <?php echo $helpful_article_class; ?>"><?php echo $article_usefulness  ?></li>
<?php endif; //end function exists ?>