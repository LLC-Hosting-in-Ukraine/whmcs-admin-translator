<?php

namespace HostingInUA\WhmcsTranslator\Util;

final class ArrayUnflattener
{
    public static function unflatten(array $flat): array
    {
        $result = [];

        foreach ($flat as $key => $value) {
            $parts = explode('.', $key);
            $ref = &$result;

            foreach ($parts as $part) {
                if (!isset($ref[$part]) || !is_array($ref[$part])) {
                    $ref[$part] = [];
                }
                $ref = &$ref[$part];
            }

            $ref = $value;
            unset($ref);
        }

        return $result;
    }
}
