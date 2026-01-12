#!/usr/bin/env php
<?php

require __DIR__ . '/../src/autoload.php';

use HostingInUA\WhmcsTranslator\Core\Context;
use HostingInUA\WhmcsTranslator\Core\Translator;
use HostingInUA\WhmcsTranslator\Scanner\AdminScanner;
use HostingInUA\WhmcsTranslator\Writer\AdminWriter;

if ($argc < 3) {
    echo "Usage: php translate.php admin <LANG> [--only=namespace] [--limit=N] [--dry-run]\n";
    exit(1);
}

$type = $argv[1];                // admin
$lang = strtoupper($argv[2]);    // UK

$limit  = null;
$only   = null;
$dryRun = false;

// CLI args
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--limit=')) {
        $limit = (int)substr($arg, 8);
    }

    if (str_starts_with($arg, '--only=')) {
        $only = trim(substr($arg, 7));
    }

    if ($arg === '--dry-run') {
        $dryRun = true;
    }
}

// Load config
$config = require __DIR__ . '/../config/translator.php';

// Override config from CLI
if ($limit !== null) {
    $config['options']['limit'] = $limit;
}

if ($only !== null) {
    $config['options']['only'] = $only;
}

if ($dryRun) {
    $config['options']['dry_run'] = true;
}

// Context
$context = new Context($type, $lang, $config);

// Scan
$scanner = new AdminScanner($context);
$strings = $scanner->scan();

// Translate
$translator = new Translator($context);
$translated = $translator->translate($strings);

// Write
$writer = new AdminWriter($context);
$writer->write($translated);

echo "Done. Translated " . count($translated) . " strings.\n";
