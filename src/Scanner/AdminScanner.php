<?php

namespace HostingInUA\WhmcsTranslator\Scanner;

use HostingInUA\WhmcsTranslator\Core\Context;
use HostingInUA\WhmcsTranslator\Util\PhpLangParser;

final class AdminScanner
{
    public function __construct(
        private Context $context
    ) {}

    public function scan(): array
    {
        $path = $this->context->path('admin');
        $result = [];

        foreach (glob($path . '/*.php') as $file) {
            $data = PhpLangParser::parseAdmin($file);
            $result = array_merge($result, $data);
        }

        return $result;
    }
}
