<?php

namespace Concurrent\Network;

$encrypted = ($_SERVER['argv'][1] ?? null) ? true : false;
$socket = TcpSocket::connect('localhost', 8080);

try {
    if ($encrypted) {
        var_dump('Negotiate TLS');
        $socket->encrypt();
    }
    
    $socket->write('Hello World :)');
    $socket->writeStream()->close();
    
    while (null !== ($chunk = $socket->read())) {
        var_dump($chunk);
    }
} finally {
    $socket->close();
}
