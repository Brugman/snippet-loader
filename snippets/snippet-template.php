<?php

if ( !defined( 'ABSPATH' ) )
    exit;

$snippets_meta[ __FILE__ ] = [
    'name'   => 'Snippet Template',
    'desc'   => 'Copy the code from this template into your snippets to display them properly in the backend.',
    'active' => false,
];

if ( !$snippets_meta[ __FILE__ ]['active'] )
    return;

/**
 * Insert your snippet here.
 */

