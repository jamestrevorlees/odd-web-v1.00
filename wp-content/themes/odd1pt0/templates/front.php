<?php
/**
 * Template Name: Front Page
 */
?>

<?php get_header( ); ?>

<?php if ( ProdoTheme::frontPage( get_the_ID( ) ) ) : ?>
<?php echo "\n" . ProdoTheme::frontSections( ); ?>
<?php endif; ?>

<?php get_footer( ); ?>