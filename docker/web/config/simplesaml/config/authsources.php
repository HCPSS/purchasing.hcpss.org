<?php

$config = array(
    // This is a authentication source which handles admin authentication.
    'admin' => array(
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ),

    'default-sp' => [
    'saml:SP',
		'entityID' => 'purchasing-dev',
		'idp' => 'https://hcpss.me/saml/saml2/idp/metadata.php',
		'discoURL' => NULL,
    ],
);
