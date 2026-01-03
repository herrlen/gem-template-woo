<?php
/**
 * Template für alle statischen Seiten (Warenkorb, Kasse, Impressum, Kontakt)
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

        <?php
        while ( have_posts() ) :
            the_post();

            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    // Falls die Seite paginiert ist (selten, aber gut zu haben)
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lensuh' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

            </article>
            <?php

        endwhile; // End of the loop.
        ?>

    </div>
</main>

<?php
get_footer();