<?php

namespace App\Services;

interface AgentConnectionInterface
{
    public function getResourceId(): string;

    public function send(string $data): void;

    public function close(): void;
}
