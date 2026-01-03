<?php
/**
 * Der "Catch-All" Template für WooCommerce.
 * Sorgt dafür, dass Shop-Seiten genau wie der Rest der Seite aussehen.
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

        <?php
        // Das ist die Magie: Statt dem Loop rufen wir den Shop-Inhalt auf.
        woocommerce_content();
        ?>

    </div>
</main>

<?php
get_footer();