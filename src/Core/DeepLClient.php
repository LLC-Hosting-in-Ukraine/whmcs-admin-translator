<?php

namespace HostingInUA\WhmcsTranslator\Core;

final class DeepLClient
{
    private int $requests = 0;
    private int $limitPerMinute = 25;

    public function __construct(
        private string $apiKey,
        private string $sourceLang,
        private string $targetLang,
        private string $endpoint
    ) {}

    public function translate(string $text): string
    {
        $this->rateLimit();

        $response = $this->request([
            'text'        => $text,
            'source_lang'=> $this->sourceLang,
            'target_lang'=> $this->targetLang,
        ]);

        if (!isset($response['translations'][0]['text'])) {
            throw new \RuntimeException('DeepL response invalid');
        }

        return $response['translations'][0]['text'];
    }

    private function request(array $data): array
    {
        $ch = curl_init($this->endpoint);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: DeepL-Auth-Key ' . $this->apiKey,
            ],
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_TIMEOUT        => 20,
        ]);

        $raw = curl_exec($ch);

        if ($raw === false) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            throw new \RuntimeException('DeepL HTTP ' . $status . ': ' . $raw);
        }

        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    private function rateLimit(): void
    {
        $this->requests++;

        if ($this->requests >= $this->limitPerMinute) {
            sleep(60);
            $this->requests = 0;
        }
    }
}
