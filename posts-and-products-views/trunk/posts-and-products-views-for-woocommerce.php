<?php
/**
 * Plugin Name: Posts and Products Views for WooCommerce
 * Plugin URI: https://www.scintelligencia.com/
 * Author: SCI Intelligencia
 * Description: Posts and Products Views for WooCommerce, Let the Admin know how many clicks they got on specific Post and WooCommerce Product.
 * Version: 1.3
 * Author: Syed Muhammad Usman
 * Author URI: https://www.linkedin.com/in/syed-muhammad-usman/
 * License: GPL v2 or later
 * Stable tag: 1.2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Tags: WC, posts, products, views, counter, track
 * @author Syed Muhammad Usman
 * @url https://www.fiverr.com/mr_ussi
 * @version 1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('PostsAndProductsViewsForWooCommerce') ) {
    class PostsAndProductsViewsForWooCommerce
    {
        /**
         * PostsAndProductsViewsForWooCommerce constructor.
         * @since 1.0
         * @version 1.0
         */
        public function __construct()
        {
            $this->run();
        }

        /**
         * Runs Plugins
         * @since 1.0
         * @version 1.0
         */
        public function run()
        {
            $this->constants();
            $this->includes();
            $this->add_actions();
            $this->register_hooks();
        }

        /**
         * @param $name Name of constant
         * @param $value Value of constant
         * @since 1.0
         * @version 1.0
         */
        public function define($name, $value)
        {
            if (!defined($name))
                define($name, $value);
        }

        /**
         * Defines Constants
         * @since 1.0
         * @version 1.0
         */
        public function constants()
        {
            $this->define('PAPVFWC_VERSION', '1.3');
            $this->define('PAPVFWC_PREFIX', 'papvfwc_');
            $this->define('PAPVFWC_TEXT_DOMAIN', 'papvfwc');
            $this->define('PAPVFWC_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
            $this->define('PAPVFWC_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
        }

        /**
         * Require File
         * @since 1.0
         * @version 1.0
         */
        public function file( $required_file ) {
            if ( file_exists( $required_file ) )
                require_once $required_file;
            else
                echo 'File Not Found';
        }

        /**
         * Include files
         * @since 1.0
         * @version 1.0
         */
        public function includes()
        {

        }

        /**
         * Enqueue Styles and Scripts
         * @since 1.0
         * @version 1.0
         */
        public function enqueue_scripts()
        {
            wp_enqueue_style(PAPVFWC_TEXT_DOMAIN . '-css', PAPVFWC_PLUGIN_DIR_URL . 'assets/css/style.css', '', PAPVFWC_VERSION);
            wp_enqueue_script(PAPVFWC_TEXT_DOMAIN . '-custom-js', PAPVFWC_PLUGIN_DIR_URL . 'assets/js/custom.js', '', PAPVFWC_VERSION);
        }

        /**
         * Adds Admin Page in Dashboard
         * @since 1.0
         * @version 1.0
         */
        public function add_menu()
        {

        }

        /**
         * Home page of Plugin
         * @since 1.0
         * @version 1.0
         */
        public function home()
        {

        }

        /**
         * Add Actions
         * @since 1.0
         * @version 1.0
         */
        public function add_actions()
        {
            add_action('init', [$this, 'enqueue_scripts']);
            add_action('admin_menu', [$this, 'add_menu']);
            add_filter( 'manage_posts_columns', array( $this, 'posts_column_views') );
            add_action( 'manage_posts_custom_column', array( $this, 'posts_custom_column_views') );
            add_filter('the_content', array( $this, 'counter' ), 10, 1);
            add_action('woocommerce_before_add_to_cart_form', array( $this, 'counter' ) );
            add_shortcode( 'papvfwc_views', array( $this, 'show_views' ) );
        }

        /**
         * Renders shortcode
         * @return string
         * @since 1.2
         * @version 1.0
         */
        public function show_views()
        {
            return $this->get_post_view();
        }

        /**
         * Counts Views
         * @since 1.0
         * @version 1.0
         */
        public function counter( $content )
        {
            $key = 'papvfwc_counter';
            $post_id = get_the_ID();
            $count = (int) get_post_meta( $post_id, $key, true );
            $count++;
            update_post_meta( $post_id, $key, $count );
            return $content;
        }

        /**
         * Get the Views
         * @return string
         */
        public function get_post_view()
        {
            $count = get_post_meta( get_the_ID(), 'papvfwc_counter', true );
            if ( $count > 0 )
                return "$count Views";
            else
                return 'No Views Yet';
        }

        /**
         * Add Views Column in Table
         * @param $columns
         * @return mixed
         * @since 1.0
         * @version 1.0
         */
        public function posts_column_views( $columns )
        {
            $columns['post_views'] = 'Views';
            return $columns;
        }

        /**
         * Add Counter Column in Table
         * @param $column
         * @since 1.0
         * @version 1.0
         */
        public function  posts_custom_column_views( $column )
        {
            if ( $column === 'post_views') {
                echo $this->get_post_view();
            }
        }

        /**
         * Register Activation, Deactivation and Uninstall Hooks
         * @since 1.0
         * @version 1.0
         */
        public function register_hooks()
        {
            register_activation_hook( __FILE__, [$this, 'activate'] );
            register_deactivation_hook( __FILE__, [$this, 'deactivate'] );
            register_uninstall_hook(__FILE__, 'papvfwc_function_to_run');
        }

        /**
         * Runs on Plugin's activation
         * @since 1.0
         * @version 1.0
         */
        public function activate()
        {

        }

        /**
         * Runs on Plugin's Deactivation
         * @since 1.0
         * @version 1.0
         */
        public function deactivate()
        {

        }
    }
}

new PostsAndProductsViewsForWooCommerce();
