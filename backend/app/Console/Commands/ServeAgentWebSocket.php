<?php

namespace App\Console\Commands;

use App\Services\AgentSocketConnection;
use App\Services\AgentWebSocketService;
use Illuminate\Console\Command;

class ServeAgentWebSocket extends Command
{
    protected $signature = 'agent:ws {--host=0.0.0.0} {--port=8000}';

    protected $description = 'Start the Laravel WebSocket server for agent connections';

    public function handle(): int
    {
        $host = $this->option('host');
        $port = (int) $this->option('port');

        $serverSocket = stream_socket_server("tcp://{$host}:{$port}", $errno, $errstr);

        if (!$serverSocket) {
            $this->error("Unable to listen on {$host}:{$port}: {$errstr}");

            return self::FAILURE;
        }

        $service = new AgentWebSocketService();
        $this->info("Agent WebSocket server listening on ws://{$host}:{$port}/ws/agent");

        while (true) {
            try {
                $clientSocket = @stream_socket_accept($serverSocket, 1);

                if ($clientSocket === false) {
                    usleep(10000);
                    continue;
                }
            } catch (\Throwable $e) {
                $this->warn($e->getMessage());
                usleep(10000);
                continue;
            }

            stream_set_blocking($clientSocket, false);

            $connection = new AgentSocketConnection(
                (string) intval(microtime(true) * 1000),
                $clientSocket
            );

            $service->registerConnection($connection);

            $handshake = '';

            while (is_resource($clientSocket)) {
                $chunk = fread($clientSocket, 4096);

                if ($chunk === '' || $chunk === false) {
                    usleep(20000);
                    continue;
                }

                $handshake .= $chunk;

                if (str_contains($handshake, "\r\n\r\n")) {
                    break;
                }
            }

            if (preg_match('/GET \/ws\/agent HTTP\//', $handshake) === 1) {
                $this->performHandshake($clientSocket, $handshake);
            }

            while (is_resource($clientSocket)) {
                $chunk = fread($clientSocket, 4096);

                if ($chunk === '' || $chunk === false) {
                    usleep(20000);
                    continue;
                }

                $payload = $connection->receive($chunk);

                if ($payload === '') {
                    continue;
                }

                $service->handleMessage($connection, $payload);
            }

            $service->disconnect($connection);
        }
    }

    protected function performHandshake($clientSocket, string $headers): void
    {
        if (preg_match('/Sec-WebSocket-Key: (.*?)\r\n/i', $headers, $matches) !== 1) {
            return;
        }

        $key = trim($matches[1]);
        $accept = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));

        $response = "HTTP/1.1 101 Switching Protocols\r\n";
        $response .= "Upgrade: websocket\r\n";
        $response .= "Connection: Upgrade\r\n";
        $response .= "Sec-WebSocket-Accept: {$accept}\r\n\r\n";

        fwrite($clientSocket, $response);
    }
}
