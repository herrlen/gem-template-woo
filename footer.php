</div><footer id="colophon" class="site-footer">
        <div class="container">
            <div class="site-info">
                <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'lensuh' ) ); ?>">
                    <?php
                    /* translators: %s: CMS Name, i.e. WordPress. */
                    printf( esc_html__( 'Proudly powered by %s', 'lensuh' ), 'WordPress' );
                    ?>
                </a>
                <span class="sep"> | </span>
                <?php
                    /* translators: 1: Theme name, 2: Theme author. */
                    printf( esc_html__( 'Theme: %1$s by %2$s.', 'lensuh' ), 'Lensuh', 'Dein Name' );
                ?>
            </div></div>
    </footer></div><?php wp_footer(); ?>

</body>
</html>