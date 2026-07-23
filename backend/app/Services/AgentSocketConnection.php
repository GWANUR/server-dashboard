<?php

namespace App\Services;

class AgentSocketConnection implements AgentConnectionInterface
{
    protected string $resourceId;

    protected mixed $socket;

    protected string $buffer = '';

    public function __construct(string $resourceId, mixed $socket)
    {
        $this->resourceId = $resourceId;
        $this->socket = $socket;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function send(string $data): void
    {
        if (!is_resource($this->socket)) {
            return;
        }

        $frame = pack('C', 0x81) . pack('C', strlen($data)) . $data;
        fwrite($this->socket, $frame);
    }

    public function close(): void
    {
        if (is_resource($this->socket)) {
            fclose($this->socket);
        }
    }

    public function receive(string $data): string
    {
        $this->buffer .= $data;

        while (($payload = $this->readFrame()) !== null) {
            return $payload;
        }

        return '';
    }

    protected function readFrame(): ?string
    {
        if (strlen($this->buffer) < 2) {
            return null;
        }

        $firstByte = ord($this->buffer[0]);
        $secondByte = ord($this->buffer[1]);
        $masked = (bool) ($secondByte & 0x80);
        $length = $secondByte & 0x7F;

        if ($length === 126) {
            if (strlen($this->buffer) < 4) {
                return null;
            }
            $length = unpack('n', substr($this->buffer, 2, 2))[1];
            $offset = 4;
        } elseif ($length === 127) {
            if (strlen($this->buffer) < 10) {
                return null;
            }
            $length = unpack('J', substr($this->buffer, 2, 8))[1];
            $offset = 10;
        } else {
            $offset = 2;
        }

        if ($masked) {
            if (strlen($this->buffer) < $offset + 4 + $length) {
                return null;
            }
            $mask = substr($this->buffer, $offset, 4);
            $offset += 4;
        } else {
            if (strlen($this->buffer) < $offset + $length) {
                return null;
            }
        }

        $frame = substr($this->buffer, $offset, $length);
        $this->buffer = substr($this->buffer, $offset + $length);

        if ($masked) {
            $frame = $this->mask($frame, $mask);
        }

        return $frame;
    }

    protected function mask(string $payload, string $mask): string
    {
        $result = '';
        for ($i = 0; $i < strlen($payload); $i++) {
            $result .= chr(ord($payload[$i]) ^ ord($mask[$i % 4]));
        }

        return $result;
    }
}
