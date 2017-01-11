<?php

/**
 * Template Name: Member Profile
 * Template Post Type: page
 */
get_header();
?>
<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('content', 'member-profile'); ?>
    <?php comments_template('', true); ?>
<?php endwhile; // end of the loop.      ?>

<?php get_footer(); ?>