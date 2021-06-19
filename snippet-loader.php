<?php

/**
 * Plugin Name: Snippet Loader
 * Description: Loads snippets.
 * Version: 0.2.0
 * Plugin URI: https://timbr.dev
 * Author: Tim Brugman
 * Author URI: https://timbr.dev
 * Text Domain: snippet-loader
 */

if ( !defined( 'ABSPATH' ) )
    exit;

if ( !class_exists( 'SnippetLoader' ) )
{
    class SnippetLoader
    {
        private $snippets_meta = [];

        private $snippets_paths;

        /**
         * Constructor.
         */

        public function __construct()
        {
            $this->snippets_paths = $this->set_snippet_paths();
        }

        /**
         * Setters.
         */

        private function set_snippet_paths()
        {
            $snippets = glob( __DIR__.DIRECTORY_SEPARATOR.'snippets'.DIRECTORY_SEPARATOR.'*.php' );

            foreach ( $snippets as $k => $path )
                if ( basename( $path ) == 'index.php' )
                    unset( $snippets[ $k ] );

            return $snippets;
        }

        public function add_snippet_meta( $file, $meta )
        {
            $this->snippets_meta[ $file ] = $meta;
        }

        /**
         * Getters.
         */

        public function get_snippet_paths()
        {
            return $this->snippets_paths;
        }

        /**
         * Page Helpers.
         */

        private function page_header()
        {
?>
<style>
.snippet-loader-wrapper {}
.snippet-loader-wrapper table { margin-top: 16px; width: auto; }
.snippet-loader-wrapper .dashicons-yes { color: green; }
.snippet-loader-wrapper .dashicons-no { color: red; }
.snippet-loader-wrapper td:nth-child(2) { white-space: nowrap; }
</style>
<div class="wrap snippet-loader-wrapper">
<?php
        }

        private function page_footer()
        {
?>
</div><!-- wrap -->
<?php
        }

        private function build_table_data()
        {
            $table_data = [];

            foreach ( $this->snippets_paths as $snippet_file )
            {
                $output_active = '<span class="dashicons dashicons-no" title="Inactive"></span>';
                if ( $this->snippets_meta[ $snippet_file ]['active'] ?? false )
                    $output_active = '<span class="dashicons dashicons-yes" title="Active"></span>';

                $table_data[] = [
                    $output_active,
                    $this->snippets_meta[ $snippet_file ]['name'] ?? '',
                    $this->snippets_meta[ $snippet_file ]['desc'] ?? '',
                ];
            }

            return $table_data;
        }

        private function display_snippets_table()
        {
            $table_data = $this->build_table_data();
?>

<?php if ( !empty( $table_data ) ): ?>
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <td></td>
            <td>Name</td>
            <td>Description</td>
        </tr>
    </thead>
    <tbody>
<?php foreach ( $table_data as $row ): ?>
        <tr>
<?php foreach ( $row as $cell ): ?>
            <td><?=$cell;?></td>
<?php endforeach; // $row ?>
        </tr>
<?php endforeach; // $table_data ?>
    </tbody>
</table>
<?php else: // $table_data is empty ?>
<p>No snippets found.</p>
<?php endif; // $table_data ?>

<?php
        }

        /**
         * Pages.
         */

        public function page_controller()
        {
            $this->page_main();
        }

        private function page_main()
        {
            $this->page_header();

            echo '<h1>Snippets</h1>';

            $this->display_snippets_table();

            $this->page_footer();
        }

        /**
         * Hooks.
         */

        public function hook_register_settings_page()
        {
            add_options_page(
                'Snippets', // page title
                'Snippets', // menu title
                'manage_options', // capability
                'snippet-loader', // menu slug
                [ $this, 'page_controller' ], // function
                null // position
            );
        }

        /**
         * Register Hooks.
         */

        public function register_hooks()
        {
            // register settings page
            add_action( 'admin_menu', [ $this, 'hook_register_settings_page' ] );
        }
    }

    /**
     * Instantiate.
     */

    $snippet_loader = new SnippetLoader();
    $snippet_loader->register_hooks();

    /**
     * Load snippets.
     */

    foreach ( $snippet_loader->get_snippet_paths() as $snippet )
        include $snippet;
}

