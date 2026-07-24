<?php

namespace App\Services;

class AgentSocketConnection
{
    public string $id;

    private $socket;

    public bool $authenticated = false;

    public ?string $agentUuid = null;

    public function __construct(string $id, $socket)
    {
        $this->id = $id;
        $this->socket = $socket;
    }

    public function send(array $payload): void
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            return;
        }

        fwrite($this->socket, $this->encodeFrame($json));
    }

    public function receive(string $data): string
    {
        return $this->decodeFrame($data);
    }

    public function close(): void
    {
        if (is_resource($this->socket)) {
            fclose($this->socket);
        }
    }

    private function encodeFrame(string $payload): string
    {
        $length = strlen($payload);

        $frame = chr(0x81);

        if ($length <= 125) {
            $frame .= chr($length);
        } elseif ($length <= 65535) {
            $frame .= chr(126);
            $frame .= pack('n', $length);
        } else {
            $frame .= chr(127);
            $frame .= pack('J', $length);
        }

        return $frame . $payload;
    }

    private function decodeFrame(string $data): string
    {
        if (strlen($data) < 6) {
            return '';
        }

        $length = ord($data[1]) & 127;

        $offset = 2;

        if ($length === 126) {
            $length = unpack('n', substr($data, 2, 2))[1];
            $offset = 4;
        } elseif ($length === 127) {
            $length = unpack('J', substr($data, 2, 8))[1];
            $offset = 10;
        }

        $mask = substr($data, $offset, 4);
        $offset += 4;

        $payload = substr($data, $offset, $length);

        $decoded = '';

        for ($i = 0; $i < $length; $i++) {
            $decoded .= $payload[$i] ^ $mask[$i % 4];
        }

        return $decoded;
    }
}