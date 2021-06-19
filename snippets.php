<?php

/**
 * Plugin Name: Snippets
 * Description:
 * Version: 0.1.0
 * Plugin URI: https://timbr.dev
 * Author: Tim Brugman
 * Author URI: https://timbr.dev
 * Text Domain: snippets
 */

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Functions.
 */

function prefix_textdomain()
{
    return 'snippets';
}

function get_snippets()
{
    $snippets = glob( __DIR__.DIRECTORY_SEPARATOR.'snippets'.DIRECTORY_SEPARATOR.'*.php' );

    foreach ( $snippets as $k => $snippet )
        if ( basename( $snippet ) == 'index.php' )
            unset( $snippets[ $k ] );

    return $snippets;
}

function prefix_controller()
{
    prefix_page_foo();
}

function prefix_page_foo()
{
    global $snippets_meta;

    $snippet_files = get_snippets();

    $table = [];
    foreach ( $snippet_files as $snippet_file )
    {
        $output_active = '<span class="dashicons dashicons-no" style="color: red;" title="Inactive"></span>';
        if ( $snippets_meta[ $snippet_file ]['active'] ?? false )
            $output_active = '<span class="dashicons dashicons-yes" style="color: green;" title="Active"></span>';

        $output_name = '';
        $output_name .= '<span style="white-space: nowrap;">';
        $output_name .= $snippets_meta[ $snippet_file ]['name'] ?? '';
        $output_name .= '</span>';

        $table[] = [
            $output_active,
            $output_name,
            $snippets_meta[ $snippet_file ]['desc'] ?? '',
        ];
    }
?>
<div class="wrap prefix-wrapper">

    <h1><?php _e( 'Snippets', prefix_textdomain() ); ?></h1>



<?php if ( !empty( $table ) ): ?>
    <table class="wp-list-table widefat fixed striped" style="margin-top: 16px; width: auto;">
        <thead>
            <tr>
                <td></td>
                <td><?php _e( 'Name', prefix_textdomain() ); ?></td>
                <td><?php _e( 'Description', prefix_textdomain() ); ?></td>
            </tr>
        </thead>
        <tbody>
<?php foreach ( $table as $row ): ?>
            <tr>
<?php foreach ( $row as $cell ): ?>
                <td><?=$cell;?></td>
<?php endforeach; // $row ?>
            </tr>
<?php endforeach; // $table ?>
        </tbody>
    </table>
<?php else: // $table is empty ?>
    <p>No snippets found.</p>
<?php endif; // $table ?>



</div><!-- wrap -->
<?php
}

/**
 * Hooks.
 */

add_action( 'admin_menu', function () {
    add_options_page(
        'Snippets', // page title
        'Snippets', // menu title
        'manage_options', // capability
        'prefix-foo', // menu slug
        'prefix_controller', // function
        null // position
    );
});

/**
 * Snippet loader.
 */

$snippets_meta = [];

foreach ( get_snippets() as $snippet )
    include $snippet;

