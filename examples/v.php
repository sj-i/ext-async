<?php

namespace Concurrent\Network;

$encrypted = ($_SERVER['argv'][1] ?? null) ? true : false;
$server = TcpServer::listen('localhost', 8080);

try {
    for ($i = 0; $i < 3; $i++) {
        $socket = $server->accept();
        
        \Concurrent\Task::async(function () use ($socket, $encrypted) {
            var_dump('CLIENT CONNECTED');
            
            try {
                if ($encrypted) {
                    var_dump('Negotiate TLS');
                    $socket->encrypt(true);
                    var_dump('TLS established');
                }
                
                $buffer = '';
                
                while (null !== ($chunk = $socket->read())) {
                    $buffer .= $chunk;
                }
                
                var_dump($buffer);
                
                $socket->write('RECEIVED: ' . \strtoupper($buffer));
            } catch (\Throwable $e) {
                echo $e, "\n\n";
            } finally {
                $socket->close();
                
                var_dump('CLIENT DISCONNECTED');
            }
        });
    }
} finally {
    $server->close();
}
