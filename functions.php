<?php
/**
 * Theme Setup & Assets laden
 */

// 1. Sicherheit: Nicht direkt aufrufen
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 2. CSS und JS laden (Enqueuing)
function lensuh_scripts() {
    // Stylesheet laden (das von Gulp generierte)
    wp_enqueue_style( 
        'lensuh-style', 
        get_template_directory_uri() . '/assets/css/style.min.css', 
        array(), 
        filemtime( get_template_directory() . '/assets/css/style.min.css' ), // Cache-Busting
        'all' 
    );

    // JavaScript laden
    wp_enqueue_script( 
        'lensuh-script', 
        get_template_directory_uri() . '/assets/js/main.min.js', 
        array(), // Keine Abhängigkeiten (jQuery nur wenn nötig)
        filemtime( get_template_directory() . '/assets/js/main.min.js' ), 
        true // Im Footer laden (gut für Performance!)
    );
}
add_action( 'wp_enqueue_scripts', 'lensuh_scripts' );

// 3. Theme Support aktivieren (Pflicht für ThemeForest)
function lensuh_config() {
    add_theme_support( 'title-tag' ); // SEO Title
    add_theme_support( 'post-thumbnails' ); // Bilder
    add_theme_support( 'woocommerce' ); // Shop Support
}
add_action( 'after_setup_theme', 'lensuh_config' );
// Menü-Positionen registrieren
function lensuh_register_menus() {
    register_nav_menus( array(
        'menu-1' => esc_html__( 'Hauptmenü', 'lensuh' ),
    ) );
}
add_action( 'after_setup_theme', 'lensuh_register_menus' );

/**
 * Hilfsfunktion: Warenkorb-Icon mit Anzahl
 * Zeigt "Cart (3)" oder ein Icon an.
 */
function lensuh_woocommerce_cart_link() {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    ?>
    <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Warenkorb ansehen', 'lensuh' ); ?>">
        <span class="cart-icon">🛒</span>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <span class="cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
    </a>
    <?php
}