<?php
/*
Plugin Name: WooCommerce Simple Product Badge
Version: 0.1.0
Description: Display custom badge on WooCommerce products
Author: Pragmatic Mates s.r.o.
Author URI: http://pragmaticmates.com
Text Domain: woocommerce-simple-product-badge
Domain Path: /languages/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    /**
     * New badge fields in admin
     */
    add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_simple_product_badge_fields' );
    function woocommerce_simple_product_badge_fields() {
        global $woocommerce, $post;

        echo '<div class="options_group">';

        woocommerce_wp_text_input(array(
            'id'          => '_woocommerce_simple_product_badge_title',
            'label'       => __( 'Badge Title', 'woocommerce-simple-product-badge' ),
            'description' => __( 'e.g. Recommended', 'woocommerce-simple-product-badge' ),
        ) );

        woocommerce_wp_text_input(array(
            'id'          => '_woocommerce_simple_product_badge_class',
            'label'       => __( 'Badge Class', 'woocommerce-simple-product-badge' ),
            'description' => __( 'e.g. background-green', 'woocommerce-simple-product-badge' ),
        ) );

        echo '</div>';
    }

    /**
     * Save custom fields values
     */
    add_action( 'woocommerce_process_product_meta', 'woocommerce_simple_product_badge_fields_save' );
    function woocommerce_simple_product_badge_fields_save( $post_id ) {
        $title = $_POST['_woocommerce_simple_product_badge_title'];
        $class = $_POST['_woocommerce_simple_product_badge_class'];

        if ( !empty( $title ) ) {
            update_post_meta( $post_id, '_woocommerce_simple_product_badge_title', esc_attr( $title ) );
        }

        if ( !empty( $class ) ) {
            update_post_meta( $post_id, '_woocommerce_simple_product_badge_class', esc_attr( $class ) );
        }
    }

    /**
     * Display product badge
     */
    add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_simple_product_badge_display', 30 );
    function woocommerce_simple_product_badge_display() {
        $title = get_post_meta( get_the_ID(), '_woocommerce_simple_product_badge_title', true );
        $class = get_post_meta( get_the_ID(), '_woocommerce_simple_product_badge_class', true );

        if ( !empty( $title ) ) {
            $class = !empty( $class ) ? $class : '';
            echo '<span class="wc-simple-product-badge ' . $class . '">' . $title . '</span>';
        }
    }
}
