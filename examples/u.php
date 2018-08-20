<?php

namespace Concurrent\Network;

$encrypted = ($_SERVER['argv'][1] ?? null) ? true : false;

$socket = TcpSocket::connect('google.com', $encrypted ? 443 : 80);

try {
    var_dump($socket->getLocalPeer(), $socket->getRemotePeer());
    
    $socket->nodelay(true);
    
    if ($encrypted) {
        $socket->encrypt();
    }
    
    $socket->write("GET / HTTP/1.0\r\nHost: google.com\r\nConnection: close\r\n\r\n");
    
    while (null !== ($chunk = $socket->read())) {
        var_dump($chunk);
    }
} finally {
    $socket->close();
}
