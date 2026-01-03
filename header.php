<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
    
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    
   <header id="masthead" class="site-header">
    <div class="container header-container">
        
        <div class="site-branding">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            } else {
                ?>
                <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php
            }
            ?>
        </div>

       <nav id="site-navigation" class="main-navigation">
    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
        <span class="hamburger-icon">☰</span>
        <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'lensuh' ); ?></span>
    </button>

    <?php
    wp_nav_menu( array(
        'theme_location' => 'menu-1',
        'menu_id'        => 'primary-menu',
        'container'      => 'div', 
        'container_class'=> 'menu-container', // Wrapper für Animation
    ) );
    ?>
</nav>

        <div class="header-actions">
            <?php lensuh_woocommerce_cart_link(); ?>
        </div>

    </div>
</header>

    <div id="content" class="site-content container">