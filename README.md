# WHMCS Admin Translator

CLI tool for translating WHMCS admin language files using DeepL.

## Features
- Admin area translation
- Safe overrides (no core file changes)
- Supports DeepL Free / Pro
- CLI options: --only, --limit, --dry-run

## Requirements
- PHP 8.1+
- WHMCS 8.x
- DeepL API key

## Configuration
cp config/translator.example.php config/translator.php

Set your DeepL API key in config/translator.php.

## Notes

Tool writes only to admin/lang/overrides

Base language file must exist for WHMCS to load overrides

Automatic translations should be reviewed manually

## Installation
```bash
git clone https://github.com/LLC-Hosting-in-Ukraine/whmcs-admin-translator.git
```
## Usage
```bash
php bin/translate.php admin uk
php bin/translate.php admin uk --only=wizard
php bin/translate.php admin uk --limit=50
php bin/translate.php admin uk --dry-run
```

## License
MIT