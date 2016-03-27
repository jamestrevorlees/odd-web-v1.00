 <?php if( hkb_show_usefulness_display()  ||  hkb_show_viewcount_display() || hkb_show_comments_display() ): ?>
    <!-- .hkb-meta -->
    <ul class="hkb-meta">

        <?php if( hkb_show_usefulness_display() ): ?>
            <?php hkb_get_template_part( 'hkb-article-meta-usefulness' ); ?>
        <?php endif; ?>
        <?php if( hkb_show_viewcount_display() ): ?>
            <?php hkb_get_template_part( 'hkb-article-meta-views' ); ?>
        <?php endif; ?>
        <?php if( hkb_show_comments_display() ): ?>
            <?php hkb_get_template_part( 'hkb-article-meta-comments' ); ?>
        <?php endif; ?>
        
    </ul>
    <!-- /.hkb-meta -->
<?php endif; ?>