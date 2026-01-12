<?php

$whmcsRoot = realpath(__DIR__ . '/../../../');

return [
    'paths' => [
        'root'  => $whmcsRoot,
        'admin' => $whmcsRoot . '/admin/lang',
    ],

    'output' => [
        'admin' => $whmcsRoot . '/admin/lang/overrides',
    ],

    'translator' => [
        'source_lang' => 'EN',
        'target_lang' => 'UK',
    ],

    'deepl' => [
        // Free:
        // 'endpoint' => 'https://api-free.deepl.com/v2/translate',
        // Pro: https://api.deepl.com/v2/translate
        'endpoint' => 'https://api.deepl.com/v2/translate',

        'api_key' => 'YOUR_DEEPL_API_KEY',

    ],

    'options' => [
        'dry_run' => false,
    ],
];
