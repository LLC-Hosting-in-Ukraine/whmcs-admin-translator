<?php

namespace HostingInUA\WhmcsTranslator\Util;

final class PhpLangParser
{
    public static function parseAdmin(string $file): array
    {

        // Мінімальний контекст для WHMCS lang-файлів
        if (!defined('WHMCS')) {
            define('WHMCS', true);
        }
        
        $_ADMINLANG = [];

        require $file;

        if (!is_array($_ADMINLANG)) {
            return [];
        }

        return self::flatten($_ADMINLANG);
    }

    private static function flatten(array $data, string $prefix = ''): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;

            if (is_array($value)) {
                $result += self::flatten($value, $fullKey);
            } elseif (is_string($value)) {
                $result[$fullKey] = $value;
            }
        }

        return $result;
    }
}
