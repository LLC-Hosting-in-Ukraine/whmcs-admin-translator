<?php

namespace HostingInUA\WhmcsTranslator\Core;

final class Translator
{
    private DeepLClient $client;

    public function __construct(
        private Context $context
    ) {
        $translator = $context->config('translator');
        $deepl      = $context->config('deepl');

        if (empty($translator['source_lang']) || empty($translator['target_lang'])) {
            throw new \RuntimeException('Translator languages are not configured');
        }

        if (empty($deepl['api_key']) || empty($deepl['endpoint'])) {
            throw new \RuntimeException('DeepL is not configured');
        }

        $this->client = new DeepLClient(
            $deepl['api_key'],
            $translator['source_lang'],
            $translator['target_lang'],
            $deepl['endpoint']
        );
    }

    public function translate(array $strings): array
    {
        $result  = [];
        $counter = 0;

        $limit = $this->context->limit();
        $only  = $this->context->only();

        // рахуємо тільки валідні рядки з урахуванням --only
        $total = 0;
        foreach ($strings as $key => $value) {
            if (!is_string($value) || trim($value) === '') {
                continue;
            }

            if ($only !== null && !str_starts_with($key, $only . '.')) {
                continue;
            }

            $total++;
        }

        foreach ($strings as $key => $value) {
            if (!is_string($value) || trim($value) === '') {
                continue;
            }

            if ($only !== null && !str_starts_with($key, $only . '.')) {
                continue;
            }

            if ($limit !== null && $counter >= $limit) {
                echo "\nLimit reached ($limit). Stopping.\n";
                break;
            }

            $counter++;
            echo "\r[$counter/$total] Translating...";
            flush();

            $result[$key] = $this->client->translate($value);
        }

        echo "\n";

        return $result;
    }
}
