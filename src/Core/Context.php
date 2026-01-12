<?php

namespace HostingInUA\WhmcsTranslator\Core;

final class Context
{
    public function __construct(
        public readonly string $type,
        public readonly string $targetLang,
        private array $config
    ) {}

    public function limit(): ?int
    {
        return $this->config['options']['limit'] ?? null;
    }

    public function config(string $key): mixed
    {
        return $this->config[$key] ?? null;
    }

    public function path(string $key): string
    {
        return $this->config['paths'][$key];
    }

    public function only(): ?string
    {
        return $this->config['options']['only'] ?? null;
    }

    public function output(string $key): string
    {
        return $this->config['output'][$key];
    }

    public function isDryRun(): bool
    {
        return (bool)($this->config['options']['dry_run'] ?? false);
    }
}
