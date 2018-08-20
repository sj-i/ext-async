<?php

namespace Concurrent\Network;

$socket = TcpSocket::connect('nghttp2.org', 443);

try {
    var_dump($socket->getLocalPeer(), $socket->getRemotePeer());
    
    $socket->encrypt();
    
    $socket->nodelay(true);
    $socket->write("GET / HTTP/1.0\r\nHost: nghttp2.org\r\nConnection: close\r\n\r\n");
    
    while (null !== ($chunk = $socket->read())) {
        var_dump($chunk);
    }
} finally {
    $socket->close();
}
