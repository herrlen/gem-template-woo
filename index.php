<?php
/**
 * Haupt-Template Datei
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    if ( have_posts() ) :

        /* Start the Loop */
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                </header>

                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>
            </article>

            <?php
        endwhile;

        the_posts_navigation();

    else :

        echo '<p>Keine Inhalte gefunden.</p>';

    endif;
    ?>

</main><?php
get_footer();