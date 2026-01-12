<?php

namespace HostingInUA\WhmcsTranslator\Writer;

use HostingInUA\WhmcsTranslator\Core\Context;
use HostingInUA\WhmcsTranslator\Util\ArrayUnflattener;

final class AdminWriter
{
    public function __construct(
        private Context $context
    ) {}

    public function write(array $flatTranslated): void
    {
        $dir  = $this->context->output('admin');
        $lang = strtolower($this->context->targetLang);
        $file = $dir . '/' . $lang . '.php';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // 1. Завантажуємо існуючі overrides
        $existing = $this->loadExisting($file);

        // 2. Розплющуємо існуючі ключі
        $existingFlat = $this->flatten($existing);

        // 3. Беремо тільки нові ключі
        $newFlat = array_diff_key($flatTranslated, $existingFlat);

        if ($this->context->isDryRun()) {
            echo "Dry-run: would add " . count($newFlat) . " strings\n";
            return;
        }

        // 4. Перетворюємо назад у вкладену структуру
        $newNested = ArrayUnflattener::unflatten($newFlat);

        // 5. Мерджимо
        $merged = array_replace_recursive($existing, $newNested);

        // 6. Генеруємо PHP-файл
        $php = "<?php\n\n";

        foreach ($merged as $group => $values) {
            if (is_array($values)) {
                $php .= $this->dumpGroup($group, $values);
            } else {
                $php .= "\$_ADMINLANG['$group'] = "
                . var_export($values, true)
                . ";\n";
            }
}


        file_put_contents($file, $php);
    }

    /**
     * Завантажити існуючий admin override
     */
    private function loadExisting(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }

        if (!defined('WHMCS')) {
            define('WHMCS', true);
        }

        $_ADMINLANG = [];
        require $file;

        return is_array($_ADMINLANG) ? $_ADMINLANG : [];
    }

    /**
     * Розплющити вкладений масив у dot-keys
     */
    private function flatten(array $data, string $prefix = ''): array
    {
        $out = [];

        foreach ($data as $key => $value) {
            $full = $prefix === '' ? $key : $prefix . '.' . $key;

            if (is_array($value)) {
                $out += $this->flatten($value, $full);
            } else {
                $out[$full] = $value;
            }
        }

        return $out;
    }

    /**
     * Генерує:
     * $_ADMINLANG['wizard']['ssl']['url'] = '...';
     */
    private function dumpGroup(string $group, array $values, string $prefix = ''): string
    {
        $out = '';

        foreach ($values as $key => $value) {
            $current = $prefix === ''
                ? "['$key']"
                : $prefix . "['$key']";

            if (is_array($value)) {
                $out .= $this->dumpGroup($group, $value, $current);
            } else {
                $out .= "\$_ADMINLANG['$group']{$current} = "
                    . var_export($value, true) . ";\n";
            }
        }

        return $out;
    }
}
