<?php

if ( !defined( 'ABSPATH' ) )
    exit;

$snippet_loader->add_snippet_meta( __FILE__, [
    'name'   => 'Snippet Template',
    'desc'   => 'Copy the code from this template into your snippets to display them properly in the backend.',
    'active' => ( $active = false ),
]);

if ( !$active )
    return;

/**
 * Insert your snippet here.
 */

