<?php

if ( !defined( 'ABSPATH' ) )
    exit;

$active = false;

$snippets_meta[ __FILE__ ] = [
    'name'   => 'Snippet Template',
    'desc'   => 'Copy the code from this template into your snippets to display them properly in the backend.',
    'active' => $active,
];

if ( !$active )
    return;

/**
 * Insert your snippet here.
 */

